<?php

namespace App\Traits;

use App\Models\CaseAccessLog;

trait LogsCaseAccess
{
    protected function logCaseAccess(string $action, array $metadata = [])
    {
        CaseAccessLog::create([
            'case_file_id' => $this->caseFile->id,
            'user_id' => auth()->id(),
            'action' => $action,
            'metadata' => $metadata,
        ]);
    }

    protected function logViewAccess()
    {
        $this->logCaseAccess('view');
    }

    protected function logEditAccess(string $field, $oldValue, $newValue)
    {
        $this->logCaseAccess('edit', [
            'field' => $field,
            'old_value' => $oldValue,
            'new_value' => $newValue,
        ]);
    }

    protected function logCollaboratorAction(string $action, array $details)
    {
        $this->logCaseAccess('collaborator_' . $action, $details);
    }
}