<?php

namespace App\Policies;

use App\Models\Document;
use App\Models\User;

class DocumentPolicy
{
    public function view(User $user, Document $document): bool
    {
        // Add your authorization logic here
        // For example, check if the user has access to the case file
        return $user->can('view', $document->case_file);
    }
}