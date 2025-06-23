<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ToolLoan extends Model
{
    use HasFactory;

    protected $fillable = [
        'loan_number',
        'user_id',
        'technical_program_id',
        'classroom_id',
        'warehouse_id',
        'status',
        'loan_date',
        'expected_return_date',
        'actual_return_date',
        'notes',
        'approved_by',
        'delivered_by',
        'received_by'
    ];

    protected $casts = [
        'loan_date' => 'date',
        'expected_return_date' => 'date',
        'actual_return_date' => 'date'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function technicalProgram(): BelongsTo
    {
        return $this->belongsTo(TechnicalProgram::class);
    }

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(Classroom::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function deliveredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }

    public function receivedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function toolLoanItems(): HasMany
    {
        return $this->hasMany(ToolLoanItem::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($toolLoan) {
            $toolLoan->loan_number = 'LOAN-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
        });
    }
}
