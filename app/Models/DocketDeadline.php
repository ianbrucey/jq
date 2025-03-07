<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DocketDeadline extends Model
{
    protected $fillable = [
        'docket_entry_id',
        'deadline_date',
        'deadline_type',
        'description',
        'reminder_id',
    ];

    protected $casts = [
        'deadline_date' => 'date',
    ];

    public function docketEntry(): BelongsTo
    {
        return $this->belongsTo(DocketEntry::class);
    }

    public function reminder(): BelongsTo
    {
        return $this->belongsTo(Reminder::class);
    }
}