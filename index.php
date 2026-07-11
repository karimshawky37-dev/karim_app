
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// ===== Language Switcher =====
if (isset($_GET['lang']) && in_array($_GET['lang'], ['ar', 'en'])) {
    $_SESSION['locale'] = $_GET['lang'];
    header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
    exit;
}
// ===== Theme Switcher =====
if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light';
}
// Set default locale
if (!isset($_SESSION['locale'])) {
    $_SESSION['locale'] = 'ar';
}

require_once __DIR__ . '/../app/autoload.php';
require_once __DIR__ . '/../app/Config/constants.php';

$request = $_SERVER['REQUEST_URI'];
$request = strtok($request, '?');
$request = rtrim($request, '/');

// ============================================================
// 🔐 AUTH ROUTES
// ============================================================
if ($request === '' || $request === '/') {
    $controller = new App\Controllers\DashboardController();
    $controller->index();
}
elseif ($request === '/login') {
    $controller = new App\Controllers\AuthController();
    $controller->login();
}
elseif ($request === '/login-submit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\AuthController();
    $controller->loginSubmit();
}
elseif ($request === '/logout') {
    $controller = new App\Controllers\AuthController();
    $controller->logout();
}

// ============================================================
// 📱 DEVICES ROUTES
// ============================================================
elseif ($request === '/devices') {
    $controller = new App\Controllers\DeviceController();
    $controller->index();
}
elseif ($request === '/devices/create') {
    $controller = new App\Controllers\DeviceController();
    $controller->create();
}
elseif ($request === '/devices/store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\DeviceController();
    $controller->store();
}
elseif ($request === '/devices/deliver' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\DeviceController();
    $controller->deliver();
}
elseif ($request === '/devices/cancel-repair' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\DeviceController();
    $controller->cancelRepair();
}
elseif ($request === '/devices/waiting') {
    $controller = new App\Controllers\DeviceController();
    $controller->waiting();
}
elseif ($request === '/devices/ready-for-delivery') {
    $controller = new App\Controllers\DeviceController();
    $controller->readyForDelivery();
}
elseif ($request === '/devices/autocomplete') {
    $controller = new App\Controllers\DeviceController();
    $controller->autocomplete();
}
elseif (strpos($request, '/devices/analyze/') === 0) {
    $id = (int) substr($request, strlen('/devices/analyze/'));
    $controller = new App\Controllers\DeviceController();
    $controller->analyzeIssue($id);
}
elseif (strpos($request, '/devices/delete/') === 0) {
    $id = (int) substr($request, strlen('/devices/delete/'));
    $controller = new App\Controllers\DeviceController();
    $controller->delete($id);
}
elseif (strpos($request, '/devices/') === 0) {
    $id = (int) substr($request, strlen('/devices/'));
    if ($id > 0) {
        $controller = new App\Controllers\DeviceController();
        $controller->show($id);
    }
}

// ============================================================
// 👨‍🔧 TECHNICIAN ROUTES
// ============================================================
elseif ($request === '/technician-dashboard') {
    $controller = new App\Controllers\TechnicianController();
    $controller->dashboard();
}
elseif ($request === '/technician/start-inspection' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\TechnicianController();
    $controller->startInspection();
}
elseif ($request === '/technician/request-part' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\TechnicianController();
    $controller->requestPart();
}
elseif ($request === '/technician/start-repair' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\TechnicianController();
    $controller->startRepair();
}
elseif ($request === '/technician/complete-repair' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\TechnicianController();
    $controller->completeRepair();
}
elseif ($request === '/technician/diagnose' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\TechnicianController();
    $controller->diagnose();
}
elseif ($request === '/technician/save-after-repair-checklist' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\TechnicianController();
    $controller->saveAfterRepairChecklist();
}
elseif (strpos($request, '/technician/checklist/') === 0) {
    $id = (int) substr($request, strlen('/technician/checklist/'));
    $controller = new App\Controllers\TechnicianController();
    $controller->checklist($id);
}

// ============================================================
// 👤 CUSTOMERS ROUTES
// ============================================================
elseif ($request === '/customers') {
    $controller = new App\Controllers\CustomerController();
    $controller->index();
}
elseif ($request === '/customers/autocomplete') {
    $controller = new App\Controllers\CustomerController();
    $controller->autocomplete();
}

// ============================================================
// 📦 INVENTORY ROUTES
// ============================================================
elseif ($request === '/inventory') {
    $controller = new App\Controllers\InventoryController();
    $controller->index();
}
elseif ($request === '/inventory/create') {
    $controller = new App\Controllers\InventoryController();
    $controller->create();
}
elseif ($request === '/inventory/store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\InventoryController();
    $controller->store();
}
elseif ($request === '/inventory/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\InventoryController();
    $controller->update();
}
elseif ($request === '/inventory/low-stock') {
    $controller = new App\Controllers\InventoryController();
    $controller->lowStock();
}
elseif ($request === '/inventory/autocomplete') {
    $controller = new App\Controllers\InventoryController();
    $controller->autocomplete();
}
elseif ($request === '/inventory/count') {
    $controller = new App\Controllers\InventoryController();
    $controller->count();
}
elseif ($request === '/inventory/count-update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\InventoryController();
    $controller->countUpdate();
}
elseif ($request === '/inventory/count-report') {
    $controller = new App\Controllers\InventoryController();
    $controller->countReport();
}
elseif ($request === '/inventory/force-update-waiting' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\InventoryController();
    $controller->forceUpdateWaitingDevices();
}
elseif (strpos($request, '/inventory/edit/') === 0) {
    $id = (int) substr($request, strlen('/inventory/edit/'));
    $controller = new App\Controllers\InventoryController();
    $controller->edit($id);
}
elseif (strpos($request, '/inventory/delete/') === 0) {
    $id = (int) substr($request, strlen('/inventory/delete/'));
    $controller = new App\Controllers\InventoryController();
    $controller->delete($id);
}

// ============================================================
// 💰 SALES ROUTES
// ============================================================
elseif ($request === '/sales') {
    $controller = new App\Controllers\SalesController();
    $controller->index();
}
elseif ($request === '/sales/create') {
    $controller = new App\Controllers\SalesController();
    $controller->create();
}
elseif ($request === '/sales/store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\SalesController();
    $controller->store();
}
elseif ($request === '/sales/pending') {
    $controller = new App\Controllers\SalesController();
    $controller->pending();
}
elseif ($request === '/sales/add-payment' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\SalesController();
    $controller->addPayment();
}
elseif ($request === '/sales/chart-data') {
    $controller = new App\Controllers\SalesController();
    $controller->chartData();
}
elseif ($request === '/sales/search-parts') {
    $controller = new App\Controllers\SalesController();
    $controller->searchParts();
}
elseif (strpos($request, '/sales/edit/') === 0) {
    $id = (int) substr($request, strlen('/sales/edit/'));
    $controller = new App\Controllers\SalesController();
    $controller->edit($id);
}
elseif ($request === '/sales/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\SalesController();
    $controller->update();
}
elseif (strpos($request, '/sales/view/') === 0) {
    $id = (int) substr($request, strlen('/sales/view/'));
    $controller = new App\Controllers\SalesController();
    $controller->viewInvoice($id);
}
elseif (strpos($request, '/sales/print/') === 0) {
    $id = (int) substr($request, strlen('/sales/print/'));
    $controller = new App\Controllers\SalesController();
    $controller->printInvoice($id);
}
elseif (strpos($request, '/sales/pdf/') === 0) {
    $id = (int) substr($request, strlen('/sales/pdf/'));
    $controller = new App\Controllers\SalesController();
    $controller->exportPDF($id);
}
elseif (strpos($request, '/sales/whatsapp/') === 0) {
    $id = (int) substr($request, strlen('/sales/whatsapp/'));
    $controller = new App\Controllers\SalesController();
    $controller->sendWhatsAppInvoice($id);
}

// ============================================================
// 📊 INSTALLMENTS ROUTES
// ============================================================
elseif ($request === '/installments') {
    $controller = new App\Controllers\InstallmentController();
    $controller->index();
}
elseif ($request === '/installments/create') {
    $controller = new App\Controllers\InstallmentController();
    $controller->create();
}
elseif ($request === '/installments/store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\InstallmentController();
    $controller->store();
}
elseif ($request === '/installments/add-payment' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\InstallmentController();
    $controller->addPayment();
}
elseif ($request === '/installments/overdue') {
    $controller = new App\Controllers\InstallmentController();
    $controller->overdue();
}
elseif (strpos($request, '/installments/show/') === 0) {
    $id = (int) substr($request, strlen('/installments/show/'));
    $controller = new App\Controllers\InstallmentController();
    $controller->viewInstallment($id);
}
elseif (strpos($request, '/installments/delete/') === 0) {
    $id = (int) substr($request, strlen('/installments/delete/'));
    $controller = new App\Controllers\InstallmentController();
    $controller->delete($id);
}

// ============================================================
// 💳 WALLETS ROUTES
// ============================================================
elseif ($request === '/wallets') {
    $controller = new App\Controllers\WalletController();
    $controller->index();
}
elseif ($request === '/wallets/create') {
    $controller = new App\Controllers\WalletController();
    $controller->create();
}
elseif ($request === '/wallets/store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\WalletController();
    $controller->store();
}
elseif ($request === '/wallets/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\WalletController();
    $controller->update();
}
elseif ($request === '/wallets/add-transaction' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\WalletController();
    $controller->addTransaction();
}
elseif ($request === '/wallet/deposit') {
    $controller = new App\Controllers\WalletController();
    $controller->depositForm();
}
elseif ($request === '/wallet/deposit-store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\WalletController();
    $controller->depositStore();
}
elseif (strpos($request, '/wallets/edit/') === 0) {
    $id = (int) substr($request, strlen('/wallets/edit/'));
    $controller = new App\Controllers\WalletController();
    $controller->edit($id);
}
elseif (strpos($request, '/wallets/delete/') === 0) {
    $id = (int) substr($request, strlen('/wallets/delete/'));
    $controller = new App\Controllers\WalletController();
    $controller->delete($id);
}
elseif (strpos($request, '/wallets/transactions/') === 0) {
    $id = (int) substr($request, strlen('/wallets/transactions/'));
    $controller = new App\Controllers\WalletController();
    $controller->transactions($id);
}

// ============================================================
// 💸 EXPENSES ROUTES
// ============================================================
elseif ($request === '/expenses') {
    $controller = new App\Controllers\ExpenseController();
    $controller->index();
}
elseif ($request === '/expenses/create') {
    $controller = new App\Controllers\ExpenseController();
    $controller->create();
}
elseif ($request === '/expenses/store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\ExpenseController();
    $controller->store();
}

// ============================================================
// ⏰ ATTENDANCE ROUTES
// ============================================================
elseif ($request === '/attendance') {
    $controller = new App\Controllers\AttendanceController();
    $controller->index();
}
elseif ($request === '/attendance/checkin' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\AttendanceController();
    $controller->checkIn();
}
elseif ($request === '/attendance/checkout' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\AttendanceController();
    $controller->checkOut();
}
elseif ($request === '/attendance/edit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\AttendanceController();
    $controller->edit();
}
elseif ($request === '/attendance/report') {
    $controller = new App\Controllers\AttendanceController();
    $controller->report();
}
elseif ($request === '/attendance/calendar') {
    $controller = new App\Controllers\AttendanceController();
    $controller->calendar();
}
elseif ($request === '/attendance/export-csv') {
    $controller = new App\Controllers\AttendanceController();
    $controller->exportCsv();
}

// ============================================================
// ⚙️ SETTINGS ROUTES (المعدلة)
// ============================================================
elseif ($request === '/settings/work') {
    $controller = new App\Controllers\SettingsController();
    $controller->work();
}
elseif ($request === '/settings/work/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\SettingsController();
    $controller->updateWork();
}
elseif ($request === '/settings/shifts') {
    $controller = new App\Controllers\SettingsController();
    $controller->shifts();
}
elseif ($request === '/settings/shifts/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\SettingsController();
    $controller->updateShifts();
}
elseif (strpos($request, '/settings/get/') === 0) {
    $key = substr($request, strlen('/settings/get/'));
    $controller = new App\Controllers\SettingsController();
    $controller->get($key);
}

// ============================================================
// 👥 USERS ROUTES
// ============================================================
elseif ($request === '/users') {
    $controller = new App\Controllers\UserController();
    $controller->index();
}
elseif ($request === '/users/create') {
    $controller = new App\Controllers\UserController();
    $controller->create();
}
elseif ($request === '/users/store' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\UserController();
    $controller->store();
}
elseif ($request === '/users/update' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\UserController();
    $controller->update();
}
elseif ($request === '/users/update-permissions' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\UserController();
    $controller->updatePermissions();
}
elseif (strpos($request, '/users/edit/') === 0) {
    $id = (int) substr($request, strlen('/users/edit/'));
    $controller = new App\Controllers\UserController();
    $controller->edit($id);
}
elseif (strpos($request, '/users/delete/') === 0) {
    $id = (int) substr($request, strlen('/users/delete/'));
    $controller = new App\Controllers\UserController();
    $controller->delete($id);
}
elseif (strpos($request, '/users/permissions/') === 0) {
    $id = (int) substr($request, strlen('/users/permissions/'));
    $controller = new App\Controllers\UserController();
    $controller->permissions($id);
}
elseif (strpos($request, '/users/toggle/') === 0) {
    $id = (int) substr($request, strlen('/users/toggle/'));
    $controller = new App\Controllers\UserController();
    $controller->toggle($id);
}

// ============================================================
// 🔍 AUDIT ROUTES
// ============================================================
elseif ($request === '/audit') {
    $controller = new App\Controllers\AuditController();
    $controller->index();
}

// ============================================================
// 💬 CHAT ROUTES
// ============================================================
elseif ($request === '/chat') {
    $controller = new App\Controllers\ChatController();
    $controller->index();
}
elseif ($request === '/chat/messages') {
    $controller = new App\Controllers\ChatController();
    $controller->getMessages();
}
elseif ($request === '/chat/send' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\ChatController();
    $controller->send();
}
elseif ($request === '/chat/unread-all') {
    $controller = new App\Controllers\ChatController();
    $controller->unreadAll();
}
elseif ($request === '/chat/unread-count') {
    $controller = new App\Controllers\ChatController();
    $controller->unreadCount();
}

// ============================================================
// 🔔 NOTIFICATIONS ROUTES
// ============================================================
elseif ($request === '/notifications') {
    $controller = new App\Controllers\NotificationsController();
    $controller->index();
}
elseif ($request === '/notifications/mark-all-read') {
    $controller = new App\Controllers\NotificationsController();
    $controller->markAllRead();
}
elseif ($request === '/notifications/unread-count') {
    $controller = new App\Controllers\NotificationsController();
    $controller->unreadCount();
}
elseif (strpos($request, '/notifications/mark-read/') === 0) {
    $id = (int) substr($request, strlen('/notifications/mark-read/'));
    $controller = new App\Controllers\NotificationsController();
    $controller->markRead($id);
}

// ============================================================
// 📊 REPORTS ROUTES
// ============================================================
elseif ($request === '/reports') {
    $controller = new App\Controllers\ReportsController();
    $controller->index();
}
elseif ($request === '/reports/sales') {
    $controller = new App\Controllers\ReportsController();
    $controller->sales();
}
elseif ($request === '/reports/sales/export-csv') {
    $controller = new App\Controllers\ReportsController();
    $controller->exportSalesCsv();
}
elseif ($request === '/reports/profit') {
    $controller = new App\Controllers\ReportsController();
    $controller->profit();
}
elseif ($request === '/reports/inventory') {
    $controller = new App\Controllers\ReportsController();
    $controller->inventory();
}
elseif ($request === '/reports/technicians') {
    $controller = new App\Controllers\ReportsController();
    $controller->technicians();
}
elseif ($request === '/reports/customers') {
    $controller = new App\Controllers\ReportsController();
    $controller->customers();
}
elseif ($request === '/reports/chart-sales') {
    $controller = new App\Controllers\ReportsController();
    $controller->chartSales();
}
elseif ($request === '/reports/chart-device-status') {
    $controller = new App\Controllers\ReportsController();
    $controller->chartDeviceStatus();
}
elseif ($request === '/reports/chart-technicians') {
    $controller = new App\Controllers\ReportsController();
    $controller->chartTechnicians();
}
elseif ($request === '/reports/chart-popular-devices') {
    $controller = new App\Controllers\ReportsController();
    $controller->chartPopularDevices();
}
elseif ($request === '/reports/chart-profit') {
    $controller = new App\Controllers\ReportsController();
    $controller->chartProfit();
}

// ============================================================
// 🔄 SHIFTS ROUTES
// ============================================================
elseif ($request === '/shifts') {
    $controller = new App\Controllers\ShiftController();
    $controller->index();
}
elseif ($request === '/shift/start') {
    $controller = new App\Controllers\ShiftController();
    $controller->start();
}
elseif ($request === '/shift/end') {
    $controller = new App\Controllers\ShiftController();
    $controller->end();
}
elseif ($request === '/shifts/clean-archive') {
    $controller = new App\Controllers\ShiftController();
    $controller->cleanArchive();
}

// ============================================================
// 🔗 TRACKING ROUTES
// ============================================================
elseif (strpos($request, '/track/') === 0) {
    $code = substr($request, strlen('/track/'));
    $controller = new App\Controllers\TrackingController();
    $controller->show($code);
}

// ============================================================
// 📱 WHATSAPP & CHECKLIST ROUTES
// ============================================================
elseif ($request === '/send-whatsapp' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\DeviceController();
    $controller->sendWhatsApp();
}
elseif (strpos($request, '/checklist/print/') === 0) {
    $id = (int) substr($request, strlen('/checklist/print/'));
    $controller = new App\Controllers\DeviceController();
    $controller->printChecklist($id);
}
elseif (strpos($request, '/checklist/whatsapp/') === 0) {
    $id = (int) substr($request, strlen('/checklist/whatsapp/'));
    $controller = new App\Controllers\DeviceController();
    $controller->sendChecklistWhatsApp($id);
}

// ============================================================
// 💾 BACKUP ROUTE
// ============================================================
elseif ($request === '/backup') {
    include __DIR__ . '/../backup.php';
}

// ============================================================
// 🧪 TEST ROUTE
// ============================================================
elseif ($request === '/test') {
    $controller = new App\Controllers\DeviceController();
    $controller->test();
}

// ============================================================
// 🧮 INVESTMENT ROUTES (معادلة الاستثمار)
// ============================================================
elseif ($request === '/investment') {
    $controller = new App\Controllers\InvestmentController();
    $controller->index();
}
elseif ($request === '/investment/initial-capital' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\InvestmentController();
    $controller->addInitialCapital();
}
elseif ($request === '/investment/bank-deposit' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\InvestmentController();
    $controller->addBankBalance();
}
elseif ($request === '/investment/purchase' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\InvestmentController();
    $controller->purchaseInventory();
}
elseif ($request === '/investment/sell' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\InvestmentController();
    $controller->sellInventory();
}
elseif ($request === '/investment/expense' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\InvestmentController();
    $controller->addExpense();
}
elseif ($request === '/investment/customer-debt' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\InvestmentController();
    $controller->addCustomerDebt();
}
elseif ($request === '/investment/collect-debt' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\InvestmentController();
    $controller->collectCustomerDebt();
}
elseif ($request === '/investment/pay-supplier' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\InvestmentController();
    $controller->paySupplier();
}
elseif ($request === '/investment/partner' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\InvestmentController();
    $controller->storePartner();
}
elseif (strpos($request, '/investment/partner/') === 0 && $_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['edit'])) {
    $id = (int) substr($request, strlen('/investment/partner/'));
    $controller = new App\Controllers\InvestmentController();
    $controller->getPartnerJson($id);
}
elseif (strpos($request, '/investment/partner/') === 0 && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'PUT') {
    $id = (int) substr($request, strlen('/investment/partner/'));
    $controller = new App\Controllers\InvestmentController();
    $controller->updatePartner($id);
}
elseif (strpos($request, '/investment/partner/') === 0 && $_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['_method']) && $_POST['_method'] === 'DELETE') {
    $id = (int) substr($request, strlen('/investment/partner/'));
    $controller = new App\Controllers\InvestmentController();
    $controller->deletePartner($id);
}

// ============================================================
// 🌐 API ROUTES - المعاملات المالية
// ============================================================
elseif ($request === '/api/invoices/credit-sale' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\TransactionController();
    $controller->storeCreditSale();
}
elseif ($request === '/api/invoices/receive-payment' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $controller = new App\Controllers\TransactionController();
    $controller->receivePayment();
}
elseif ($request === '/api/debts/customers' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new App\Controllers\TransactionController();
    $controller->getCustomerDebts();
}
elseif ($request === '/api/debts/suppliers' && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $controller = new App\Controllers\TransactionController();
    $controller->getSupplierDebts();
}
elseif (strpos($request, '/api/entities/') === 0 && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = (int) substr($request, strlen('/api/entities/'));
    $controller = new App\Controllers\TransactionController();
    $controller->getEntityProfile($id);
}
elseif (strpos($request, '/api/entities/') === 0 && strpos($request, '/aging') !== false && $_SERVER['REQUEST_METHOD'] === 'GET') {
    $id = (int) substr($request, strlen('/api/entities/'), strpos($request, '/aging') - strlen('/api/entities/'));
    $controller = new App\Controllers\TransactionController();
    $controller->getEntityAging($id);
}

// ============================================================
// ❌ 404 NOT FOUND
// ============================================================
else {
    http_response_code(404);
    echo "<h1>404 - الصفحة غير موجودة</h1>";
    echo "<p><a href='/'>العودة للرئيسية</a></p>";
}