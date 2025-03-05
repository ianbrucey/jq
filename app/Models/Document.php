<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;

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

    /**
     * Get human readable file size
     *
     * @return string
     */
    public function getHumanFileSizeAttribute()
    {
        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    /**
     * Scope a query to search documents by filename or title
     *
     * @param Builder $query
     * @param string $search
     * @return Builder
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        return $query->where(function ($query) use ($search) {
            $query->where('original_filename', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
        });
    }
}
