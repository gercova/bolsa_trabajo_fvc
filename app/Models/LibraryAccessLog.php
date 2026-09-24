<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LibraryAccessLog extends Model
{
    use HasFactory;

    protected $table    = 'library_access_logs';
    public $timestamps  = false;
    protected $fillable = [
        'user_id',
        'book_id',
        'access_type',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function book(): BelongsTo {
        return $this->belongsTo(Book::class, 'book_id');
    }

    public function scopeRecent($query) {
        return $query->orderByDesc('created_at');
    }
}
