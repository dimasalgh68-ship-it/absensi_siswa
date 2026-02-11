<?php

use App\Helpers;
use app\Livewire\Admin\Employee\EmployeeTable;
use App\Livewire\Admin\AttendanceComponent;
use App\Http\Controllers\Admin\MasterDataController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ImportExportController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserAttendanceController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TaskCommentController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\FaceRegistrationController;
use App\Http\Controllers\FaceAttendanceController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Redirect to appropriate dashboard based on auth status
    if (Auth::check()) {
        $student = Auth::user();
        
        // Admin and Teacher redirect to admin dashboard
        if ($student->isAdmin || $student->isTeacher) {
            return redirect('/admin/dashboard');
        }
        
        // Student and others redirect to home
        return redirect('/home');
    }
    
    // Not logged in, show splash screen (will auto redirect to login)
    return view('splash');
})->name('splash');

Route::view('/about', 'about')->name('about');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->group(function () {
    // Route::get('/', fn () => Auth::user()->isAdmin ? redirect('/admin') : redirect('/home'));

    Route::get('/logout', function () {
        return view('auth.logout');
    })->name('logout.confirm');

  // USER AREA
Route::middleware('user')->group(function () {
    Route::get('/home', HomeController::class)->name('home');

    Route::get('/apply-leave', [UserAttendanceController::class, 'applyLeave'])
        ->name('apply-leave');
    Route::post('/apply-leave', [UserAttendanceController::class, 'storeLeaveRequest'])
        ->name('store-leave-request');

    Route::get('/attendance-history', [UserAttendanceController::class, 'history'])
        ->name('attendance-history');

    // Academic Calendar
    Route::get('/academic-calendar', [App\Http\Controllers\AcademicCalendarController::class, 'index'])
        ->name('academic-calendar');
    Route::post('/academic-calendar/sync-holidays', [App\Http\Controllers\AcademicCalendarController::class, 'syncHolidays'])
        ->name('academic-calendar.sync-holidays');

    // Bills and Tasks routes removed
    
    Route::view('/scan', 'scan')->name('scan');

    // Face Registration
    Route::get('/face-registration', [FaceRegistrationController::class, 'index'])
        ->name('face-registration.index');
    Route::post('/face-registration', [FaceRegistrationController::class, 'store'])
        ->name('face-registration.store');
    // Note: Delete route removed - only admin can delete face registrations
    
    // API endpoint to get face descriptor for browser verification
    Route::get('/api/face-registration/descriptor', [FaceRegistrationController::class, 'getDescriptor'])
        ->name('api.face-registration.descriptor');

    // Face Attendance
    Route::get('/face-attendance', [FaceAttendanceController::class, 'index'])
        ->name('face-attendance.index');
    Route::post('/face-attendance', [FaceAttendanceController::class, 'store'])
        ->name('face-attendance.store');
}); // ← tutup user middleware group

// ADMIN AREA
Route::prefix('admin')->middleware('admin')->group(function () {
    Route::get('/', fn () => redirect('/admin/dashboard'));
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');

    // Face Registration Management (Admin)
    Route::get('/face-registrations', function () {
        return view('admin.face-registrations');
    })->name('admin.face-registrations');

    // Office Locations Management (Admin)
    Route::get('/office-locations', function () {
        return view('admin.office-locations');
    })->name('admin.office-locations');

    // Employee
    Route::resource('/employees', EmployeeController::class)
        ->only(['index'])
        ->names(['index' => 'admin.employees']);

    // Master Data
    Route::get('/masterdata/division', [MasterDataController::class, 'division'])->name('admin.masters.division');
    Route::get('/masterdata/job-title', [MasterDataController::class, 'jobTitle'])->name('admin.masters.job-title');
    Route::get('/masterdata/education', [MasterDataController::class, 'education'])->name('admin.masters.education');
    Route::get('/masterdata/shift', [MasterDataController::class, 'shift'])->name('admin.masters.shift');
    Route::get('/masterdata/admin', [MasterDataController::class, 'admin'])->name('admin.masters.admin');

    // Attendance
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('admin.attendances');
    Route::get('/attendances/report', [AttendanceController::class, 'report'])->name('admin.attendances.report');

    // Import/Export
    Route::get('/import-export/users', [ImportExportController::class, 'users'])->name('admin.import-export.users');
    Route::get('/import-export/attendances', [ImportExportController::class, 'attendances'])->name('admin.import-export.attendances');
    Route::get('/import-export/users/template', [ImportExportController::class, 'userTemplate'])->name('admin.import-export.users.template');
    Route::get('/import-export/attendances/template', [ImportExportController::class, 'attendanceTemplate'])->name('admin.import-export.attendances.template');
    Route::post('/users/import', [ImportExportController::class, 'importUsers'])->name('admin.users.import');
    Route::post('/attendances/import', [ImportExportController::class, 'importAttendances'])->name('admin.attendances.import');
    Route::get('/users/export', [ImportExportController::class, 'exportUsers'])->name('admin.users.export');
    Route::get('/attendances/export', [ImportExportController::class, 'exportAttendances'])->name('admin.attendances.export');

    // Bills and Tasks routes removed

    // Academic Events
    Route::get('/academic-events', function () {
        return view('admin.academic-events');
    })->name('admin.academic-events');

    // Academic Calendar (Admin)
    Route::get('/academic-calendar', [App\Http\Controllers\AcademicCalendarController::class, 'index'])
        ->name('admin.academic-calendar');
    Route::post('/academic-calendar/sync-holidays', [App\Http\Controllers\AcademicCalendarController::class, 'syncHolidays'])
        ->name('admin.academic-calendar.sync-holidays');

    // Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('admin.settings');
    Route::post('/settings/logo', [\App\Http\Controllers\Admin\SettingsController::class, 'updateLogo'])->name('admin.settings.update-logo');
    Route::post('/settings/app-name', [\App\Http\Controllers\Admin\SettingsController::class, 'updateAppName'])->name('admin.settings.update-app-name');
    Route::post('/settings/attendance', [\App\Http\Controllers\Admin\SettingsController::class, 'updateAttendanceSettings'])->name('admin.settings.update-attendance');
    Route::post('/settings/face-recognition', [\App\Http\Controllers\Admin\SettingsController::class, 'updateFaceRecognitionSettings'])->name('admin.settings.update-face-recognition');
    Route::delete('/settings/logo', [\App\Http\Controllers\Admin\SettingsController::class, 'resetLogo'])->name('admin.settings.reset-logo');
}); // ← tutup admin middleware group
}); // ← tutup auth middleware group