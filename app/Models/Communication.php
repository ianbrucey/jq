<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Communication extends Model
{
    protected $fillable = [
        'thread_id',
        'type',
        'content',
        'sent_at',
        'subject'
    ];

    protected $casts = [
        'sent_at' => 'datetime'
    ];

    public function thread(): BelongsTo
    {
        return $this->belongsTo(Thread::class);
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(Party::class, 'communication_participants')
            ->withPivot('role')
            ->withTimestamps();
    }

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class, 'communication_documents')
            ->withTimestamps();
    }

    public function delete()
    {
        // The related records in communication_participants and communication_documents
        // will be automatically deleted due to the onDelete: cascade constraint
        return parent::delete();
    }
}
