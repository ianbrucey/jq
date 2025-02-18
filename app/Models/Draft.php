<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Draft extends Model
{
    use HasFactory;

    protected $primaryKey = 'draft_id';
    protected $fillable = [
        'case_file_id',
        'draft_type',
        'description',
        'status',
        'structured_context',
        'generated_content'
    ];

    public function case()
    {
        return $this->belongsTo(LegalCase::class, 'case_file_id');
    }
}
