<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class EmployeeSeeder extends Seeder
{
    public function run()
    {
        // Create Admin User
        User::create([
            'employee_id' => 'A001',
            'name' => 'Admin',
            'position' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => bcrypt('admin123'),
            'role' => 'admin'
        ]);

        // Create Employee Users
        $employees = [
            [
                'employee_id' => 'K001',
                'name' => 'Budi Santoso',
                'position' => 'Staff Produksi',
                'email' => 'K001@company.com',
                'password' => bcrypt('password123'),
                'role' => 'employee'
            ],
            [
                'employee_id' => 'K002',
                'name' => 'Siti Rahayu',
                'position' => 'Staff Gudang',
                'email' => 'K002@company.com',
                'password' => bcrypt('password123'),
                'role' => 'employee'
            ],
            [
                'employee_id' => 'K003',
                'name' => 'Ahmad Wijaya',
                'position' => 'Staff QC',
                'email' => 'K003@company.com',
                'password' => bcrypt('password123'),
                'role' => 'employee'
            ]
        ];

        foreach ($employees as $employee) {
            User::create($employee);
        }
    }
}