<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserRoleDetail extends Model
{
    protected $table      = 'user_roles_details';
    protected $primaryKey = 'id';

    protected $fillable = [
        'user_id',
        'program_id',
        'is_coordinator',
        'specialty',
        'is_active',
    ];

    protected $casts = [
        'is_coordinator' => 'boolean',
        'is_active'      => 'boolean',
        'created_at'     => 'datetime',
        'updated_at'     => 'datetime',
    ];

    // ──────────────────────────────────────────────
    // Relationships
    // ──────────────────────────────────────────────

    /** The user (teacher / staff member) this detail belongs to. */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /** The study programme this teacher is assigned to (may be null). */
    public function program(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class, 'program_id', 'id');
    }

    // ──────────────────────────────────────────────
    // Scopes
    // ──────────────────────────────────────────────

    /** Filter only active records. */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /** Filter only coordinators. */
    public function scopeCoordinators($query)
    {
        return $query->where('is_coordinator', true);
    }
}
