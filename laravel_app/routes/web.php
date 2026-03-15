<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\StudentAuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/student-portal', [StudentAuthController::class, 'showLoginForm'])->name('student.login');
Route::post('/student-portal', [StudentAuthController::class, 'login'])->name('student.login.post');
Route::post('/logout', [StudentAuthController::class, 'logout'])->name('student.logout');

Route::middleware(['auth:student'])->group(function () {
    Route::get('/dashboard', [StudentDashboardController::class, 'index'])->name('student.dashboard');
});

// Legacy pages converted to Blade (placeholders)
Route::view('/admin-login', 'pages.admin_login')->name('admin.login');
Route::view('/admin-dashboard', 'pages.admin_dashboard')->name('admin.dashboard');
Route::view('/contact', 'pages.contact')->name('contact');
Route::view('/home', 'pages.home_page')->name('home');
Route::view('/my-courses', 'pages.my_courses')->name('my.courses');
Route::view('/profile-details', 'pages.profile_details')->name('profile.details');
Route::view('/student-registration', 'pages.student_registration')->name('student.registration');
Route::view('/teacher-login', 'pages.teacher_login')->name('teacher.login');
Route::view('/teacher-dashboard', 'pages.teacher_dashboard')->name('teacher.dashboard');
