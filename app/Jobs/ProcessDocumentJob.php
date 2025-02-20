<?php

namespace App\Jobs;

use App\Models\Document;
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

    public function __construct(
        private Document $document
    ) {}

    public function handle(): void
    {
        try {
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
        $this->document->update(['ingestion_status' => 'uploading']);

        $fileContent = Storage::disk('s3')->get($this->document->storage_path);
        
        $response = OpenAI::files()->upload([
            'purpose' => 'assistants',
            'file' => $fileContent,
            'name' => $this->document->original_filename
        ]);

        $this->document->update(['openai_file_id' => $response->id]);

        return $this;
    }

    private function generateSummaryIfNeeded(): self
    {
        if ($this->document->title && $this->document->description) {
            return $this;
        }

        $this->document->update(['ingestion_status' => 'summarizing']);

        $response = OpenAI::chat()->create([
            'model' => 'gpt-4',
            'messages' => [
                [
                    'role' => 'system',
                    'content' => 'Generate a concise title and summary for the document. Respond in JSON format: {"title": "...", "summary": "..."}'
                ],
                [
                    'role' => 'user',
                    'content' => "File: {$this->document->original_filename}\n\nGenerate title and summary."
                ]
            ],
            'file_ids' => [$this->document->openai_file_id]
        ]);

        $content = json_decode($response->choices[0]->message->content, true);

        $this->document->update([
            'title' => $content['title'] ?? $this->document->title,
            'description' => $content['summary'] ?? $this->document->description
        ]);

        return $this;
    }

    private function attachToVectorStore(): self
    {
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