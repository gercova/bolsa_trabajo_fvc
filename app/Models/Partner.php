<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model {
    
    protected $table        = 'partners';
    protected $primaryKey   = 'id';
    protected $keyType      = 'int';
    protected $fillable     = [
        'company',
        'image_url',
        'is_active',
    ];

    protected $casts = [
        'is_active'     => 'boolean',
        'created_at'    => 'datetime',
        'updated_at'    => 'datetime',
    ];
}
