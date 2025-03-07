<?php

namespace App\Policies;

use App\Models\CaseFile;
use App\Models\User;

class CaseFilePolicy
{
    public function view(User $user, CaseFile $caseFile): bool
    {
        return $user->id === $caseFile->user_id || $caseFile->hasCollaborator($user);
    }

    public function update(User $user, CaseFile $caseFile): bool
    {
        if ($user->id === $caseFile->user_id) {
            return true;
        }

        $role = $caseFile->getCollaboratorRole($user);
        return in_array($role, ['editor', 'manager']);
    }

    public function delete(User $user, CaseFile $caseFile): bool
    {
        return $user->id === $caseFile->user_id;
    }

    public function manageCollaborators(User $user, CaseFile $caseFile): bool
    {
        if ($user->id === $caseFile->user_id) {
            return true;
        }

        return $caseFile->getCollaboratorRole($user) === 'manager';
    }

    public function inviteCollaborators(User $user, CaseFile $caseFile): bool
    {
        return $this->manageCollaborators($user, $caseFile);
    }

    public function removeCollaborators(User $user, CaseFile $caseFile): bool
    {
        return $this->manageCollaborators($user, $caseFile);
    }
}
