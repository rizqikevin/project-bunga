<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'employee_id',
        'name',
        'email',
        'position'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
