<?php

namespace App\Services\OpenAI;

use App\Models\CaseFile;
use App\Models\OpenAiProject;
use OpenAI\Laravel\Facades\OpenAI;
use Illuminate\Support\Facades\Log;
use Exception;

/**
 * Service for managing OpenAI assistants and vector stores for legal cases.
 *
 * This service handles the creation and configuration of OpenAI resources
 * required for each legal case, including an AI assistant and vector store
 * for document search capabilities.
 */
class CaseAssistantService
{
    /**
     * The OpenAI model to use for the assistant.
     */
    private const ASSISTANT_MODEL = 'gpt-4';

    /**
     * Base instructions defining the assistant's role and responsibilities.
     */
    private const ASSISTANT_INSTRUCTIONS = <<<EOT
You are a dedicated legal assistant for this case. Your role is to:
1. Provide accurate legal information and context based on case documents
2. Help draft legal documents while maintaining professional standards
3. Answer questions about the case using the provided documentation
4. Maintain strict confidentiality and professional ethics
5. Always clarify when legal advice is needed from a licensed attorney
EOT;

    /**
     * Gets a default OpenAI client using the least-used active project.
     *
     * @return void
     * @throws Exception When no active OpenAI projects are available
     */
    private function configureDefaultOpenAi(): void
    {
        $project = OpenAiProject::where('is_active', true)
            ->orderBy('storage_used')
            ->first();

        if (!$project) {
            throw new Exception('No available OpenAI projects');
        }

        // Configure OpenAI with the project credentials
        config([
            'openai.api_key' => $project->api_key,
            'openai.organization' => $project->organization_id
        ]);
    }

    /**
     * Configures OpenAI for the given case.
     *
     * Selects the least-used active OpenAI project if the case doesn't have one assigned.
     *
     * @param CaseFile $case The case requiring OpenAI configuration
     * @throws Exception When no active OpenAI projects are available
     */
    private function configureOpenAi(CaseFile $case): void
    {
        if (!$case->openai_project_id) {
            $project = OpenAiProject::where('is_active', true)
                ->orderBy('storage_used')
                ->first();

            if (!$project) {
                throw new Exception('No available OpenAI projects');
            }

            $case->update(['openai_project_id' => $project->id]);
        }

        $project = $case->openAiProject;

        // Configure OpenAI with the project credentials
        config([
            'openai.api_key' => $project->api_key,
            'openai.organization' => $project->organization_id
        ]);
    }

    /**
     * Sets up all required OpenAI resources for a case.
     *
     * Creates an AI assistant and vector store, then links them together.
     *
     * @param CaseFile $case The case requiring resource setup
     * @return bool True if setup was successful, false otherwise
     * @throws Exception When setup fails
     */
    public function setupCaseResources(CaseFile $case): bool
    {
        try {
            $this->configureOpenAi($case);

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

    /**
     * Creates an OpenAI assistant for the case.
     *
     * @param CaseFile $case The case requiring an assistant
     * @return bool True if creation was successful
     * @throws Exception When assistant creation fails
     */
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

    /**
     * Creates a vector store for the case's documents.
     *
     * @param CaseFile $case The case requiring a vector store
     * @return bool True if creation was successful
     * @throws Exception When vector store creation fails
     */
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

    /**
     * Attaches the vector store to the assistant for document search capabilities.
     *
     * @param CaseFile $case The case whose resources need to be linked
     * @return bool True if attachment was successful
     * @throws Exception When attachment fails
     */
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

    /**
     * Deletes an OpenAI assistant and its associated resources
     *
     * @param string $assistantId The OpenAI assistant ID to delete
     * @return void
     * @throws \Exception if deletion fails
     */
    public function deleteAssistant(string $assistantId): void
    {
        $this->configureDefaultOpenAi();
        OpenAI::assistants()->delete($assistantId);
    }

    /**
     * Deletes a vector store and its associated embeddings
     *
     * @param string $vectorStoreId The vector store ID to delete
     * @return void
     * @throws \Exception if deletion fails
     */
    public function deleteVectorStore(string $vectorStoreId): void
    {
        $this->configureDefaultOpenAi();
        OpenAI::files()->delete($vectorStoreId);
    }
}
