<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        $employees = [
            [
                'name' => 'John Doe',
                'employee_id' => 'EMP001',
                'email' => 'emp001@company.com',
                'password' => Hash::make('password123'),
                'position' => 'Staff',
                'role' => 'employee'
            ],
            // Tambahkan data karyawan lainnya sesuai kebutuhan
        ];

        foreach ($employees as $employee) {
            User::create($employee);
        }
    }
}