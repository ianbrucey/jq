<?php

namespace App\Events;

use App\Models\CaseFile;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CollaboratorRemoved
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public CaseFile $caseFile,
        public User $user
    ) {}
}