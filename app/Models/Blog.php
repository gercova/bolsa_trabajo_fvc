<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Blog extends Model
{
    protected $table = 'blog';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'status',
        'published_at',
    ];

    public function images(): MorphMany {
        return $this->morphMany(Image::class, 'imageable');
    }
}
