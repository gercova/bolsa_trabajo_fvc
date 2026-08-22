<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class Blog extends Model
{
    protected $table    = 'blogs';
    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'slug',
        'content',
        'details',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    /** Returns a plain-text excerpt of the content. */
    public function excerpt(int $limit = 150): string
    {
        return Str::limit(strip_tags($this->content), $limit);
    }

    /** Cover image (first attached image). */
    public function coverImage(): ?string
    {
        $img = $this->images()->first();
        return $img ? asset('storage/' . $img->path) : null;
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}

