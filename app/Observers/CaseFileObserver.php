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
        // TODO: Implement cleanup of OpenAI resources
        // This would delete the assistant and vector store
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
