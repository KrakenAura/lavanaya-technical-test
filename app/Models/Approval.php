<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $fillable = [
        'submission_id',
        'approver_id',
        'level',
        'status',
        'notes',
        'acted_at'
    ];

    public const WAITING = 'waiting';

    public const APPROVED = 'approved';

    public const REJECTED = 'rejected';


    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }


    public function approver()
    {
        return $this->belongsTo(
            User::class,
            'approver_id'
        );
    }

    protected function casts(): array
    {
        return [
            'acted_at' => 'datetime',
        ];
    }
}
