<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserInvitation extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'token',
        'role',
        'technical_program_id',
        'invited_by',
        'expires_at',
        'accepted_at',
        'is_used',
        'metadata',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'accepted_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    // Accessor y Mutator para manejar metadata como JSON
    public function getMetadataAttribute($value)
    {
        return $value ? json_decode($value, true) : null;
    }

    public function setMetadataAttribute($value)
    {
        $this->attributes['metadata'] = $value ? json_encode($value) : null;
    }

    public function invitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invited_by');
    }

    public function technicalProgram(): BelongsTo
    {
        return $this->belongsTo(TechnicalProgram::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return !$this->is_used && !$this->isExpired();
    }

    public function markAsUsed(): void
    {
        $this->update([
            'is_used' => true,
            'accepted_at' => now(),
        ]);
    }

    public static function generateToken(): string
    {
        return Str::random(64);
    }

    public static function createInvitation(string $email, string $role, int $invitedBy, ?int $technicalProgramId = null, array $metadata = []): self
    {
        return self::create([
            'email' => $email,
            'token' => self::generateToken(),
            'role' => $role,
            'technical_program_id' => $technicalProgramId,
            'invited_by' => $invitedBy,
            'expires_at' => Carbon::now()->addHours(24),
            'metadata' => $metadata,
        ]);
    }

    // Scopes
    public function scopeValid($query)
    {
        return $query->where('is_used', false)
                    ->where('expires_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    public function scopeUsed($query)
    {
        return $query->where('is_used', true);
    }

    public function scopePending($query)
    {
        return $query->where('is_used', false)
                    ->where('expires_at', '>', now());
    }
}
