<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Classroom extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'technical_program_id',
        'capacity',
        'location',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function technicalProgram(): BelongsTo
    {
        return $this->belongsTo(TechnicalProgram::class);
    }

    public function toolLoans(): HasMany
    {
        return $this->hasMany(ToolLoan::class);
    }
}
