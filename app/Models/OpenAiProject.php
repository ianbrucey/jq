<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OpenAiProject extends Model
{
    protected $table = 'openai_projects';
    protected $fillable = [
        'name',
        'api_key',
        'organization_id',
        'storage_used',
        'is_active'
    ];

    protected $casts = [
        'storage_used' => 'integer',
        'is_active' => 'boolean',
    ];

    public function caseFiles(): HasMany
    {
        return $this->hasMany(CaseFile::class);
    }
}
