<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\MenuItemManagement;
use App\Livewire\Admin\MenuCategoryManagement;
use App\Livewire\Admin\TableManagement;
use App\Livewire\Welcome;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ChangePassword;
use Illuminate\Support\Facades\Auth;

Route::get('/', Welcome::class)->name('dashboard');
Route::get('/admin/menu-category-management', MenuCategoryManagement::class)->name('admin.menu-category-management');
Route::get('/admin/menu-item-management', MenuItemManagement::class)->name('admin.menu-item-management');
Route::get('/admin/table-management', TableManagement::class)->name('admin.table-management');
Route::get('/login', Login::class)->name('login');
Route::get('/forgot-password', ForgotPassword::class)->name('forgot-password');
Route::middleware('auth')->group(function () {
    Route::get('/change-password', ChangePassword::class)->name('change-password');
    Route::get('/logout', function () {
        Auth::logout();
        return redirect()->route('login');
    })->name('logout');
});