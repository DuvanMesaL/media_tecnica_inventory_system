<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ToolLoanItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'tool_loan_id',
        'tool_id',
        'quantity_requested',
        'quantity_delivered',
        'quantity_returned',
        'condition_delivered',
        'condition_returned',
        'notes'
    ];

    public function toolLoan(): BelongsTo
    {
        return $this->belongsTo(ToolLoan::class);
    }

    public function tool(): BelongsTo
    {
        return $this->belongsTo(Tool::class);
    }
}
