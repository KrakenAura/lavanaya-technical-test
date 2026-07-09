<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'submission_id',
        'finance_user_id',
        'paid_at',
        'payment_method',
        'reference_number',
        'status'
    ];


    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }


    public function finance()
    {
        return $this->belongsTo(
            User::class,
            'finance_user_id'
        );
    }
}
