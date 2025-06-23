<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'first_name',
        'middle_name',
        'last_name',
        'second_last_name',
        'email',
        'password',
        'identification_number',
        'phone_number',
        'technical_program_id',
        'is_active',
        'profile_completed',
        'password_changed',
        'profile_completed_at',
        'last_login_at',
        'invitation_token',
        'invitation_sent_at',
        'invitation_accepted_at',
        'invited_by'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'invitation_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
        'profile_completed' => 'boolean',
        'password_changed' => 'boolean',
        'profile_completed_at' => 'datetime',
        'last_login_at' => 'datetime',
        'invitation_sent_at' => 'datetime',
        'invitation_accepted_at' => 'datetime',
    ];

    // Relationships
    public function technicalProgram(): BelongsTo
    {
        return $this->belongsTo(TechnicalProgram::class);
    }

    public function toolLoans(): HasMany
    {
        return $this->hasMany(ToolLoan::class);
    }

    public function approvedLoans(): HasMany
    {
        return $this->hasMany(ToolLoan::class, 'approved_by');
    }

    public function deliveredLoans(): HasMany
    {
        return $this->hasMany(ToolLoan::class, 'delivered_by');
    }

    public function receivedLoans(): HasMany
    {
        return $this->hasMany(ToolLoan::class, 'received_by');
    }

    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function invitedUsers(): HasMany
    {
        return $this->hasMany(User::class, 'invited_by');
    }

    // Helper methods for roles
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isTeacher(): bool
    {
        return $this->hasRole('teacher');
    }

    public function isLogistics(): bool
    {
        return $this->hasRole('logistics');
    }

    public function canManageTools(): bool
    {
        return $this->hasPermissionTo('manage tools');
    }

    public function canApproveLoans(): bool
    {
        return $this->hasPermissionTo('approve loans');
    }

    public function canManageUsers(): bool
    {
        return $this->hasPermissionTo('manage users');
    }

    // Profile methods
    public function getFullNameAttribute(): string
    {
        $parts = array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
            $this->second_last_name
        ]);

        return implode(' ', $parts) ?: $this->name;
    }

    public function getInitialsAttribute(): string
    {
        $name = $this->full_name ?: $this->name;
        $words = explode(' ', $name);
        $initials = '';

        foreach ($words as $word) {
            if (!empty($word)) {
                $initials .= strtoupper($word[0]);
            }
        }

        return substr($initials, 0, 2);
    }

    public function needsProfileCompletion(): bool
    {
        return !$this->profile_completed || !$this->password_changed;
    }

    public function generateInvitationToken(): string
    {
        $token = Str::random(64);
        $this->update([
            'invitation_token' => $token,
            'invitation_sent_at' => now()
        ]);

        return $token;
    }

    public function acceptInvitation(): void
    {
        $this->update([
            'invitation_accepted_at' => now(),
            'invitation_token' => null
        ]);
    }

    public function markProfileCompleted(): void
    {
        $this->update([
            'profile_completed' => true,
            'profile_completed_at' => now()
        ]);
    }

    public function updateLastLogin(): void
    {
        $this->update(['last_login_at' => now()]);
    }
}
