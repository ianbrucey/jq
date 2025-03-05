<?php

namespace App\Jobs;

use App\Models\Document;
use App\Models\Party;
use App\Services\OpenAI\CaseAssistantService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use OpenAI\Laravel\Facades\OpenAI;

class ProcessDocumentJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;
    public $backoff = [10, 60, 120];
    public $timeout = 60;

    private CaseAssistantService $assistantService;

    public function __construct(
        private Document $document
    ) {
        $this->assistantService = app(CaseAssistantService::class);
    }

    /**
     * @throws \Exception
     */
    private function configureOpenAI(): void
    {
        $this->assistantService->configureCaseCredentials($this->document->caseFile);
    }

    public function handle(): void
    {
        try {
            $this->configureOpenAI();

            $this->uploadToOpenAI()
                ->generateSummaryIfNeeded()
                ->attachToVectorStore();

            $this->document->update([
                'ingestion_status' => 'indexed',
                'ingested_at' => now()
            ]);
        } catch (\Exception $e) {
            Log::error('Document processing failed', [
                'document_id' => $this->document->id,
                'error' => $e->getMessage()
            ]);

            $this->document->update([
                'ingestion_status' => 'failed',
                'ingestion_error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    private function uploadToOpenAI(): self
    {
        $this->configureOpenAI();
        $this->document->update(['ingestion_status' => 'uploading']);

        Storage::disk('s3')->setVisibility($this->document->storage_path, 'public');

        $url = Storage::disk('s3')->url(
            $this->document->storage_path
        );

        try {// Upload to OpenAI
            $response = OpenAI::files()->upload([
                'purpose' => 'assistants',
                'file'    => fopen($url, 'r'),
            ]);

        } catch (\Exception $e) {
            throw new \Exception('Failed to upload document to OpenAI: ' . $e->getMessage());
        } finally {
            Storage::disk('s3')->setVisibility($this->document->storage_path, 'private');
        }


        // Update document record with OpenAI File ID
        $this->document->update(['openai_file_id' => $response->id]);

        return $this;
    }

    private function generateSummaryIfNeeded(): self
    {
        if ($this->document->title && $this->document->description) {
            return $this;
        }

        $this->configureOpenAI();
        $this->document->update(['ingestion_status' => 'summarizing']);

        try {
            // Catchall for any image type
            if (str_starts_with($this->document->mime_type, 'image/')) {
                // 1) Generate a temporary URL for the image in S3
                $url = Storage::disk('s3')->temporaryUrl(
                    $this->document->storage_path,
                    now()->addMinutes(5)
                );

                // 2) Build the Chat Completion messages for GPT-4 Vision
                $messages = [
                    [
                        'role' => 'system',
                        'content' => 'You are a vision-capable assistant. '
                            . 'Generate a concise title and summary for the following image. '
                            . 'Return *only* valid JSON in the format {"title": "...", "summary": "..."} '
                            . 'with no extra text, code blocks, or markdown.'
                    ],
                    [
                        'role' => 'user',
                        'content' => "Here is an image URL: $url\n\n"
                            . "Analyze this image, understand it's content, then provide the JSON response."
                    ],
                ];

                $response = OpenAI::chat()->create([
                    'model'      => 'gpt-4o-mini',
                    'messages'   => $messages,
                    'max_tokens' => 500,
                ]);

                $rawContent = $response->choices[0]->message->content ?? '';
                $content = json_decode($rawContent, true);

                if (json_last_error() !== JSON_ERROR_NONE) {
                    Log::error('JSON decode error', [
                        'error' => json_last_error_msg(),
                        'raw_content' => $rawContent
                    ]);
                    throw new \Exception('Failed to decode JSON response: ' . json_last_error_msg());
                }

                // Skip vector store for images
                $this->document->update(['skip_vector_store' => true]);

            } else {
                switch ($this->document->mime_type) {
                    case 'application/pdf':
                    case 'application/msword':
                    case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                        // 1) Use the case's Assistant ID for text documents
                        $assistantId = $this->document->caseFile->openai_assistant_id;
                        if (!$assistantId) {
                            throw new \Exception('Case has no associated OpenAI assistant');
                        }

                        // 2) Create a new thread
                        $thread = OpenAI::threads()->create([
                            'messages' => [
                                [
                                    'role'    => 'user',
                                    'content' => 'Please analyze this document and provide:
1. A title and summary of the document.
2. Any parties and their addresses mentioned in the document, but only if you are very confident. ignore addresses or parties that you cannot be sure of. Do not make up your own.

Return valid JSON only, in the format:
{
    "title": "...",
    "summary": "...",
    "parties": [
        {
            "name": "...",
            "address_line1": "...",
            "address_line2": null,
            "city": "...",
            "state": "...",
            "zip": "...",
            "email": null,
            "phone": null,
            "relationship": "..."
        }
    ]
}

For parties, only include entries where you are confident about the name and address. The relationship field should be one of: attorney, court, or opponent. If relationship is unclear, omit it.',
                                    'attachments' => [
                                        [
                                            'file_id' => $this->document->openai_file_id,
                                            'tools' => [
                                                ['type' => 'file_search']
                                            ]
                                        ]
                                    ]
                                ]
                            ]
                        ]);

                        // 3) Create a run on that thread
                        $run = OpenAI::threads()->runs()->create($thread->id, [
                            'assistant_id' => $assistantId,
                        ]);

                        // 4) Poll until the run is completed or fails
                        $startTime = time();
                        $timeout = 300; // 5 minutes
                        while (in_array($run->status, ['queued', 'in_progress'])) {
                            if (time() - $startTime > $timeout) {
                                throw new \Exception('Run timed out after 5 minutes');
                            }
                            sleep(1);
                            $run = OpenAI::threads()->runs()->retrieve($thread->id, $run->id);
                        }

                        if ($run->status !== 'completed') {
                            throw new \Exception("Assistant run failed with status: {$run->status}");
                        }

                        // 5) Retrieve all messages; find the last assistant message
                        $allMessages = OpenAI::threads()->messages()->list($thread->id);
                        $assistantMessage = collect($allMessages->data)
                            ->where('role', 'assistant')
                            ->last();

                        if (!$assistantMessage) {
                            throw new \Exception('No valid assistant message found in thread');
                        }

                        // 6) Extract the JSON response
                        //    If the library segments text differently, you may need to combine or check multiple array items.
                        $rawContent = $assistantMessage->content[0]->text->value ?? '';
                        // Remove any triple backticks if they appear
                        $rawContent = preg_replace('/```(?:json)?(.*?)```/s', '$1', $rawContent);
                        $content = json_decode($rawContent, true);

                        if (json_last_error() !== JSON_ERROR_NONE) {
                            Log::error('JSON decode error', [
                                'error'       => json_last_error_msg(),
                                'raw_content' => $rawContent
                            ]);
                            throw new \Exception('Failed to decode JSON response: ' . json_last_error_msg());
                        }

                        // Validate the JSON structure
                        if (!isset($content['title']) || !isset($content['summary'])) {
                            Log::error('Invalid response format from OpenAI', ['response' => $content]);
                            throw new \Exception('OpenAI response missing required fields');
                        }

                        $this->document->update([
                            'title' => $content['title'],
                            'description' => $content['summary']
                        ]);

                        // Handle extracted parties if present
                        if (isset($content['parties']) && is_array($content['parties'])) {
                            Party::createFromDocumentAnalysis(
                                $content['parties'],
                                $this->document->caseFile->user_id
                            );
                        }

                        break;

                    default:
                        throw new \Exception("Unsupported file type: {$this->document->mime_type}");
                }
            }

            return $this;
        } catch (\Exception $e) {
            throw new \Exception('Failed to generate document summary: ' . $e->getMessage());
        }
    }



    private function attachToVectorStore(): self
    {
        // Early return conditions with additional logging
        if ($this->document->skip_vector_store) {
            Log::info('Skipping vector store attachment - skip_vector_store flag is true', [
                'document_id' => $this->document->id,
                'mime_type' => $this->document->mime_type
            ]);
            return $this;
        }

        // Check for any image type (image/*)
        if (str_starts_with($this->document->mime_type, 'image/')) {
            Log::info('Skipping vector store attachment for image file', [
                'document_id' => $this->document->id,
                'mime_type' => $this->document->mime_type
            ]);
            $this->document->update(['skip_vector_store' => true]);
            return $this;
        }

        $this->configureOpenAI();
        $this->document->update(['ingestion_status' => 'indexing']);

        $vectorStoreId = $this->document->caseFile->openai_vector_store_id;

        if (!$vectorStoreId) {
            throw new \Exception('Case file has no vector store ID');
        }

        if (!$this->document->openai_file_id) {
            throw new \Exception('Document has no OpenAI file ID');
        }

        OpenAI::vectorStores()->files()->create($vectorStoreId, [
            'file_id' => $this->document->openai_file_id
        ]);

        return $this;
    }
}
