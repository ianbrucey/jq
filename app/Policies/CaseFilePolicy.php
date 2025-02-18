<?php

namespace App\Policies;

use App\Models\CaseFile;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CaseFilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any case files.
     */
    public function viewAny(User $user): bool
    {
        return true; // All authenticated users can view the list
    }

    /**
     * Determine whether the user can view the case file.
     */
    public function view(User $user, CaseFile $caseFile): bool
    {
        return $user->id === $caseFile->user_id;
    }

    /**
     * Determine whether the user can create case files.
     */
    public function create(User $user): bool
    {
        return true; // All authenticated users can create
    }

    /**
     * Determine whether the user can update the case file.
     */
    public function update(User $user, CaseFile $caseFile): bool
    {
        return $user->id === $caseFile->user_id;
    }

    /**
     * Determine whether the user can delete the case file.
     */
    public function delete(User $user, CaseFile $caseFile): bool
    {
        return $user->id === $caseFile->user_id;
    }
}
