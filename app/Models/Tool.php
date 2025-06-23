<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tool extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'category',
        'condition',
        'total_quantity',
        'available_quantity',
        'warehouse_id',
        'unit_price'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2'
    ];

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function toolLoanItems(): HasMany
    {
        return $this->hasMany(ToolLoanItem::class);
    }

    public function isAvailable(int $quantity = 1): bool
    {
        return $this->available_quantity >= $quantity;
    }
}
