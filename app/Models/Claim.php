<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Claim extends Model
{
    protected $table = 'claims';
    protected $primaryKey = 'id';
    protected $fillable = [
        'dni',
        'names',
        'email',
        'subject',
        'message',
        'file_path',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
