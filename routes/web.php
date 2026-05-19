<?php

use App\Helpers;
use app\Livewire\Admin\Employee\EmployeeTable;
use App\Livewire\Admin\AttendanceComponent;
use App\Http\Controllers\Admin\MasterDataController;
use App\Http\Controllers\Admin\AttendanceController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\ImportExportController;
use App\Http\Controllers\Admin\TeacherPortalController;
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
        
        // Admin redirect to admin dashboard
        if ($student->isAdmin) {
            return redirect('/admin/dashboard');
        }
        
        // Teacher redirect to teacher dashboard
        if ($student->isTeacher) {
            return redirect('/teacher/dashboard');
        }
        
        // Student and others redirect to home
        return redirect('/home');
    }
    
    // Not logged in, show splash screen (will auto redirect to login)
    return view('splash');
})->name('splash');

Route::view('/about', 'about')->name('about');

// CSRF Token Refresh Route (untuk mencegah halaman kadaluarsa)
Route::get('/refresh-csrf', function () {
    return response()->json([
        'token' => csrf_token()
    ]);
})->name('refresh-csrf');

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

    // Face Registration Management (Admin Only)
    Route::middleware('only_admin')->group(function () {
        Route::get('/face-registrations', function () {
            return view('admin.face-registrations');
        })->name('admin.face-registrations');

        // Office Locations Management (Admin Only)
        Route::get('/office-locations', function () {
            return view('admin.office-locations');
        })->name('admin.office-locations');

        // Master Data (Admin Only)
        Route::get('/masterdata/division', [MasterDataController::class, 'division'])->name('admin.masters.division');
        Route::get('/masterdata/job-title', [MasterDataController::class, 'jobTitle'])->name('admin.masters.job-title');
        Route::get('/masterdata/education', [MasterDataController::class, 'education'])->name('admin.masters.education');
        Route::get('/masterdata/shift', [MasterDataController::class, 'shift'])->name('admin.masters.shift');
        Route::get('/masterdata/subject', [MasterDataController::class, 'subject'])->name('admin.masters.subject');
        Route::get('/masterdata/admin', [MasterDataController::class, 'admin'])->name('admin.masters.admin');

        // Import/Export (Admin Only)
        Route::get('/import-export/users', [ImportExportController::class, 'users'])->name('admin.import-export.users');
        Route::get('/import-export/attendances', [ImportExportController::class, 'attendances'])->name('admin.import-export.attendances');
        Route::get('/import-export/users/template', [ImportExportController::class, 'userTemplate'])->name('admin.import-export.users.template');
        Route::get('/import-export/attendances/template', [ImportExportController::class, 'attendanceTemplate'])->name('admin.import-export.attendances.template');
        Route::post('/users/import', [ImportExportController::class, 'importUsers'])->name('admin.users.import');
        Route::post('/attendances/import', [ImportExportController::class, 'importAttendances'])->name('admin.attendances.import');
        Route::get('/users/export', [ImportExportController::class, 'exportUsers'])->name('admin.users.export');
        Route::get('/attendances/export', [ImportExportController::class, 'exportAttendances'])->name('admin.attendances.export');

        // Academic Events (Admin Only)
        Route::get('/academic-events', function () {
            return view('admin.academic-events');
        })->name('admin.academic-events');

        // Settings (Admin Only)
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('admin.settings');
        Route::post('/settings/logo', [\App\Http\Controllers\Admin\SettingsController::class, 'updateLogo'])->name('admin.settings.update-logo');
        Route::post('/settings/app-name', [\App\Http\Controllers\Admin\SettingsController::class, 'updateAppName'])->name('admin.settings.update-app-name');
        Route::post('/settings/attendance', [\App\Http\Controllers\Admin\SettingsController::class, 'updateAttendanceSettings'])->name('admin.settings.update-attendance');
        Route::post('/settings/face-recognition', [\App\Http\Controllers\Admin\SettingsController::class, 'updateFaceRecognitionSettings'])->name('admin.settings.update-face-recognition');
        Route::post('/settings/gps-anti-spoofing', [\App\Http\Controllers\Admin\SettingsController::class, 'updateGPSAntiSpoofingSettings'])->name('admin.settings.update-gps-anti-spoofing');
        Route::delete('/settings/logo', [\App\Http\Controllers\Admin\SettingsController::class, 'resetLogo'])->name('admin.settings.reset-logo');
    });

    // Employee
    Route::resource('/employees', EmployeeController::class)
        ->only(['index'])
        ->names(['index' => 'admin.employees']);

    // Teacher
    Route::resource('/teachers', \App\Http\Controllers\Admin\TeacherController::class)
        ->only(['index'])
        ->names(['index' => 'admin.teachers']);

    // Teacher Subjects (Mata Pelajaran Guru)
    Route::get('/teacher-subjects', function () {
        return view('admin.teacher-subjects');
    })->name('admin.teacher-subjects');

    // Attendance (Both Admin & Teacher)
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('admin.attendances');
    Route::get('/attendances/report', [AttendanceController::class, 'report'])->name('admin.attendances.report');
    Route::get('/attendances/absent-report', [AttendanceController::class, 'absentReport'])->name('admin.attendances.absent-report');
<<<<<<< HEAD
=======

    // Import/Export
    Route::get('/import-export/users', [ImportExportController::class, 'users'])->name('admin.import-export.users');
    Route::get('/import-export/attendances', [ImportExportController::class, 'attendances'])->name('admin.import-export.attendances');
    Route::get('/import-export/users/template', [ImportExportController::class, 'userTemplate'])->name('admin.import-export.users.template');
    Route::get('/import-export/attendances/template', [ImportExportController::class, 'attendanceTemplate'])->name('admin.import-export.attendances.template');
    Route::post('/users/import', [ImportExportController::class, 'importUsers'])->name('admin.users.import');
    Route::post('/attendances/import', [ImportExportController::class, 'importAttendances'])->name('admin.attendances.import');
    Route::get('/users/export', [ImportExportController::class, 'exportUsers'])->name('admin.users.export');
    Route::get('/attendances/export', [ImportExportController::class, 'exportAttendances'])->name('admin.attendances.export');
>>>>>>> b7451b4dfb32aa6d059cb1d176141e6ab49a7ffd

    // Bills and Tasks routes removed

    // Academic Calendar (Both Admin & Teacher)
    Route::get('/academic-calendar', [App\Http\Controllers\AcademicCalendarController::class, 'index'])
        ->name('admin.academic-calendar');
    Route::post('/academic-calendar/sync-holidays', [App\Http\Controllers\AcademicCalendarController::class, 'syncHolidays'])
        ->name('admin.academic-calendar.sync-holidays');

<<<<<<< HEAD
    // Admin Profile
    Route::get('/profile', [\App\Http\Controllers\AdminProfileController::class, 'show'])->name('admin.profile');

    // ==========================================
    // PORTAL GURU ROUTES
    // ==========================================
    // Materials (Materi)
    Route::get('/materials', [TeacherPortalController::class, 'materials'])->name('admin.materials');
    Route::get('/materials/create', [TeacherPortalController::class, 'createMaterial'])->name('admin.materials.create');
    Route::post('/materials', [TeacherPortalController::class, 'storeMaterial'])->name('admin.materials.store');
    Route::get('/materials/{material}/edit', [TeacherPortalController::class, 'editMaterial'])->name('admin.materials.edit');
    Route::put('/materials/{material}', [TeacherPortalController::class, 'updateMaterial'])->name('admin.materials.update');
    Route::delete('/materials/{material}', [TeacherPortalController::class, 'deleteMaterial'])->name('admin.materials.destroy');

    // Schedules (Jadwal)
    Route::get('/schedules', [TeacherPortalController::class, 'schedules'])->name('admin.schedules');
    Route::get('/schedules/create', [TeacherPortalController::class, 'createSchedule'])->name('admin.schedules.create');
    Route::post('/schedules', [TeacherPortalController::class, 'storeSchedule'])->name('admin.schedules.store');
    Route::get('/schedules/{schedule}/edit', [TeacherPortalController::class, 'editSchedule'])->name('admin.schedules.edit');
    Route::put('/schedules/{schedule}', [TeacherPortalController::class, 'updateSchedule'])->name('admin.schedules.update');
    Route::delete('/schedules/{schedule}', [TeacherPortalController::class, 'deleteSchedule'])->name('admin.schedules.destroy');

    // Exams (Ujian CBT)
    Route::get('/exams', [TeacherPortalController::class, 'exams'])->name('admin.exams');
    Route::get('/exams/create', [TeacherPortalController::class, 'createExam'])->name('admin.exams.create');
    Route::post('/exams', [TeacherPortalController::class, 'storeExam'])->name('admin.exams.store');
    Route::get('/exams/{exam}', [TeacherPortalController::class, 'showExam'])->name('admin.exams.show');
    Route::post('/exams/{exam}/questions', [TeacherPortalController::class, 'storeQuestion'])->name('admin.exams.questions.store');
    Route::delete('/exams/{exam}/questions/{question}', [TeacherPortalController::class, 'deleteQuestion'])->name('admin.exams.questions.destroy');
    Route::get('/exams/{exam}/results', [TeacherPortalController::class, 'examResults'])->name('admin.exams.results');
    Route::delete('/exams/{exam}', [TeacherPortalController::class, 'deleteExam'])->name('admin.exams.destroy');

    // Grades (Input Nilai)
    Route::get('/grades', [TeacherPortalController::class, 'grades'])->name('admin.grades');
    Route::post('/grades', [TeacherPortalController::class, 'storeGrades'])->name('admin.grades.store');

    // Report Cards (Raport Semesteran)
    Route::get('/report-cards', [TeacherPortalController::class, 'reportCards'])->name('admin.report-cards');
    Route::get('/report-cards/{student}', [TeacherPortalController::class, 'showStudentReport'])->name('admin.report-cards.show');
    Route::post('/report-cards/{student}', [TeacherPortalController::class, 'storeReportCard'])->name('admin.report-cards.store');

    // Tasks (Tugas)
    Route::get('/tasks', [TaskController::class, 'index'])->name('admin.tasks');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('admin.tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('admin.tasks.store');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('admin.tasks.show');
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('admin.tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('admin.tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('admin.tasks.destroy');
    Route::post('/tasks/{task}/submissions/{submission}/status', [TaskController::class, 'updateSubmissionStatus'])->name('admin.tasks.submissions.status');
=======
    // Settings
    Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('admin.settings');
    Route::post('/settings/logo', [\App\Http\Controllers\Admin\SettingsController::class, 'updateLogo'])->name('admin.settings.update-logo');
    Route::post('/settings/app-name', [\App\Http\Controllers\Admin\SettingsController::class, 'updateAppName'])->name('admin.settings.update-app-name');
    Route::post('/settings/attendance', [\App\Http\Controllers\Admin\SettingsController::class, 'updateAttendanceSettings'])->name('admin.settings.update-attendance');
    Route::post('/settings/face-recognition', [\App\Http\Controllers\Admin\SettingsController::class, 'updateFaceRecognitionSettings'])->name('admin.settings.update-face-recognition');
    Route::delete('/settings/logo', [\App\Http\Controllers\Admin\SettingsController::class, 'resetLogo'])->name('admin.settings.reset-logo');

    // Admin Profile
    Route::get('/profile', function () {
        return view('admin.profile');
    })->name('admin.profile');
>>>>>>> b7451b4dfb32aa6d059cb1d176141e6ab49a7ffd
}); // ← tutup admin middleware group

// TEACHER AREA
Route::prefix('teacher')->middleware('teacher')->group(function () {
    Route::get('/', fn () => redirect('/teacher/dashboard'));
    Route::get('/dashboard', function () {
        return view('teacher.dashboard');
    })->name('teacher.dashboard');

    // Employee (Siswa)
    Route::resource('/employees', EmployeeController::class)
        ->only(['index'])
        ->names(['index' => 'teacher.employees']);

    // Teacher (Guru) - REMOVED: Only admin can access teacher data

    // Attendance (Presensi)
    Route::get('/attendances', [AttendanceController::class, 'index'])->name('teacher.attendances');
    Route::get('/attendances/report', [AttendanceController::class, 'report'])->name('teacher.attendances.report');
    Route::get('/attendances/absent-report', [AttendanceController::class, 'absentReport'])->name('teacher.attendances.absent-report');

    // Academic Calendar (Kalender Akademik)
    Route::get('/academic-calendar', [App\Http\Controllers\AcademicCalendarController::class, 'index'])
        ->name('teacher.academic-calendar');

    // Materials (Materi)
    Route::get('/materials', [TeacherPortalController::class, 'materials'])->name('teacher.materials');
    Route::get('/materials/create', [TeacherPortalController::class, 'createMaterial'])->name('teacher.materials.create');
    Route::post('/materials', [TeacherPortalController::class, 'storeMaterial'])->name('teacher.materials.store');
    Route::get('/materials/{material}/edit', [TeacherPortalController::class, 'editMaterial'])->name('teacher.materials.edit');
    Route::put('/materials/{material}', [TeacherPortalController::class, 'updateMaterial'])->name('teacher.materials.update');
    Route::delete('/materials/{material}', [TeacherPortalController::class, 'deleteMaterial'])->name('teacher.materials.destroy');

    // Schedules (Jadwal)
    Route::get('/schedules', [TeacherPortalController::class, 'schedules'])->name('teacher.schedules');
    Route::get('/schedules/create', [TeacherPortalController::class, 'createSchedule'])->name('teacher.schedules.create');
    Route::post('/schedules', [TeacherPortalController::class, 'storeSchedule'])->name('teacher.schedules.store');
    Route::get('/schedules/{schedule}/edit', [TeacherPortalController::class, 'editSchedule'])->name('teacher.schedules.edit');
    Route::put('/schedules/{schedule}', [TeacherPortalController::class, 'updateSchedule'])->name('teacher.schedules.update');
    Route::delete('/schedules/{schedule}', [TeacherPortalController::class, 'deleteSchedule'])->name('teacher.schedules.destroy');

    // Exams (Ujian CBT)
    Route::get('/exams', [TeacherPortalController::class, 'exams'])->name('teacher.exams');
    Route::get('/exams/create', [TeacherPortalController::class, 'createExam'])->name('teacher.exams.create');
    Route::post('/exams', [TeacherPortalController::class, 'storeExam'])->name('teacher.exams.store');
    Route::get('/exams/{exam}', [TeacherPortalController::class, 'showExam'])->name('teacher.exams.show');
    Route::post('/exams/{exam}/questions', [TeacherPortalController::class, 'storeQuestion'])->name('teacher.exams.questions.store');
    Route::delete('/exams/{exam}/questions/{question}', [TeacherPortalController::class, 'deleteQuestion'])->name('teacher.exams.questions.destroy');
    Route::get('/exams/{exam}/results', [TeacherPortalController::class, 'examResults'])->name('teacher.exams.results');
    Route::delete('/exams/{exam}', [TeacherPortalController::class, 'deleteExam'])->name('teacher.exams.destroy');

    // Grades (Input Nilai)
    Route::get('/grades', [TeacherPortalController::class, 'grades'])->name('teacher.grades');
    Route::post('/grades', [TeacherPortalController::class, 'storeGrades'])->name('teacher.grades.store');

    // Report Cards (Raport Semesteran)
    Route::get('/report-cards', [TeacherPortalController::class, 'reportCards'])->name('teacher.report-cards');
    Route::get('/report-cards/{student}', [TeacherPortalController::class, 'showStudentReport'])->name('teacher.report-cards.show');
    Route::post('/report-cards/{student}', [TeacherPortalController::class, 'storeReportCard'])->name('teacher.report-cards.store');

    // Tasks (Tugas)
    Route::get('/tasks', [TaskController::class, 'index'])->name('teacher.tasks');
    Route::get('/tasks/create', [TaskController::class, 'create'])->name('teacher.tasks.create');
    Route::post('/tasks', [TaskController::class, 'store'])->name('teacher.tasks.store');
    Route::get('/tasks/{task}', [TaskController::class, 'show'])->name('teacher.tasks.show');
    Route::get('/tasks/{task}/edit', [TaskController::class, 'edit'])->name('teacher.tasks.edit');
    Route::put('/tasks/{task}', [TaskController::class, 'update'])->name('teacher.tasks.update');
    Route::delete('/tasks/{task}', [TaskController::class, 'destroy'])->name('teacher.tasks.destroy');
    Route::post('/tasks/{task}/submissions/{submission}/status', [TaskController::class, 'updateSubmissionStatus'])->name('teacher.tasks.submissions.status');

    // Profile
    Route::get('/profile', [\App\Http\Controllers\TeacherProfileController::class, 'show'])->name('teacher.profile');
});
}); // ← tutup auth middleware group