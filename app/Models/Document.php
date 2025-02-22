<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_file_id',
        'draft_id',
        'storage_path',
        'original_filename',
        'mime_type',
        'file_size',
        'title',
        'description',
        'ingestion_status',
        'ingestion_error',
        'ingested_at',
        'openai_file_id',
        'skip_vector_store'
    ];

    protected $casts = [
        'ingested_at' => 'datetime',
    ];

    public function caseFile(): BelongsTo
    {
        return $this->belongsTo(CaseFile::class);
    }

    public function draft(): BelongsTo
    {
        return $this->belongsTo(Draft::class);
    }
}
