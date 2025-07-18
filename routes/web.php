<?php

use App\Http\Controllers\AttendanceController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('attendance')->group(function () {
    Route::get('/create', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/{attendance}', [AttendanceController::class, 'show'])->name('attendance.show');
    Route::get('/{attendance}/scan', [AttendanceController::class, 'scan'])->name('attendance.scan');
    Route::post('/{attendance}/process', [AttendanceController::class, 'processAttendance'])->name('attendance.process');
    Route::get('/attendance/{attendance}/scan', [AttendanceController::class, 'scan'])->name('attendance.scan');
    Route::get('/attendance/{attendance}/scan-form', [AttendanceController::class, 'scanForm'])->name('attendance.scan-form');
    Route::get('/attendances/{attendance}/logs', [AttendanceController::class, 'logs'])->name('attendance.logs');
    Route::get('/attendances/{attendance}/export', [AttendanceController::class, 'export'])->name('attendance.export');
});

// Hapus route yang salah dan ganti dengan ini:
Route::prefix('attendance')->group(function () {
    Route::get('/', [AttendanceController::class, 'index'])->name('attendance.index');
});

Route::post('/attendances/{attendance}/success', [AttendanceController::class, 'success'])->name('attendance.success');