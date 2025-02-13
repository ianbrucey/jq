<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LegalCase extends Model
{
    use HasFactory;

    protected $table = 'cases';

    protected $fillable = ['title', 'description', 'desired_outcome','user_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}