<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\User;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run()
    {
        $employees = User::where('role', 'employee')->get();
        $startDate = Carbon::parse('2023-08-01');
        $endDate = Carbon::now();

        while ($startDate <= $endDate) {
            // Skip Sabtu dan Minggu
            if ($startDate->dayOfWeek !== Carbon::SATURDAY && $startDate->dayOfWeek !== Carbon::SUNDAY) {
                foreach ($employees as $employee) {
                    // Random time between 07:30 and 08:15
                    $timeIn = Carbon::parse($startDate->format('Y-m-d') . ' ' . sprintf('%02d:%02d:00', 
                        rand(7, 8), 
                        rand(30, 59)
                    ));

                    // Set time out to 16:00
                    $timeOut = Carbon::parse($startDate->format('Y-m-d') . ' 16:00:00');

                    Attendance::create([
                        'user_id' => $employee->id,
                        'date' => $startDate->format('Y-m-d'),
                        'time_in' => $timeIn->format('H:i:s'),
                        'time_out' => $timeOut->format('H:i:s'),
                        'status' => $timeIn->format('H:i') > '08:00' ? 'Terlambat' : 'Tepat Waktu'
                    ]);
                }
            }
            $startDate->addDay();
        }
    }
}