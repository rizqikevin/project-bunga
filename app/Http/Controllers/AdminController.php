<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;

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
            'name'        => 'required',
            'position'    => 'required'
        ]);

        User::create([
            'employee_id' => $request->employee_id,
            'name'        => $request->name,
            'position'    => $request->position,
            'email'       => $request->employee_id . '@company.com',
            'password'    => bcrypt('password123'),
            'role'        => 'employee'
        ]);

        return redirect()->route('admin.employees')->with('success', 'Karyawan berhasil ditambahkan');
    }

    public function updateEmployee(Request $request, $id)
    {
        $request->validate([
            'employee_id' => 'required|unique:users,employee_id,'.$id,
            'name'        => 'required',
            'position'    => 'required'
        ]);

        $employee = User::findOrFail($id);
        $employee->update([
            'employee_id' => $request->employee_id,
            'name'        => $request->name,
            'position'    => $request->position
        ]);

        return redirect()->route('admin.employees')->with('success', 'Data karyawan berhasil diperbarui');
    }

    public function destroyEmployee($id)
    {
        $employee = User::findOrFail($id);
        $employee->delete();
        return redirect()->route('admin.employees')->with('success', 'Karyawan berhasil dihapus');
    }

    public function attendanceReport()
    {
        $attendances = Attendance::with('user')
            ->orderBy('date', 'desc')
            ->get();

        $employees = User::where('role', 'employee')->get();

        return view('admin.attendance.report', compact('attendances', 'employees'));
    }
}
