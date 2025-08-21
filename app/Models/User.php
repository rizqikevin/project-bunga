<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'employee_id',
        'position',
        'role'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Tambahkan method untuk mendukung login dengan employee_id
    public function username()
    {
        return 'employee_id';
    }
}
