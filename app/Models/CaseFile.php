<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CaseFile extends Model
{
    use HasFactory;

    protected $table = 'case_files';

    protected $fillable = ['title', 'description', 'desired_outcome','user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
