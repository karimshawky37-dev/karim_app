<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\ShiftController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\NotificationController;

// ======================
// المصادقة (Auth)
// ======================
Auth::routes();

// ======================
// لوحة التحكم
// ======================
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// ======================
// الأجهزة
// ======================
Route::resource('devices', DeviceController::class);
Route::get('devices/search', [DeviceController::class, 'search'])->name('devices.search');
Route::get('devices/{id}/receipt', [DeviceController::class, 'receipt'])->name('devices.receipt');
Route::get('devices/{id}/sticker', [DeviceController::class, 'sticker'])->name('devices.sticker');
Route::post('devices/deliver', [DeviceController::class, 'deliver'])->name('devices.deliver');
Route::post('devices/cancel-repair', [DeviceController::class, 'cancelRepair'])->name('devices.cancel-repair');
Route::get('devices/waiting', [DeviceController::class, 'waiting'])->name('devices.waiting');

// ======================
// الفواتير
// ======================
Route::resource('invoices', InvoiceController::class);
Route::get('invoices/{id}/print', [InvoiceController::class, 'print'])->name('invoices.print');
Route::post('invoices/{id}/payment', [InvoiceController::class, 'addPayment'])->name('invoices.add-payment');

// ======================
// المخزون
// ======================
Route::resource('inventory', InventoryController::class);
Route::get('inventory/search', [InventoryController::class, 'search'])->name('inventory.search');
Route::get('inventory/low-stock', [InventoryController::class, 'lowStock'])->name('inventory.low-stock');
Route::post('inventory/count', [InventoryController::class, 'countUpdate'])->name('inventory.count-update');

// ======================
// الورديات
// ======================
Route::get('shifts', [ShiftController::class, 'index'])->name('shifts.index');
Route::get('shifts/archive', [ShiftController::class, 'archive'])->name('shifts.archive');
Route::post('shift/start', [ShiftController::class, 'start'])->name('shift.start');
Route::post('shift/end', [ShiftController::class, 'end'])->name('shift.end');

// ======================
// الحضور
// ======================
Route::resource('attendance', AttendanceController::class);
Route::post('attendance/checkin', [AttendanceController::class, 'checkIn'])->name('attendance.checkin');
Route::post('attendance/checkout', [AttendanceController::class, 'checkOut'])->name('attendance.checkout');

// ======================
// الإعدادات
// ======================
Route::get('settings/work', [SettingsController::class, 'work'])->name('settings.work');
Route::post('settings/work/update', [SettingsController::class, 'updateWork'])->name('settings.work.update');
Route::get('settings/shifts', [SettingsController::class, 'shifts'])->name('settings.shifts');
Route::post('settings/shifts/update', [SettingsController::class, 'updateShifts'])->name('settings.shifts.update');

// ======================
// المستخدمون
// ======================
Route::resource('users', UserController::class);

// ======================
// التقارير
// ======================
Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
Route::get('reports/sales', [ReportController::class, 'sales'])->name('reports.sales');
Route::get('reports/profit', [ReportController::class, 'profit'])->name('reports.profit');
Route::get('reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
Route::get('reports/technicians', [ReportController::class, 'technicians'])->name('reports.technicians');

// ======================
// الدردشة والإشعارات
// ======================
Route::get('chat', [ChatController::class, 'index'])->name('chat.index');
Route::post('chat/send', [ChatController::class, 'send'])->name('chat.send');
Route::get('notifications', [NotificationController::class, 'index'])->name('notifications.index');
Route::post('notifications/mark-read/{id}', [NotificationController::class, 'markRead'])->name('notifications.mark-read');

// ======================
// API Routes (البحث المباشر)
// ======================
Route::prefix('api')->group(function () {
    Route::get('devices/search', [DeviceController::class, 'search'])->name('api.devices.search');
    Route::get('inventory/search', [InventoryController::class, 'search'])->name('api.inventory.search');
    Route::get('entities/search', [SearchController::class, 'searchEntities'])->name('api.entities.search');
    Route::get('invoices/unpaid-total', [InvoiceController::class, 'unpaidTotal'])->name('api.invoices.unpaid-total');


    Route::get('devices/{id}/sticker', [DeviceController::class, 'sticker'])->name('devices.sticker');


    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
Route::resource('devices', DeviceController::class);
Route::resource('invoices', InvoiceController::class);
Route::resource('inventory', InventoryController::class);
});