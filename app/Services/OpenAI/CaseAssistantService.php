<?php

namespace App\Services\OpenAI;

use App\Models\CaseFile;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;
use Exception;

class CaseAssistantService
{
    private const ASSISTANT_MODEL = 'gpt-4';
    private const ASSISTANT_INSTRUCTIONS = <<<EOT
You are a dedicated legal assistant for this case. Your role is to:
1. Provide accurate legal information and context based on case documents
2. Help draft legal documents while maintaining professional standards
3. Answer questions about the case using the provided documentation
4. Maintain strict confidentiality and professional ethics
5. Always clarify when legal advice is needed from a licensed attorney
EOT;

    public function setupCaseResources(CaseFile $case): bool
    {
        try {
            $assistant = $this->createAssistant($case);
            $vectorStore = $this->createVectorStore($case);

            if ($assistant && $vectorStore) {
                $this->attachVectorStoreToAssistant($case);
                return true;
            }

            return false;
        } catch (Exception $e) {
            Log::error('Failed to setup case resources', [
                'case_id' => $case->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw $e;
        }
    }

    private function createAssistant(CaseFile $case): bool
    {
        try {
            $response = OpenAI::assistants()->create([
                'name' => "Case Agent: {$case->title}",
                'instructions' => self::ASSISTANT_INSTRUCTIONS,
                'model' => self::ASSISTANT_MODEL,
                'tools' => [
                    ['type' => 'file_search'],
                    ['type' => 'retrieval']
                ]
            ]);

            $case->update(['openai_assistant_id' => $response->id]);
            return true;
        } catch (Exception $e) {
            Log::error('Failed to create assistant', [
                'case_id' => $case->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    private function createVectorStore(CaseFile $case): bool
    {
        try {
            $response = OpenAI::vectorStores()->create([
                'name' => "Case #{$case->id} Vector Store",
                'description' => "Vector store for {$case->title}",
                'expires_after' => null
            ]);

            $case->update(['openai_vector_store_id' => $response->id]);
            return true;
        } catch (Exception $e) {
            Log::error('Failed to create vector store', [
                'case_id' => $case->id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }

    private function attachVectorStoreToAssistant(CaseFile $case): bool
    {
        try {
            OpenAI::assistants()->update($case->openai_assistant_id, [
                'tool_resources' => [
                    'file_search' => [
                        'vector_store_ids' => [$case->openai_vector_store_id]
                    ]
                ]
            ]);

            return true;
        } catch (Exception $e) {
            Log::error('Failed to attach vector store to assistant', [
                'case_id' => $case->id,
                'assistant_id' => $case->openai_assistant_id,
                'vector_store_id' => $case->openai_vector_store_id,
                'error' => $e->getMessage()
            ]);

            throw $e;
        }
    }
}
