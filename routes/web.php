<?php

use App\Livewire\Welcome;
use App\Livewire\Checkout;
use App\Livewire\ViewCart;
use App\Livewire\ViewOrder;
use App\Livewire\Auth\Login;
use App\Livewire\SelectItem;
use App\Livewire\SelectTable;
use App\Livewire\StaffHomePage;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\ResetPassword;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Auth\ChangePassword;
use App\Livewire\Auth\ForgotPassword;
use Illuminate\Support\Facades\Route;
use App\Livewire\Admin\TableManagement;
use App\Livewire\Admin\MenuItemManagement;
use App\Livewire\Admin\MenuCategoryManagement;
use App\Livewire\Report\SaleReport;

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
    Route::get('/staff-home', StaffHomePage::class)->name('staff-home');
    Route::get('/change-password', ChangePassword::class)->name('change-password');
    Route::get('/select-table', SelectTable::class)->name('select-table');
    Route::get('/select-item', SelectItem::class)->name('select-item');
    Route::get('/view-cart', ViewCart::class)->name('view-cart');
    Route::get('/view-order', ViewOrder::class)->name('view-order');
    Route::get('/checkout', Checkout::class)->name('checkout');
    Route::get('/sale-report', SaleReport::class)->name('sale-report');
    Route::get('/logout', function () {
        Auth::logout();
        return redirect()->route('login');
    })->name('logout');
});