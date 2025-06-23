<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'location',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean'
    ];

    public function tools(): HasMany
    {
        return $this->hasMany(Tool::class);
    }

    public function toolLoans(): HasMany
    {
        return $this->hasMany(ToolLoan::class);
    }
}
