<?php

namespace App\Observers;

use App\Models\CaseFile;
use App\Services\OpenAI\CaseAssistantService;
use Illuminate\Support\Facades\Log;

class CaseFileObserver
{

    public function __construct(
        private CaseAssistantService $assistantService
    ) {}
    /**
     * Handle the CaseFile "created" event.
     */
    public function created(CaseFile $case): void
    {
        try {
            $this->assistantService->setupCaseResources($case);
        } catch (\Exception $e) {
            Log::error('Failed to setup OpenAI resources for case', [
                'case_id' => $case->id,
                'error' => $e->getMessage()
            ]);

            // You might want to add case status update here
            $case->update(['status' => 'setup_failed']);
        }
    }

    public function deleted(CaseFile $case): void
    {
        try {
            // Clean up OpenAI resources associated with the case
            if ($case->openai_assistant_id) {
                $this->assistantService->deleteAssistant($case->openai_assistant_id);
            }

            if ($case->openai_vector_store_id) {
                $this->assistantService->deleteVectorStore($case->openai_vector_store_id);
            }

            // Update the storage used for the OpenAI project
            if ($case->openai_project_id) {
                $project = $case->openaiProject;
                if ($project) {
                    $project->decrement('storage_used', $case->documents()->sum('file_size'));
                }
            }

            Log::info('Successfully cleaned up OpenAI resources for deleted case', [
                'case_id' => $case->id,
                'assistant_id' => $case->openai_assistant_id,
                'vector_store_id' => $case->openai_vector_store_id
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to cleanup OpenAI resources for deleted case', [
                'case_id' => $case->id,
                'error' => $e->getMessage()
            ]);
            // We don't rethrow the exception since the case is already deleted
            // and we don't want to prevent the deletion from completing
        }
    }

    /**
     * Handle the CaseFile "updated" event.
     */
    public function updated(CaseFile $caseFile): void
    {
        //
    }

    /**
     * Handle the CaseFile "restored" event.
     */
    public function restored(CaseFile $caseFile): void
    {
        //
    }

    /**
     * Handle the CaseFile "force deleted" event.
     */
    public function forceDeleted(CaseFile $caseFile): void
    {
        //
    }
}
