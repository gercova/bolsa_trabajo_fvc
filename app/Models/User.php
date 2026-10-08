<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id';

    protected $fillable = [
        'document_type_id',
        'dni',
        'names',
        'birthdate',
        'mother_tongue',
        'phone',
        'address',
        'sex',
        'ubigeo',
        'email',
        'photo_profile',
        'cv_file',
        'role',
        'job_position',
        'charge_id',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function documentType(): BelongsTo
    {
        return $this->belongsTo(DocumentType::class, 'document_type_id', 'id');
    }

    public function areas(): HasMany
    {
        return $this->hasMany(Area::class, 'user_id', 'id');
    }

    /** All role-detail records for this user (can be on multiple programmes). */
    public function roleDetails(): HasMany
    {
        return $this->hasMany(UserRoleDetail::class, 'user_id', 'id');
    }

    /** The most recently active role-detail record (for display convenience). */
    public function primaryRoleDetail(): HasOne
    {
        return $this->hasOne(UserRoleDetail::class, 'user_id', 'id')
            ->where('is_active', true)
            ->latest();
    }

    public function studentCouncils(): HasMany
    {
        return $this->hasMany(StudentCouncil::class, 'user_id', 'id');
    }

    public function favoriteBooks(): BelongsToMany
    {
        return $this->belongsToMany(Book::class, 'book_favorites', 'user_id', 'book_id')->withTimestamps();
    }

    public function libraryAccessLogs(): HasMany
    {
        return $this->hasMany(LibraryAccessLog::class, 'user_id', 'id');
    }

    /** All certificates issued to this user. */
    public function certificates(): HasMany
    {
        return $this->hasMany(Certificate::class, 'user_id', 'id');
    }
}
