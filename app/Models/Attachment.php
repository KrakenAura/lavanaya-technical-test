<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'submission_id',
        'file_name',
        'file_path'
    ];


    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }
}
