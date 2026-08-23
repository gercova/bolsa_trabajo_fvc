<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManagementDocument extends Model
{
    protected $fillable = [
        'title',
        'description',
        'details',
        'file_path',
        'resolution_document_path',
        'validity_period',
        'is_active'
    ];

    protected $casts = [
        'validity_period' => 'date',
        'is_active'       => 'boolean'
    ];
}
