<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $currentMonth = Carbon::now()->month;
        
        // Hitung statistik
        $monthlyAttendance = Attendance::where('user_id', $user->id)
            ->whereMonth('date', $currentMonth)
            ->count();
            
        $lateCount = Attendance::where('user_id', $user->id)
            ->whereMonth('date', $currentMonth)
            ->where('status', 'terlambat')
            ->count();
            
        $leaveCount = 1; // Implementasi sesuai kebutuhan
        $overtimeCount = 4; // Implementasi sesuai kebutuhan
        
        $recentActivities = Attendance::where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();
        
        return view('employee.dashboard', compact(
            'monthlyAttendance',
            'lateCount',
            'leaveCount',
            'overtimeCount',
            'recentActivities'
        ));
    }

    public function profile()
    {
        return view('employee.profile');
    }

    public function attendanceHistory()
    {
        $attendances = Attendance::where('user_id', Auth::id())
                               ->orderBy('date', 'desc')
                               ->get();
                               
        return view('employee.attendance.history', compact('attendances'));
    }

    public function submitAttendance(Request $request)
    {
        $user = Auth::user();
        $now = Carbon::now();

        // Cek apakah sudah absen hari ini
        $existingAttendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $now->toDateString())
            ->first();

        if ($existingAttendance) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah melakukan absensi hari ini'
            ]);
        }

        // Tentukan status keterlambatan (contoh: telat jika > 08:00)
        $status = $now->format('H:i') > '08:00' ? 'terlambat' : 'tepat_waktu';

        Attendance::create([
            'user_id' => $user->id,
            'date' => $now->toDateString(),
            'clock_in' => $now,
            'status' => $status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absensi berhasil tercatat'
        ]);
    }

    public function clockOut()
    {
        $user = Auth::user();
        $now = Carbon::now();

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $now->toDateString())
            ->first();

        if (!$attendance) {
            return back()->with('error', 'Data absensi tidak ditemukan');
        }

        if ($attendance->clock_out) {
            return back()->with('error', 'Anda sudah melakukan clock out');
        }

        $attendance->update([
            'clock_out' => $now
        ]);

        return back()->with('success', 'Clock out berhasil');
    }

    public function scanQR()
    {
        $todayAttendance = Attendance::where('user_id', Auth::id())
                                ->whereDate('date', Carbon::today())
                                ->first();
        
        return view('employee.scan', compact('todayAttendance'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai');
        }

        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return back()->with('success', 'Password berhasil diubah');
    }
}
