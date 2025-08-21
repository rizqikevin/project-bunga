<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Cek apakah admin sudah ada
        if (!User::where('email', 'admin@gmail.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'employee_id' => 'ADM001', // Tambahkan employee_id
                'position' => 'Administrator' // Tambahkan position
            ]);
        }
    }
}