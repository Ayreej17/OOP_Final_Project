<?php

use App\Http\Controllers\AuditTrailController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified', 'active'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // ==========================================
    // Assigned to @Ayreej17 (POS & OOP Order Processing)
    // ==========================================
    Route::get('/orders/pos', [OrderController::class, 'create'])->name('orders.pos');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');

    // ==========================================
    // Assigned to @srabmaudo-bit (Reports & Audit Trail)
    // ==========================================

    // 1. Order Status Tracking Board
    Route::get('/orders/status-board', [OrderStatusController::class, 'index'])->name('orders.status-board');
    Route::patch('/orders/{order}/status', [OrderStatusController::class, 'updateStatus'])->name('orders.update-status');

    // 2. Sales & Analytics Reports
    Route::get('/reports/sales', [ReportController::class, 'salesSummary'])->name('reports.sales');
    Route::get('/reports/top-selling', [ReportController::class, 'topSelling'])->name('reports.top-selling');

    // ========================================
    // Assigned to @Heqieboo (User Management & Master Data)
    // ========================================

    // Admin AND staff can VIEW the menu (read-only)
    Route::get('/menu-items', [MenuItemController::class, 'index'])->name('menu-items.index');

    // Admin only: everything that changes data
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class)->except(['show']);
        Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])
            ->name('users.toggle-status');

        // 3. System Audit Trail
        Route::get('/audit-trail', [AuditTrailController::class, 'index'])->name('audit.index');

        Route::resource('categories', CategoryController::class)->except(['show']);
        Route::resource('menu-items', MenuItemController::class)->except(['show', 'index']);
    });
});

require __DIR__.'/settings.php';
