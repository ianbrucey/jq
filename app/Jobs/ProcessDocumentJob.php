<?php

namespace App\Jobs;

use App\Models\Document;
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

    public $tries = 3;
    public $backoff = [30, 60, 120];
    public $timeout = 600;

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

        $messages = [
            [
                'role' => 'system',
                'content' => 'Generate a concise title and summary for the document. Respond in JSON format: {"title": "...", "summary": "..."}'
            ]
        ];

        try {
            // Handle different file types
            switch ($this->document->mime_type) {
                case 'image/jpeg':
                case 'image/png':
                    // Keep existing vision API implementation for images
                    $url = Storage::disk('s3')->temporaryUrl(
                        $this->document->storage_path,
                        now()->addMinutes(5)
                    );

                    $messages[] = [
                        'role' => 'user',
                        'content' => [
                            [
                                'type' => 'image_url',
                                'image_url' => ['url' => $url]
                            ]
                        ]
                    ];

                    $response = OpenAI::chat()->create([
                        'model' => 'gpt-4-vision-preview',
                        'messages' => $messages,
                        'max_tokens' => 500
                    ]);

                    $content = json_decode($response->choices[0]->message->content, true);
                    break;

                case 'application/pdf':
                case 'application/msword':
                case 'application/vnd.openxmlformats-officedocument.wordprocessingml.document':
                    // Use the case's assistant for text documents
                    $assistantId = $this->document->caseFile->openai_assistant_id;

                    if (!$assistantId) {
                        throw new \Exception('Case has no associated OpenAI assistant');
                    }

                    // Create a thread
                    $thread = OpenAI::threads()->create([
                        'messages' => [
                            [
                                'role' => 'user',
                                'content' => 'Please analyze this document and generate a title and summary in JSON format: {"title": "...", "summary": "..."}'
                            ]
                        ]
                    ]);

                    // Run the assistant
                    $run = OpenAI::threads()->runs()->create($thread->id, [
                        'assistant_id' => $assistantId,
                    ]);

                    // Wait for completion
                    while (in_array($run->status, ['queued', 'in_progress'])) {
                        sleep(1);
                        $run = OpenAI::threads()->runs()->retrieve(
                            $thread->id,
                            $run->id
                        );
                    }

                    if ($run->status !== 'completed') {
                        throw new \Exception("Assistant run failed with status: {$run->status}");
                    }

                    // Get the response
                    $messages = OpenAI::threads()->messages()->list($thread->id);
                    $lastMessage = $messages->data[0];

                    $content = json_decode($lastMessage->content[0]->text->value, true);
                    break;

                default:
                    throw new \Exception("Unsupported file type: {$this->document->mime_type}");
            }

            if (!isset($content['title']) || !isset($content['summary'])) {
                throw new \Exception('Invalid response format from OpenAI');
            }

            $this->document->update([
                'title' => $content['title'] ?? $this->document->title,
                'description' => $content['summary'] ?? $this->document->description
            ]);

            return $this;
        } catch (\Exception $e) {
            throw new \Exception('Failed to generate document summary: ' . $e->getMessage());
        }
    }

    private function attachToVectorStore(): self
    {
        $this->configureOpenAI();
        $this->document->update(['ingestion_status' => 'indexing']);

        $vectorStoreId = $this->document->caseFile->openai_vector_store_id;

        if (!$vectorStoreId) {
            throw new \Exception('Case file has no vector store ID');
        }

        OpenAI::vectorStores()->files()->create($vectorStoreId, [
            'file_id' => $this->document->openai_file_id
        ]);

        return $this;
    }
}
