<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $fillable = [
        'category_id',
        'period',
        'amount',
        'used_amount'
    ];


    public function category()
    {
        return $this->belongsTo(
            ExpenseCategory::class
        );
    }
}
