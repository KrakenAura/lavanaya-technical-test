<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    protected $fillable = [
        'user_id',
        'category_id',
        'submission_number',
        'title',
        'description',
        'amount',
        'status',
        'submitted_at'
    ];

    public const DRAFT = 'draft';

    public const SUBMITTED = 'submitted';

    public const APPROVED = 'approved';

    public const REJECTED = 'rejected';


    public function user()
    {
        return $this->belongsTo(User::class);
    }


    public function category()
    {
        return $this->belongsTo(
            ExpenseCategory::class,
            'category_id'
        );
    }


    public function approvals()
    {
        return $this->hasMany(Approval::class);
    }


    public function attachments()
    {
        return $this->hasMany(Attachment::class);
    }


    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}
