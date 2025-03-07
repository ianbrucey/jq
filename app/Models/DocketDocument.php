<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class DocketDocument extends Pivot
{
    protected $table = 'docket_documents';

    protected $fillable = [
        'docket_entry_id',
        'document_id',
        'is_primary',
    ];

    protected $casts = [
        'is_primary' => 'boolean',
    ];
}