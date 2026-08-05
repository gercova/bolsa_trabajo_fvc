<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    protected $table        = 'users';
    protected $primaryKey   = 'id';
    protected $fillable     = [
        'dni',
        'names',
        'email',
        'photo_profile',
        'cv_file',
        'role',
        'job_position',
        'password',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_active'         => 'boolean',
    ];

    public function areas(): HasMany {
        return $this->hasMany(Area::class, 'user_id', 'id');
    }

    /** All role-detail records for this user (can be on multiple programmes). */
    public function roleDetails(): HasMany {
        return $this->hasMany(UserRoleDetail::class, 'user_id', 'id');
    }

    /** The most recently active role-detail record (for display convenience). */
    public function primaryRoleDetail(): HasOne {
        return $this->hasOne(UserRoleDetail::class, 'user_id', 'id')
            ->where('is_active', true)
            ->latest();
    }

    public function studentCouncils(): HasMany {
        return $this->hasMany(StudentCouncil::class, 'user_id', 'id');
    }
}
