<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminPDFExport;
use App\Http\Controllers\FormController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WeeksController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AttendanceController;



Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/remedial', function () {
    return view('remedial.index');
})->middleware(['auth', 'verified'])->name('dashboard');



require __DIR__ . '/auth.php';

Route::middleware(['auth', 'auth.Admin'])->group(function () {


    Route::get('/remedial/users', [UserController::class, 'index'])->name('users.index');

    Route::get('/remedial/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/remedial/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/remedial/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/remedial/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/remedial/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::put('/remedial/users/{user}/update-role', [UserController::class, 'updateRole'])->name('users.updateRole');


    Route::get('/remedial/attendances', [AttendanceController::class, 'index'])->name('attendance.index');
    Route::get('/remedial/attendance/create', [AttendanceController::class, 'create'])->name('attendance.create');
    Route::post('/remedial/attendance/store', [AttendanceController::class, 'store'])->name('attendance.store');
    Route::get('/remedial/attendance/{user_id}/show', [AttendanceController::class, 'show'])->name('attendance.show');


    Route::get('/remedial/classrecords', [AttendanceController::class, 'forms'])->name('attendance.classrecords');
    // Route::get('/remedial/classrecords/{id}/attendance/show', [FormController::class, 'classrecords'])->name('form.attendance.show');
    Route::get('/remedial/classrecords/{id}/attendance/show', [FormController::class, 'showAttendance'])->name('form.attendance.show');
    Route::get('/remedial/classrecords/{id}/attendance/week/{week_id}', [FormController::class, 'showAttendanceWeek'])->name('form.attendance.week');





    Route::get('/remedial/attendance/{week}/{user_id}/show', [AttendanceController::class, 'userweekly'])->name('user.attendances.viewweekly');
    Route::delete('/remedial/attendance/{id}/delete', [AttendanceController::class, 'destroy'])->name('attendance.delete');
    Route::post('attendance/deleteAll', [AttendanceController::class, 'deleteAll'])->name('attendance.deleteAll');


    // forms/classes
    Route::get('/remedial/forms', [FormController::class, 'index'])->name('forms.index');
    Route::get('/remedial/form/create', [FormController::class, 'create'])->name('form.create');
    Route::post('/remedial/form/store', [FormController::class, 'store'])->name('form.store');
    Route::get('/remedial/form/{id}/edit', [FormController::class, 'edit'])->name('form.edit');
    Route::patch('/remedial/form/{id}', [FormController::class, 'update'])->name('form.update');
    Route::delete('/remedial/form/delete/{id}', [FormController::class, 'destroy'])->name('form.destroy');



    // weeks
    Route::get('/remedial/weeks', [WeeksController::class, 'index'])->name('weeks.index');
    Route::get('/remedial/weeks/create', [WeeksController::class, 'create'])->name('week.create');
    Route::post('/remedial/weeks/store', [WeeksController::class, 'store'])->name('week.store');
    Route::delete('/remedial/weeks/{week}', [WeeksController::class, 'destroy'])->name('week.destroy');



    Route::get('/remedial/attendance/download', [AdminPDFExport::class, 'AllLessonCountExport'])->name('pdfexport');
    Route::get('/remedial/attendance/download/report', [AdminPDFExport::class, 'WeeklyAttendanceReportExport'])->name('finalreport');
});

Route::middleware(['auth'])->group(function () {

    Route::get('/remedial/users/change-password', [UserController::class, 'changepassword'])->name('users.password');
    Route::post('/remedial/password/change', [UserController::class, 'storepassword'])->name('users.password.store');

    Route::get('/remedial/attendance/mylessons', [UserController::class, 'mylessons'])->name('user.attendance');
    Route::get('/remedial/attendance/week/{week}/user/{user_id}', [UserController::class, 'userweekly'])->name('user.viewweekly');

    Route::get('/remedial/comments', [CommentController::class, 'index'])->name('comment.index');
    Route::post('/remedial/comments', [CommentController::class, 'index'])->name('comment.filter');
    Route::get('/remedial/comments/create', [CommentController::class, 'create'])->name('comment.create');
    Route::post('/remedial/comments/store', [CommentController::class, 'store'])->name('comment.store');
    Route::get('/remedial/comments/{id}/edit', [CommentController::class, 'edit'])->name('comment.edit');
    Route::put('/remedial/comments/{id}', [CommentController::class, 'update'])->name('comment.update');
    Route::delete('/remedial/comments/{id}', [CommentController::class, 'destroy'])->name('comment.destroy');
    Route::get('/remedial/comments/export-pdf', [CommentController::class, 'exportToPDF'])->name('exportToPDF');

});
