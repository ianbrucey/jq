<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CaseFile extends Model
{
    use HasFactory;

    protected $table = 'case_files';

    protected $fillable = [
        'title',
        'case_number',
        'desired_outcome',
        'user_id',
        'status',
        'filed_date',
        'openai_assistant_id',
        'openai_vector_store_id',
        'openai_project_id',
        'collaboration_enabled',
        'max_collaborators'
    ];

    protected $casts = [
        'collaboration_enabled' => 'boolean',
        'max_collaborators' => 'integer',
        'filed_date' => 'date'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function documents(): HasMany
    {
        return $this->hasMany(Document::class);
    }

    public function summaries()
    {
        return $this->hasMany(CaseSummary::class)->orderBy('created_at', 'desc');
    }

    public function openAiProject()
    {
        return $this->belongsTo(OpenAiProject::class, 'openai_project_id');
    }

    public function threads(): HasMany
    {
        return $this->hasMany(Thread::class);
    }

    public function collaborators()
    {
        return $this->hasMany(CaseCollaborator::class);
    }

    public function accessLogs(): HasMany
    {
        return $this->hasMany(CaseAccessLog::class);
    }

    public function hasCollaborator(User $user): bool
    {
        return $this->collaborators()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->exists();
    }

    public function getCollaboratorRole(User $user): ?string
    {
        $collaborator = $this->collaborators()
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->first();

        return $collaborator ? $collaborator->role : null;
    }

    public function docketEntries(): HasMany
    {
        return $this->hasMany(DocketEntry::class);
    }
}
