<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Budget extends Model
{
    protected $fillable = [
        'category_id',
        'period',
        'amount',
        'used_amount',
    ];

    protected $casts = [
        'amount' => 'integer',
        'used_amount' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            ExpenseCategory::class,
            'category_id'
        );
    }
}
