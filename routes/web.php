<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\MenuItemManagement;
use App\Livewire\Admin\MenuCategoryManagement;
use App\Livewire\Admin\TableManagement;
use App\Livewire\Welcome;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\ChangePassword;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\SelectTable;
use App\Livewire\SelectItem;
use Illuminate\Support\Facades\Auth;


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/menu-category-management', MenuCategoryManagement::class)->name('admin.menu-category-management');
    Route::get('/admin/menu-item-management', MenuItemManagement::class)->name('admin.menu-item-management');
    Route::get('/admin/table-management', TableManagement::class)->name('admin.table-management');
    Route::get('/user', Register::class)->name('admin.user');
});

Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');
Route::get('/forgot-password', ForgotPassword::class)->name('forgot-password');
Route::get('/reset-password/{token}', ResetPassword::class)->name('password.reset');
Route::middleware('auth')->group(function () {
    Route::get('/', Welcome::class)->name('dashboard');
    Route::get('/change-password', ChangePassword::class)->name('change-password');
    Route::get('/select-table', SelectTable::class)->name('select-table');
    Route::get('/select-item', SelectItem::class)->name('select-item');
    Route::get('/logout', function () {
        Auth::logout();
        return redirect()->route('login');
    })->name('logout');
});