<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Book extends Model
{
    use HasFactory;

    protected $table = 'books';

    protected $fillable = [
        'title',
        'slug',
        'author',
        'study_program_id',
        'category',
        'description',
        'publisher',
        'publication_year',
        'edition',
        'pages',
        'isbn',
        'language',
        'cover_image',
        'file_path',
        'file_size',
        'external_url',
        'rating',
        'views_count',
        'downloads_count',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'publication_year' => 'integer',
        'pages'            => 'integer',
        'file_size'        => 'integer',
        'rating'           => 'float',
        'views_count'      => 'integer',
        'downloads_count'  => 'integer',
        'is_active'        => 'boolean',
    ];

    protected $appends = [
        'cover_url',
        'file_url',
        'formatted_file_size',
        'is_external',
    ];

    /**
     * Boot logic for automatically generating slug.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function (Book $book) {
            if (empty($book->slug)) {
                $baseSlug = Str::slug($book->title);
                $slug = $baseSlug;
                $counter = 1;
                while (static::where('slug', $slug)->exists()) {
                    $slug = "{$baseSlug}-{$counter}";
                    $counter++;
                }
                $book->slug = $slug;
            }
        });
    }

    /**
     * Study program relation (can be null for General / all programs).
     */
    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class, 'study_program_id');
    }

    /**
     * User who created / uploaded the book.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Favorites relationship.
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(BookFavorite::class, 'book_id');
    }

    /**
     * Users who have favorited this book.
     */
    public function favoritedBy(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'book_favorites', 'book_id', 'user_id')->withTimestamps();
    }

    /**
     * Reader access logs.
     */
    public function accessLogs(): HasMany
    {
        return $this->hasMany(LibraryAccessLog::class, 'book_id');
    }

    /**
     * Accessor: Cover image URL.
     */
    public function getCoverUrlAttribute(): string
    {
        if ($this->cover_image) {
            if (str_starts_with($this->cover_image, 'http://') || str_starts_with($this->cover_image, 'https://')) {
                return $this->cover_image;
            }
            return '/storage/' . ltrim($this->cover_image, '/');
        }

        return '';
    }

    /**
     * Accessor: Direct or streaming PDF file URL.
     */
    public function getFileUrlAttribute(): string
    {
        if ($this->file_path) {
            return '/storage/' . ltrim($this->file_path, '/');
        }

        return $this->external_url ?? '';
    }

    /**
     * Accessor: Formatted file size string.
     */
    public function getFormattedFileSizeAttribute(): string
    {
        if ($this->external_url && !$this->file_size) {
            return 'Enlace Web';
        }

        if (!$this->file_size) {
            return 'Documento Digital';
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB'];
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 1) . ' ' . $units[$i];
    }

    /**
     * Accessor: Check if document is external resource.
     */
    public function getIsExternalAttribute(): bool
    {
        return !empty($this->external_url) && empty($this->file_path);
    }

    /**
     * Scope: Only active documents.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Filter by career / study program.
     */
    public function scopeCareer($query, $careerId)
    {
        if ($careerId && $careerId !== 'all') {
            return $query->where('study_program_id', $careerId);
        }

        return $query;
    }

    /**
     * Scope: Filter by category (Libro, Revista, etc.).
     */
    public function scopeCategory($query, $category)
    {
        if ($category && $category !== 'all') {
            return $query->where('category', $category);
        }

        return $query;
    }

    /**
     * Scope: Search term in title, author, or description.
     */
    public function scopeSearch($query, ?string $term)
    {
        if (empty($term)) {
            return $query;
        }

        $term = trim($term);
        return $query->where(function ($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('author', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%")
              ->orWhere('publisher', 'like', "%{$term}%")
              ->orWhere('isbn', 'like', "%{$term}%");
        });
    }
}
