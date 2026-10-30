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
        'is_active',
    ];

    protected $casts = [
        'validity_period' => 'date',
        'is_active' => 'boolean',
    ];

    protected $appends = [
        'file_url',
        'resolution_document_url',
    ];

    public function getFileUrlAttribute(): ?string
    {
        return $this->file_path ? asset('storage/'.$this->file_path) : null;
    }

    public function getResolutionDocumentUrlAttribute(): ?string
    {
        return $this->resolution_document_path ? asset('storage/'.$this->resolution_document_path) : null;
    }
}
