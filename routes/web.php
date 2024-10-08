<?php

use App\Enum\CanEnum;
use App\Livewire\Admin;
use App\Livewire\Auth\Password\{Recovery, Reset};
use App\Livewire\Auth\{Login, Register};
use App\Livewire\{Welcome};
use Illuminate\Support\Facades\Route;

//region Login
Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('auth.register');
Route::get('/password/recovery', Recovery::class)->name('password.recovery');
Route::get('/password/reset', Reset::class)->name('password.reset');
//endregion

//region Autenticated
Route::group(['middleware' => 'auth'], function () {
    Route::get('/', Welcome::class)->name('welcome');

    //region Admin
    Route::prefix('admin')->middleware('can:' . CanEnum::BE_AN_ADMIN->value)->group(function () {
        Route::get('/dashboard', Admin\Dashboard::class)->name('admin.dashboard');
        Route::get('/users', Admin\Users\Index::class)->name('admin.users');
        Route::get('matrices', Admin\Matrices\Index::class)->name('admin.matrices.index');
    });
    //endregion

});
//endregion
