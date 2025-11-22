<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StreamController;
use App\Http\Controllers\Admin\Pdf\PdfController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\TeacherController;
use App\Http\Controllers\Admin\PaymentsController;
use App\Http\Controllers\Admin\RemedialController;
use App\Http\Controllers\Admin\PromotionController;
use App\Http\Controllers\Admin\ManageClassController;
use App\Http\Controllers\Admin\SubjectLearningAreaController;

require __DIR__ . '/auth.php';


// ==========================================================
// AUTHENTICATED ROUTES
// ==========================================================
Route::middleware(['auth'])->group(function () {

    Route::get('/', [DashboardController::class, 'home'])->name('home')->middleware('auth');

    Route::prefix('teacher')->group(function () {
        Route::get('change-password', [TeacherController::class, 'showChangePasswordForm'])->name('teacher.changepassword');
        Route::post('change-password/store', [TeacherController::class, 'updatePassword'])->name('teacher.password.update');
    });

    // ======================================================
    // DASHBOARD ROUTES
    // ======================================================
    Route::prefix('dashboard')->group(function () {
        Route::get('/admin', [DashboardController::class, 'admin'])
            ->name('dashboard.admin')->middleware('role:super_admin|principal|deputy|dos|senior_teacher');

        Route::get('/committee', [DashboardController::class, 'committee'])
            ->name('dashboard.committee')->middleware('role:committee_member');

        Route::get('/class-supervisor', [DashboardController::class, 'classSupervisor'])
            ->name('dashboard.class_supervisor')->middleware('role:class_supervisor');

        Route::get('/class-teacher', [DashboardController::class, 'classTeacher'])
            ->name('dashboard.class_teacher')->middleware('role:class_teacher');

        Route::get('/teacher', [DashboardController::class, 'teacher'])
            ->name('dashboard.teacher')->middleware('role:teacher');
    });

    // ======================================================
    // TEACHERS MANAGEMENT
    // ======================================================
    Route::middleware('can:manage_users')->prefix('teachers')->group(function () {
        Route::get('/', [TeacherController::class, 'index'])->name('teachers.index');
        Route::get('create', [TeacherController::class, 'create'])->name('teacher.create');
        Route::post('/', [TeacherController::class, 'store'])->name('teacher.store');
        Route::get('{id}/{slug}/edit', [TeacherController::class, 'edit'])->name('teacher.edit');
        Route::put('{id}/{slug}', [TeacherController::class, 'update'])->name('teacher.update');
        Route::delete('{id}/{slug}', [TeacherController::class, 'destroy'])->name('teacher.destroy');
        Route::post('change-role', [TeacherController::class, 'changeRole'])->name('teacher.changeRole');
    });

    // ======================================================
    // CLASSES & STREAMS
    // ======================================================
    Route::middleware('can:manage_classes')->prefix('classes')->group(function () {

        Route::get('/', [ManageClassController::class, 'index'])->name('classes.index');
        Route::get('{curriculum}', [ManageClassController::class, 'show'])->name('classes.show');
        Route::post('update-supervisor', [ManageClassController::class, 'updateSupervisor'])->name('classes.updateSupervisor');
        Route::post('admin/students/promote-all', [PromotionController::class, 'promoteAll'])->name('students.promoteAll');

        // Streams under a class type
        Route::prefix('{type}/{id}/streams')->group(function () {
            Route::get('/', [StreamController::class, 'showStreams'])->name('classes.streams.showStreams');
            Route::get('create', [StreamController::class, 'create'])->name('classes.streams.create');
            Route::post('/', [StreamController::class, 'store'])->name('classes.streams.store');
        });

        // Individual stream actions
        Route::prefix('stream/{stream}')->group(function () {
            Route::get('edit', [StreamController::class, 'edit'])->name('classes.streams.edit');
            Route::put('/', [StreamController::class, 'update'])->name('classes.streams.update');
            Route::delete('/', [StreamController::class, 'destroy'])->name('classes.streams.destroy');
            Route::get('students', [StreamController::class, 'manageStudents'])->name('classes.streams.students');
            Route::post('update-teacher', [StreamController::class, 'updateTeacher'])->name('classes.streams.updateTeacher');
        });
    });

    // ======================================================
    // STUDENTS
    // ======================================================
    Route::middleware('can:manage_classes')->prefix('students')->group(function () {

        Route::get('stream/{stream}/create', [StudentController::class, 'create'])->name('classes.streams.students.create');
        Route::post('stream/{stream}', [StudentController::class, 'store'])->name('classes.streams.students.store');

        Route::get('stream/students/{student}/edit', [StudentController::class, 'edit'])->name('classes.streams.students.edit');
        Route::put('stream/students/{student}', [StudentController::class, 'update'])->name('classes.streams.students.update');
        Route::delete('stream/students/{student}', [StudentController::class, 'destroy'])->name('classes.streams.students.delete');

        // Excel import
        Route::get('stream/{stream}/import', [StudentController::class, 'importForm'])->name('admin.students.import.grade');
        Route::post('stream/{stream}/students/import', [StudentController::class, 'importStore'])->name('admin.students.import.grade.store');

        // API
        Route::get('api/academic-years/{year}/terms', [StudentController::class, 'getTermsByYear']);
    });

    // ======================================================
    // SUBJECTS / LEARNING AREAS
    // ======================================================
    Route::middleware('can:manage_curricula')->prefix('subjects')->group(function () {
        Route::get('/', [SubjectLearningAreaController::class, 'index'])->name('subjects.index');
        Route::get('create/{curriculum}', [SubjectLearningAreaController::class, 'create'])->name('subjects.create');
        Route::post('/', [SubjectLearningAreaController::class, 'store'])->name('subjects.store');
        Route::get('{id}/edit', [SubjectLearningAreaController::class, 'edit'])->name('subjects.edit');
        Route::put('{id}', [SubjectLearningAreaController::class, 'update'])->name('subjects.update');
        Route::delete('{id}', [SubjectLearningAreaController::class, 'destroy'])->name('subjects.destroy');
    });

    // ======================================================
    // REMEDIAL & PAYMENTS
    // ======================================================
    Route::prefix('remedial')->group(function () {

        Route::get('/', [RemedialController::class, 'index'])->name('remedial.index');

        Route::get('payments', [PaymentsController::class, 'index'])
            ->middleware('can:payment_capture')
            ->name('payments.index');

        Route::get('payment/capture', [PaymentsController::class, 'captureForm'])->name('remedial.payments.capture');
        Route::get('search-student', [PaymentsController::class, 'searchStudent'])->name('remedial.payments.searchStudent');
        Route::post('store', [PaymentsController::class, 'store'])->name('remedial.payments.store');

        Route::get('student/search', [PaymentsController::class, 'searchStudent'])->name('remedial.student.search');
        Route::get('student/{student}', [PaymentsController::class, 'studentProfile'])->name('remedial.student.profile');
    });

    // ======================================================
    // PDF EXPORTS
    // ======================================================
    Route::prefix('admin/pdf')->group(function () {
        Route::get('remedial/grade/{grade}', [PdfController::class, 'generateGradeRemedialPDF'])
            ->name('admin.pdf.remedial.grade');
    });
});
