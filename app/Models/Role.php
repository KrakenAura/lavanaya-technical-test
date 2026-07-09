<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    public const STAFF = 'Staff';
    public const SUPERVISOR = 'Supervisor';
    public const MANAGER = 'Manager';
    public const DIRECTOR = 'Director';
    public const FINANCE = 'Finance';


    protected $fillable = [
        'name',
    ];


    public function users()
    {
        return $this->hasMany(User::class);
    }
}
