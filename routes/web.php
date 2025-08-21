<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeeController;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        
        // Kelola Karyawan Routes
        Route::get('/karyawan', [AdminController::class, 'employees'])->name('admin.employees');
        Route::post('/karyawan/store', [AdminController::class, 'storeEmployee'])->name('admin.employees.store');
        Route::put('/karyawan/{id}', [AdminController::class, 'updateEmployee'])->name('admin.employees.update');
        Route::delete('/karyawan/{id}', [AdminController::class, 'destroyEmployee'])->name('admin.employees.destroy');
        
        // QR Code Routes
        Route::get('/qrcode', [AdminController::class, 'qrcodeIndex'])->name('admin.qrcode.index');
        Route::post('/qrcode/generate', [AdminController::class, 'generateQrCode'])->name('admin.qrcode.generate');
        Route::post('/qrcode/refresh', [AdminController::class, 'refreshQrCode'])->name('admin.qrcode.refresh');
        
        // Attendance Report Route
        Route::get('/attendance/report', [AdminController::class, 'attendanceReport'])->name('admin.attendance.report');
    });
});

Route::middleware(['auth', 'role:employee'])->group(function () {
    Route::prefix('karyawan')->group(function () {
        Route::get('/dashboard', [EmployeeController::class, 'dashboard'])->name('employee.dashboard');
        Route::get('/scan', [EmployeeController::class, 'scanQR'])->name('employee.scan');
        Route::get('/profile', [EmployeeController::class, 'profile'])->name('employee.profile');
        Route::get('/attendance/history', [EmployeeController::class, 'attendanceHistory'])->name('employee.attendance.history');
        Route::post('/submit-attendance', [EmployeeController::class, 'submitAttendance'])->name('employee.submit-attendance');
        Route::post('/clock-out', [EmployeeController::class, 'clockOut'])->name('employee.clock-out');
        Route::post('/update-password', [EmployeeController::class, 'updatePassword'])->name('employee.update-password');
    });
});

// Remove this duplicate route group
// Route::middleware(['auth', 'admin'])->group(function () {
//     Route::get('/admin/qrcode', [QrCodeController::class, 'index'])->name('admin.qrcode');
//     Route::post('/admin/qrcode/refresh', [QrCodeController::class, 'refreshQrCode'])->name('admin.qrcode.refresh');
// });
