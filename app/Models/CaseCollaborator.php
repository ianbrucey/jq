<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseCollaborator extends Model
{
    protected $fillable = [
        'case_file_id',
        'user_id',
        'role',
        'status'
    ];

    protected $casts = [
        'status' => 'string',
        'role' => 'string'
    ];

    public function caseFile(): BelongsTo
    {
        return $this->belongsTo(CaseFile::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}