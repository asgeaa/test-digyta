<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::name('task.')
    ->middleware('auth')
    ->controller(TaskController::class)
    ->group(function () {
        Route::get('/task', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::patch('/task/{task:id}', 'update')->name('update');
        Route::patch('/task/{task}/status/{status}', 'updateStatus')->name('updateStatus');
        Route::delete('/task/{task:id}', 'destroy')->name('destroy');
    });

Route::name('auth.')
    ->controller(AuthController::class)
    ->group(function () {
        Route::middleware('guest')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/store', 'store')->name('store');
        });

        Route::get('/logout', 'logout')->name('logout');
    });
