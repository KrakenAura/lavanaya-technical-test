<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExpenseCategory extends Model
{
    protected $fillable = [
        'name',
        'description'
    ];


    public function submissions()
    {
        return $this->hasMany(Submission::class, 'category_id');
    }


    public function budgets()
    {
        return $this->hasMany(Budget::class);
    }
}
