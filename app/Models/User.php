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
        'personal_email',
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

    protected $appends = [
        'photo_profile_url',
        'cv_file_url',
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

    /**
     * Check if user has the Student or Applicant role / profile.
     */
    public function isStudent(): bool
    {
        $role = strtolower((string) $this->role);
        if (in_array($role, ['estudiante', 'student', 'postulante', 'applicant', 'lector', 'solicitante'])) {
            return true;
        }

        if (method_exists($this, 'hasAnyRole') && $this->hasAnyRole(['Estudiante', 'Student', 'Postulante', 'Applicant', 'estudiante', 'student'])) {
            return true;
        }

        return ! $this->can('ver-dashboard') && ! $this->can('gestionar-usuarios') && (
            $this->can('actualizar-informacion-personal') ||
            $this->can('acceder-biblioteca-virtual') ||
            $this->can('ver-certificados')
        );
    }

    /**
     * Alias for isStudent.
     */
    public function isStudentOrApplicant(): bool
    {
        return $this->isStudent();
    }

    /**
     * Get the full public URL for the user's profile photo.
     */
    public function getPhotoProfileUrlAttribute(): ?string
    {
        if (! $this->photo_profile) {
            return null;
        }

        if (str_starts_with($this->photo_profile, 'http://') || str_starts_with($this->photo_profile, 'https://')) {
            return $this->photo_profile;
        }

        return asset('storage/'.$this->photo_profile);
    }

    /**
     * Get the full public URL for the user's CV file.
     */
    public function getCvFileUrlAttribute(): ?string
    {
        if (! $this->cv_file) {
            return null;
        }

        if (str_starts_with($this->cv_file, 'http://') || str_starts_with($this->cv_file, 'https://')) {
            return $this->cv_file;
        }

        return asset('storage/'.$this->cv_file);
    }
}
