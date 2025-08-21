<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\QRCode as QRCodeModel;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalEmployees = User::where('role', 'employee')->count();
        $todayAttendance = Attendance::whereDate('date', Carbon::today())->count();
        $lateToday = Attendance::whereDate('date', Carbon::today())
                              ->where('status', 'terlambat')
                              ->count();
        
        $recentAttendances = Attendance::with('user')
                                     ->orderBy('date', 'desc')
                                     ->take(10)
                                     ->get();
        
        return view('admin.dashboard', compact(
            'totalEmployees',
            'todayAttendance',
            'lateToday',
            'recentAttendances'
        ));
    }

    public function employees()
    {
        $employees = User::where('role', 'employee')->get();
        return view('admin.employees.index', compact('employees'));
    }

    public function storeEmployee(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|unique:users,employee_id',
            'name' => 'required',
            'position' => 'required'
        ]);

        User::create([
            'employee_id' => $request->employee_id,
            'name' => $request->name,
            'position' => $request->position,
            'email' => $request->employee_id . '@company.com', // Format email
            'password' => bcrypt('password123'), // Default password
            'role' => 'employee'
        ]);

        return redirect()->route('admin.employees')->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function updateEmployee(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required|unique:users,employee_id,'.$id,
            'name' => 'required',
            'position' => 'required'
        ]);

        $employee = User::findOrFail($id);
        $employee->update([
            'employee_id' => $request->employee_id,
            'name' => $request->name,
            'position' => $request->position
        ]);

        return redirect()->route('admin.employees')->with('success', 'Data karyawan berhasil diperbarui');
    }

    public function destroyEmployee($id)
    {
        $employee = User::findOrFail($id);
        $employee->delete();
        return redirect()->route('admin.employees')->with('success', 'Karyawan berhasil dihapus');
    }

    public function qrcodeIndex()
    {
        $interval = 60; // Set interval 60 detik
        $now = Carbon::now();
        
        // Buat QR baru jika belum ada
        $qrCode = QRCodeModel::latest()->first();
        if (!$qrCode) {
            $qrCode = $this->generateNewQrCode();
        }
        
        // Hitung sisa waktu
        $elapsed = $now->diffInSeconds($qrCode->created_at);
        $timeLeft = $interval - ($elapsed % $interval);
        
        // Jika waktu habis, buat QR baru
        if ($timeLeft <= 0 || $elapsed >= $interval) {
            QRCodeModel::truncate();
            $qrCode = $this->generateNewQrCode();
            $timeLeft = $interval;
        }
        
        return view('admin.qrcode.index', compact('qrCode', 'timeLeft'));
    }

    private function generateNewQrCode()
    {
        return QRCodeModel::create([
            'name' => 'Absensi ' . now()->format('d M Y H:i:s'),
            'qr_id' => 'JSP-' . now()->format('YmdHis') . '-' . substr(md5(now()->timestamp), 0, 6),
            'location' => 'Office',
            'created_at' => now()
        ]);
    }

    public function attendanceReport(Request $request)
    {
        $startDate = $request->input('start_date', '2023-08-01');
        $endDate = $request->input('end_date', now()->format('Y-m-d'));
        $selectedEmployee = $request->input('employee', 'all');

        $query = Attendance::with('user')
            ->whereBetween('date', [$startDate, $endDate])
            ->whereHas('user', function($q) {
                $q->where('role', 'employee');
            })
            ->whereRaw('DAYOFWEEK(date) NOT IN (1, 7)'); // Exclude Sabtu (7) dan Minggu (1)

        if ($selectedEmployee !== 'all') {
            $query->whereHas('user', function($q) use ($selectedEmployee) {
                $q->where('id', $selectedEmployee);
            });
        }

        $attendances = $query->orderBy('date', 'desc')->get();

        // Set status berdasarkan jam masuk
        foreach ($attendances as $attendance) {
            $timeIn = Carbon::parse($attendance->time_in);
            if ($timeIn->format('H:i') > '08:00') {
                $attendance->status = 'Terlambat';
            } else if ($timeIn->format('H:i') >= '07:30') {
                $attendance->status = 'Tepat Waktu';
            }
        }

        $employees = User::where('role', 'employee')->get();
        
        return view('admin.attendance.report', compact('attendances', 'employees', 'startDate', 'endDate', 'selectedEmployee'));
    }

    public function refreshQrCode()
    {
        $timestamp = floor(time() / 30) * 30;
        $newCode = 'JSP-' . date('Ymd-His', $timestamp) . '-' . substr(md5($timestamp), 0, 6);
        
        $qrCode = QRCodeModel::create([
            'name' => 'Absensi ' . date('d M Y H:i:s'),
            'qr_id' => $newCode,
            'location' => 'Office',
            'qr_path' => 'qrcodes/' . $newCode . '.png'
        ]);
    
        return response()->json([
            'qrCode' => QrCode::size(300)->generate($qrCode->qr_id),
            'timeLeft' => 30
        ]);
    }
}