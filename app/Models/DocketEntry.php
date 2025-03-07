<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DocketEntry extends Model
{
    protected $fillable = [
        'case_file_id',
        'entry_date',
        'entry_type',
        'title',
        'description',
        'filing_party',
        'judge',
        'docket_number',
        'status',
        'is_sealed',
    ];

    protected $casts = [
        'entry_date' => 'date',
        'is_sealed' => 'boolean',
    ];

    const ENTRY_TYPES = [
        'filing',
        'order',
        'hearing',
        'notice',
        'motion',
        'judgment',
        'other',
    ];

    const STATUSES = [
        'pending',
        'granted',
        'denied',
        'heard',
        'continued',
        'withdrawn',
    ];

    public function caseFile(): BelongsTo
    {
        return $this->belongsTo(CaseFile::class);
    }

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class, 'docket_documents')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function deadlines(): HasMany
    {
        return $this->hasMany(DocketDeadline::class);
    }

    public function primaryDocument(): BelongsToMany
    {
        return $this->documents()->wherePivot('is_primary', true);
    }

    public function communications(): BelongsToMany
    {
        return $this->belongsToMany(Communication::class, 'docket_entry_communications')
            ->withTimestamps();
    }
}
