<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Attendance;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class EmployeeController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $currentMonth = Carbon::now()->month;

        $monthlyAttendance = Attendance::where('user_id', $user->id)
            ->whereMonth('date', $currentMonth)
            ->count();

        $lateCount = Attendance::where('user_id', $user->id)
            ->whereMonth('date', $currentMonth)
            ->where('status', 'terlambat')
            ->count();

        $leaveCount = 1;     // TODO: implementasi sesuai kebutuhan
        $overtimeCount = 4;  // TODO: implementasi sesuai kebutuhan

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

    /**
     * Endpoint submit via scan atau input manual.
     * Mengharapkan { qr_id: "JSP-YYYYMMDDHHMMSS-xxxxxx" }
     */
    public function submitAttendance(Request $request)
    {
        $request->validate([
            'qr_id' => 'required|string'
        ]);

        $user = Auth::user();
        $now  = Carbon::now('Asia/Jakarta');

        // 1) Validasi pola JSP-YYYYMMDDHHMMSS-xxxxxx
        if (!preg_match('/^JSP-(\d{14})-([a-f0-9]{6})$/i', $request->qr_id, $m)) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak valid (format tidak sesuai)'
            ], 400);
        }

        // 2) Cek masa berlaku ≤ 60 detik
        try {
            $qrTime = Carbon::createFromFormat('YmdHis', $m[1], 'Asia/Jakarta');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Timestamp QR tidak valid'
            ], 400);
        }

        $age = $qrTime->diffInSeconds($now);
        if ($age > 60) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code sudah kedaluwarsa'
            ], 400);
        }

        // Aturan kehadiran
        $cutoffIn = $now->copy()->setTime(8, 0, 0);   // >= 08:00 -> terlambat
        $allowOut = $now->copy()->setTime(16, 0, 0);  // >= 16:00 -> boleh pulang

        // 3) Cek absensi hari ini
        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $now->toDateString())
            ->first();

        if (!$attendance) {
            // CLOCK IN
            $isLate = $now->greaterThanOrEqualTo($cutoffIn);
            $status = $isLate ? 'terlambat' : 'hadir';

            Attendance::create([
                'user_id'  => $user->id,
                'date'     => $now->toDateString(),
                'clock_in' => $now->toTimeString(),
                'status'   => $status,
            ]);

            return response()->json([
                'success' => true,
                'message' => $isLate
                    ? 'Absen masuk berhasil (hadir terlambat)'
                    : 'Absen masuk berhasil (hadir tepat waktu)'
            ]);
        }

        // CLOCK OUT
        if ($attendance->clock_out) {
            return response()->json([
                'success' => false,
                'message' => 'Hari ini sudah absen masuk & pulang'
            ], 400);
        }

        if ($now->lt($allowOut)) {
            return response()->json([
                'success' => false,
                'message' => 'Clock out hanya bisa mulai pukul 16:00'
            ], 400);
        }

        $attendance->update([
            'clock_out' => $now->toTimeString(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Absen pulang berhasil'
        ]);
    }

    public function clockOut()
    {
        $user = Auth::user();
        $now  = Carbon::now('Asia/Jakarta');

        $attendance = Attendance::where('user_id', $user->id)
            ->whereDate('date', $now->toDateString())
            ->first();

        if (!$attendance || !$attendance->clock_in) {
            return back()->with('error', 'Anda belum melakukan clock in hari ini');
        }

        if ($attendance->clock_out) {
            return back()->with('error', 'Anda sudah melakukan clock out');
        }

        $allowOut = $now->copy()->setTime(16, 0, 0);
        if ($now->lt($allowOut)) {
            return back()->with('error', 'Clock out hanya bisa mulai pukul 16:00');
        }

        $attendance->update([
            'clock_out' => $now->toTimeString()
        ]);

        return back()->with('success', 'Clock out berhasil');
    }

    public function scanQR()
    {
        $todayAttendance = Attendance::where('user_id', Auth::id())
            ->whereDate('date', Carbon::today('Asia/Jakarta'))
            ->first();

        return view('employee.scan', compact('todayAttendance'));
    }

    public function inputCode()
    {
        $prefill = request('code', '');
        return view('employee.input-code', compact('prefill'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password'     => 'required|min:6|confirmed',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->with('error', 'Password lama tidak sesuai');
        }

        DB::table('users')
            ->where('id', $user->id)
            ->update(['password' => Hash::make($request->new_password)]);

        return back()->with('success', 'Password berhasil diubah');
    }

}
