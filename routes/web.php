<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MeetController;
use App\Http\Controllers\StudyPlanController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\WelcomeController;
use GPBMetadata\Google\Api\Auth;
use Kreait\Firebase\Factory;
use PharIo\Manifest\AuthorCollection;

// landing page 
Route::get('/', [WelcomeController::class,  'index'])->name('welcome.view');


// auth 
// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticationController::class, 'showLogin'])->name('login');
    // auth activate here
    Route::post('/login', [AuthenticationController::class, 'login']);

    Route::get('/register', [AuthenticationController::class, 'showRegister']);
    Route::post('/register', [AuthenticationController::class, 'register'])->name('register');
});

Route::get('/logout', [AuthenticationController::class, 'logout']);


Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Tasks
Route::get('/tasks', [TaskController::class, 'view'])->name('tasks');
Route::post('/tasks', [TaskController::class, 'store'])->name('tasks.store');
Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');

// Study Plan
Route::get('/study-plan', [StudyPlanController::class, 'index'])->name('study-plan');

// Meet
Route::get('/meet', [MeetController::class, 'index'])->name('meet');

// Chat
Route::get('/chat', [ChatController::class, 'index'])->name('chat');


// verification 
Route::get('/verify', [AuthenticationController::class, 'verifyEmail'])->name('verify.email');
