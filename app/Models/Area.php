<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Area extends Model
{
    protected $table        = 'area';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'name',
        'program_id',
        'description',
        'details',
        'user_id',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function program(): BelongsTo {
        return $this->belongsTo(StudyProgram::class, 'program_id', 'id');
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
}
