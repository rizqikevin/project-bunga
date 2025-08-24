<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\QrCodeController;

Route::get('/', function () {
    return redirect('/login');
});

/**
 * Auth (guest)
 */
Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

/**
 * Logout
 */
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/**
 * Admin area
 */
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');

        // Kelola Karyawan
        Route::get('/karyawan', [AdminController::class, 'employees'])->name('admin.employees');
        Route::post('/karyawan/store', [AdminController::class, 'storeEmployee'])->name('admin.employees.store');
        Route::put('/karyawan/{id}', [AdminController::class, 'updateEmployee'])->name('admin.employees.update');
        Route::delete('/karyawan/{id}', [AdminController::class, 'destroyEmployee'])->name('admin.employees.destroy');

        // QR Code (semua via QrCodeController agar 1 sumber kebenaran)
        Route::get('/qrcode', [QrCodeController::class, 'index'])->name('admin.qrcode.index');
        Route::get('/qrcode/refresh', [QrCodeController::class, 'refreshQrCode'])->name('admin.qrcode.refresh');

        // Laporan Absensi
        Route::get('/attendance/report', [AdminController::class, 'attendanceReport'])->name('admin.attendance.report');
    });
});

/**
 * Employee area
 */
Route::middleware(['auth', 'role:employee'])->group(function () {
    Route::prefix('karyawan')->group(function () {
        Route::get('/dashboard', [EmployeeController::class, 'dashboard'])->name('employee.dashboard');
        Route::get('/scan', [EmployeeController::class, 'scanQR'])->name('employee.scan');
        Route::get('/profile', [EmployeeController::class, 'profile'])->name('employee.profile');
        Route::get('/attendance/history', [EmployeeController::class, 'attendanceHistory'])->name('employee.attendance.history');

        Route::post('/submit-attendance', [EmployeeController::class, 'submitAttendance'])->name('employee.submit-attendance');
        Route::post('/clock-out', [EmployeeController::class, 'clockOut'])->name('employee.clock-out');
        Route::post('/update-password', [EmployeeController::class, 'updatePassword'])->name('employee.update-password');

        // Input kode manual (dipindah ke dalam prefix "karyawan")
        Route::get('/input-kode', [EmployeeController::class, 'inputCode'])->name('employee.input-code');
    });
});
