<?php

use App\Http\Controllers\AuditTrailController;
use App\Http\Controllers\OrderStatusController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // ==========================================
    // Assigned to @srabmaudo-bit (Reports & Audit Trail)
    // ==========================================

    // 1. Order Status Tracking Board
    Route::get('/orders/status-board', [OrderStatusController::class, 'index'])->name('orders.status-board');
    Route::patch('/orders/{order}/status', [OrderStatusController::class, 'updateStatus'])->name('orders.update-status');

    // 2. Sales & Analytics Reports
    Route::get('/reports/sales', [ReportController::class, 'salesSummary'])->name('reports.sales');
    Route::get('/reports/top-selling', [ReportController::class, 'topSelling'])->name('reports.top-selling');

    // 3. System Audit Trail
    Route::get('/audit-trail', [AuditTrailController::class, 'index'])->name('audit.index');
});

require __DIR__ .'/settings.php';