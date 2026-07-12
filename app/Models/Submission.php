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
        'is_po',
        'status',
        'submitted_at'
    ];

    public const DRAFT = 'draft';

    public const SUBMITTED = 'submitted';

    public const WAITING_SPV_APPROVAL =
    'waiting_spv_approval';

    public const WAITING_MANAGER_APPROVAL =
    'waiting_manager_approval';

    public const WAITING_DIRECTOR_APPROVAL =
    'waiting_director_approval';

    public const WAITING_FINANCE =
    'waiting_finance';

    public const PAID = 'paid';

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
    protected function casts(): array
    {
        return [
            'amount' => 'integer',
            'is_po' => 'boolean',
            'submitted_at' => 'datetime',
        ];
    }
}
