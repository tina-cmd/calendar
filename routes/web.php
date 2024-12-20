<?php

use App\Http\Controllers\NavigateController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;


Route::get('/migrate', function () {
    Artisan::call('migrate');
    return 'Migrations ran successfully!';
});
Route::get('/', [NavigateController::class, 'index'])->name('home');
Route::post('/login', [AuthController::class, 'authenticate'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Route::get('/todolist', [NavigateController::class, 'todo,list'])->name('get.todolist');

Route::middleware('auth-user')->group(function () {
    Route::get('/dashboard/todolist', [NavigateController::class, 'todolist'])->name('get.todolist');
    Route::post('/dashboard/logout', [AuthController::class, 'destroy'])->name('logout');
    Route::get('/dashboard/account', [AuthController::class, 'account'])->name('account');
    Route::post('/dashboard/post', [TaskController::class, 'post'])->name('post.task');
    Route::delete('/dashboard/delete', [TaskController::class, 'destroy'])->name('post.delete');
    Route::get('/dashboard/about', [AuthController::class, 'about'])->name('about');
    Route::put('/dashboard/update', [TaskController::class, 'update'])->name('update');
    Route::post('/dashboard/toggle', [TaskController::class, 'edit_status'])->name('status');
    Route::post('/dashboard/expired', [TaskController::class, 'expired_status'])->name('expired');
    Route::post('/account/update', [AuthController::class, 'update_info'])->name('update_info');
    Route::delete('/account/delete', [AuthController::class, 'delete_account'])->name('delete');
});