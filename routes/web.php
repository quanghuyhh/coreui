<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');

    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('forgot-password', [PasswordResetLinkController::class, 'create'])
        ->name('password.request');

    Route::post('forgot-password', [PasswordResetLinkController::class, 'store'])
        ->name('password.email');

    Route::get('reset-password/{token}', [NewPasswordController::class, 'create'])
        ->name('password.reset');

    Route::post('reset-password', [NewPasswordController::class, 'store'])
        ->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
});

Route::get('/', [HomeController::class, 'index'])->name('home')->middleware('auth');

Route::group(['middleware' => 'web'], function() {
    Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => 'role:manager'], function () {
        Route::get('/', [AdminController::class, 'index'])->name('dashboard');
        Route::view('/schools', 'school.index')->name('schools.index');
        Route::view('/roles', 'role.index')->name('roles.index');
        Route::group(['prefix' => 'shifts', 'as' => 'shifts.'], function () {
            Route::get('/{id}/edit-shift', [ShiftController::class, 'editShift'])->name('editShift');
        });
        Route::resource('/shifts', ShiftController::class);
    });

    Route::group(['prefix' => 'teacher', 'as' => 'teacher.', 'middleware' => 'role:teacher'], function () {
        Route::get('/', [TeacherController::class, 'index'])->name('dashboard');
        Route::group(['prefix' => 'shift', 'as' => 'shift.'], function () {
           Route::get('/shift-application', [TeacherController::class, 'application'])->name('application');
        });
    });
});

