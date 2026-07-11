<?php
namespace App\Controllers;

class SalesController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    // ============================================================
    // 📋 قائمة الفواتير
    // ============================================================
    public function index()
    {
        $this->requirePermission('view_financial');

        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $filter = isset($_GET['filter']) ? $_GET['filter'] : 'all';

        $sql = "
            SELECT s.*, c.full_name as customer_name, c.phone as customer_phone, u.full_name as created_by_name
            FROM sales s
            LEFT JOIN customers c ON s.customer_id = c.id
            LEFT JOIN users u ON s.user_id = u.id
            WHERE s.deleted_at IS NULL
        ";
        $params = [];

        if (!empty($search) && strlen($search) >= 2) {
            $like = '%' . $search . '%';
            $sql .= " AND (c.full_name LIKE ? OR c.phone LIKE ? OR s.invoice_number LIKE ?)";
            $params = array_merge($params, [$like, $like, $like]);
        } elseif (!empty($search) && strlen($search) < 2) {
            $sql .= " AND 1=0";
        }

        if ($filter === 'pending') {
            $sql .= " AND s.status = 'pending'";
        } elseif ($filter === 'completed') {
            $sql .= " AND s.status = 'completed'";
        } elseif ($filter === 'partially') {
            $sql .= " AND s.status = 'partially_paid'";
        }

        $sql .= " ORDER BY s.id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $sales = $stmt->fetchAll();

        $stmt = $this->db->query("
            SELECT 
                COUNT(*) as total,
                COALESCE(SUM(total_amount), 0) as total_amount,
                COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_count,
                COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_count
            FROM sales WHERE deleted_at IS NULL
        ");
        $stats = $stmt->fetch();

        $this->view('sales/index', [
            'title' => 'الفواتير',
            'sales' => $sales,
            'stats' => $stats,
            'filter' => $filter,
            'search' => $search
        ]);
    }

    // ============================================================
    // ➕ نموذج فاتورة جديدة
    // ============================================================
    public function create()
    {
        $deviceId = isset($_GET['device_id']) ? (int) $_GET['device_id'] : null;
        $suggestedItems = [];

        if ($deviceId) {
            $stmt = $this->db->prepare("
                SELECT d.*, c.full_name as customer_name 
                FROM devices d
                LEFT JOIN customers c ON d.customer_id = c.id
                WHERE d.id = ? AND d.deleted_at IS NULL
            ");
            $stmt->execute([$deviceId]);
            $device = $stmt->fetch();

            if ($device) {
                $suggestedItems[] = [
                    'type' => 'service',
                    'description' => 'خدمة صيانة: ' . $device['reported_issue'],
                    'quantity' => 1,
                    'unit_price' => 0,
                ];

                $stmt = $this->db->prepare("
                    SELECT description 
                    FROM device_maintenance_log 
                    WHERE device_id = ? AND action LIKE '%part%' 
                    ORDER BY performed_at DESC LIMIT 5
                ");
                $stmt->execute([$deviceId]);
                $logs = $stmt->fetchAll();
                foreach ($logs as $log) {
                    if (preg_match('/قطعة غيار: (.+)/', $log['description'], $matches)) {
                        $suggestedItems[] = [
                            'type' => 'part',
                            'description' => 'قطعة غيار: ' . $matches[1],
                            'quantity' => 1,
                            'unit_price' => 0,
                        ];
                    }
                }
            }
        }

        $stmt = $this->db->query("SELECT id, wallet_name FROM wallets WHERE is_active = 1");
        $wallets = $stmt->fetchAll();

        $this->view('sales/create', [
            'title' => 'فاتورة جديدة',
            'wallets' => $wallets,
            'suggestedItems' => $suggestedItems,
            'deviceId' => $deviceId
        ]);
    }

    // ============================================================
    // 💾 حفظ فاتورة جديدة
    // ============================================================
    public function store()
    {
        $customer_name = isset($_POST['customer_name']) ? trim($_POST['customer_name']) : '';
        $customer_phone = isset($_POST['customer_phone']) ? trim($_POST['customer_phone']) : '';
        $customer_id = null;
        $device_id = isset($_POST['device_id']) ? (int) $_POST['device_id'] : null;

        if (!empty($customer_name) || !empty($customer_phone)) {
            $stmt = $this->db->prepare("SELECT id FROM customers WHERE phone = ? OR full_name = ?");
            $stmt->execute([$customer_phone, $customer_name]);
            $existing = $stmt->fetch();
            if ($existing) {
                $customer_id = $existing['id'];
                if (!empty($customer_name)) {
                    $stmt = $this->db->prepare("UPDATE customers SET full_name = ? WHERE id = ?");
                    $stmt->execute([$customer_name, $customer_id]);
                }
            } else {
                $stmt = $this->db->prepare("INSERT INTO customers (full_name, phone) VALUES (?, ?)");
                $stmt->execute([$customer_name, $customer_phone]);
                $customer_id = $this->db->lastInsertId();
            }
        }

        $payment_method = isset($_POST['payment_method']) ? $_POST['payment_method'] : 'cash';
        $wallet_id = isset($_POST['wallet_id']) && !empty($_POST['wallet_id']) ? (int) $_POST['wallet_id'] : null;
        $notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';
        $itemsJson = isset($_POST['items_json']) ? $_POST['items_json'] : '[]';
        $subtotal = isset($_POST['subtotal']) ? (float) $_POST['subtotal'] : 0;
        $discount = isset($_POST['discount']) ? (float) $_POST['discount'] : 0;
        $total_amount = isset($_POST['total_amount']) ? (float) $_POST['total_amount'] : 0;
        $paid_amount = isset($_POST['paid_amount']) ? (float) $_POST['paid_amount'] : 0;
        $remaining_amount = isset($_POST['remaining_amount']) ? (float) $_POST['remaining_amount'] : 0;

        $items = json_decode($itemsJson, true);
        if (empty($items)) {
            $this->redirect('/sales/create', 'يجب إضافة صنف واحد على الأقل', 'error');
            return;
        }

        $invoice_number = 'INV-' . date('Y') . '-' . str_pad(rand(1, 99999), 5, '0', STR_PAD_LEFT);

        try {
            $this->db->beginTransaction();

            if ($remaining_amount <= 0) {
                $status = 'completed';
            } elseif ($paid_amount > 0) {
                $status = 'partially_paid';
            } else {
                $status = 'pending';
            }

            $stmt = $this->db->prepare("
                INSERT INTO sales (
                    invoice_number, customer_id, user_id, sale_date, subtotal, discount, tax,
                    total_amount, paid_amount, remaining_amount, payment_method, wallet_id, status, notes, created_at
                ) VALUES (?, ?, ?, NOW(), ?, ?, 0, ?, ?, ?, ?, ?, ?, ?, NOW())
            ");
            
            $stmt->execute([
                $invoice_number, $customer_id, $this->userId,
                $subtotal, $discount, $total_amount, $paid_amount, $remaining_amount,
                $payment_method, $wallet_id, $status, $notes
            ]);

            $saleId = $this->db->lastInsertId();

            foreach ($items as $item) {
                $inventory_id = isset($item['inventory_id']) ? (int) $item['inventory_id'] : null;
                $description = $item['description'];
                $quantity = (int) $item['quantity'];
                $unit_price = (float) $item['unit_price'];
                $total_price = $quantity * $unit_price;

                $stmt = $this->db->prepare("
                    INSERT INTO sale_items (sale_id, item_type, inventory_id, description, quantity, unit_price, total_price)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$saleId, $item['type'], $inventory_id, $description, $quantity, $unit_price, $total_price]);

                if ($item['type'] == 'part' && $inventory_id) {
                    $stmt = $this->db->prepare("UPDATE inventory SET current_quantity = current_quantity - ? WHERE id = ? AND current_quantity >= ?");
                    $stmt->execute([$quantity, $inventory_id, $quantity]);
                    if ($stmt->rowCount() == 0) {
                        throw new \Exception("الكمية غير متوفرة في المخزون للقطعة: $description");
                    }
                }
            }

            if ($device_id) {
                $stmt = $this->db->prepare("UPDATE devices SET sale_id = ? WHERE id = ?");
                $stmt->execute([$saleId, $device_id]);
            }

            $this->audit->logCreate('sales', $saleId, [
                'invoice_number' => $invoice_number,
                'customer_id' => $customer_id,
                'total_amount' => $total_amount,
                'payment_method' => $payment_method,
                'status' => $status
            ]);

            $this->db->commit();
            $this->redirect("/sales/view/" . $saleId, 'تم إنشاء الفاتورة بنجاح', 'success');

        } catch (\Exception $e) {
            $this->db->rollBack();
            $this->redirect('/sales/create', 'خطأ: ' . $e->getMessage(), 'error');
        }
    }

    // ============================================================
    // 👁️ عرض تفاصيل الفاتورة
    // ============================================================
    public function viewInvoice($id)
    {
        $stmt = $this->db->prepare("
            SELECT s.*, c.full_name as customer_name, c.phone as customer_phone, u.full_name as created_by_name
            FROM sales s
            LEFT JOIN customers c ON s.customer_id = c.id
            LEFT JOIN users u ON s.user_id = u.id
            WHERE s.id = ? AND s.deleted_at IS NULL
        ");
        $stmt->execute([$id]);
        $sale = $stmt->fetch();

        if (!$sale) {
            die("<h1>الفاتورة غير موجودة</h1><a href='/sales'>العودة</a>");
        }

        $stmt = $this->db->prepare("
            SELECT si.*, i.name as inventory_name
            FROM sale_items si
            LEFT JOIN inventory i ON si.inventory_id = i.id
            WHERE si.sale_id = ?
        ");
        $stmt->execute([$id]);
        $items = $stmt->fetchAll();

        $stmt = $this->db->prepare("
            SELECT id, device_code, brand, model, customer_id
            FROM devices
            WHERE sale_id = ? AND deleted_at IS NULL
        ");
        $stmt->execute([$id]);
        $devices = $stmt->fetchAll();

        $this->view('sales/view', [
            'title' => 'تفاصيل الفاتورة',
            'sale' => $sale,
            'items' => $items,
            'devices' => $devices
        ]);
    }

    // ============================================================
    // 🖨️ طباعة الفاتورة
    // ============================================================
    public function printInvoice($id)
    {
        $stmt = $this->db->prepare("
            SELECT s.*, 
                   c.full_name as customer_name, 
                   c.phone as customer_phone,
                   u.full_name as created_by_name
            FROM sales s
            LEFT JOIN customers c ON s.customer_id = c.id
            LEFT JOIN users u ON s.user_id = u.id
            WHERE s.id = ? AND s.deleted_at IS NULL
        ");
        $stmt->execute([$id]);
        $sale = $stmt->fetch();

        if (!$sale) {
            die("⚠️ الفاتورة غير موجودة");
        }

        $stmt = $this->db->prepare("
            SELECT si.*, i.name as inventory_name
            FROM sale_items si
            LEFT JOIN inventory i ON si.inventory_id = i.id
            WHERE si.sale_id = ?
        ");
        $stmt->execute([$id]);
        $items = $stmt->fetchAll();

        $this->view('sales/print_invoice', [
            'title' => 'طباعة فاتورة',
            'sale' => $sale,
            'items' => $items
        ]);
    }

    // ============================================================
    // ✏️ تعديل الفاتورة (عرض النموذج)
    // ============================================================
    public function edit($id)
    {
        $this->requirePermission('edit_invoices');

        $stmt = $this->db->prepare("
            SELECT s.*, c.full_name as customer_name, c.phone as customer_phone
            FROM sales s
            LEFT JOIN customers c ON s.customer_id = c.id
            WHERE s.id = ? AND s.deleted_at IS NULL
        ");
        $stmt->execute([$id]);
        $sale = $stmt->fetch();

        if (!$sale) {
            $this->redirect('/sales', 'الفاتورة غير موجودة', 'error');
            return;
        }

        if ($sale['status'] == 'completed') {
            $this->redirect("/sales/view/{$id}", '⚠️ لا يمكن تعديل فاتورة مكتملة', 'error');
            return;
        }

        $stmt = $this->db->prepare("
            SELECT si.*, i.name as inventory_name
            FROM sale_items si
            LEFT JOIN inventory i ON si.inventory_id = i.id
            WHERE si.sale_id = ?
        ");
        $stmt->execute([$id]);
        $items = $stmt->fetchAll();

        $stmt = $this->db->query("SELECT id, wallet_name FROM wallets WHERE is_active = 1");
        $wallets = $stmt->fetchAll();

        $this->view('sales/edit', [
            'title' => 'تعديل الفاتورة',
            'sale' => $sale,
            'items' => $items,
            'wallets' => $wallets
        ]);
    }

    // ============================================================
    // 💾 تحديث الفاتورة (حفظ التعديلات)
    // ============================================================
    public function update()
    {
        $this->requirePermission('edit_invoices');

        $saleId = (int) $_POST['sale_id'];
        $customer_name = trim($_POST['customer_name']);
        $customer_phone = trim($_POST['customer_phone']);
        $payment_method = $_POST['payment_method'] ?? 'cash';
        $wallet_id = isset($_POST['wallet_id']) && !empty($_POST['wallet_id']) ? (int) $_POST['wallet_id'] : null;
        $notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';
        $itemsJson = isset($_POST['items_json']) ? $_POST['items_json'] : '[]';
        $subtotal = (float) $_POST['subtotal'];
        $discount = (float) $_POST['discount'];
        $total_amount = (float) $_POST['total_amount'];
        $paid_amount = (float) $_POST['paid_amount'];
        $remaining_amount = (float) $_POST['remaining_amount'];

        $stmt = $this->db->prepare("SELECT * FROM sales WHERE id = ? AND deleted_at IS NULL");
        $stmt->execute([$saleId]);
        $oldSale = $stmt->fetch();

        if (!$oldSale) {
            $this->redirect('/sales', 'الفاتورة غير موجودة', 'error');
            return;
        }

        if ($oldSale['status'] == 'completed') {
            $this->redirect("/sales/view/{$saleId}", '⚠️ لا يمكن تعديل فاتورة مكتملة', 'error');
            return;
        }

        $items = json_decode($itemsJson, true);
        if (empty($items)) {
            $this->redirect("/sales/edit/{$saleId}", 'يجب إضافة صنف واحد على الأقل', 'error');
            return;
        }

        try {
            $this->db->beginTransaction();

            // استرجاع الكميات القديمة من المخزون
            $stmt = $this->db->prepare("SELECT * FROM sale_items WHERE sale_id = ?");
            $stmt->execute([$saleId]);
            $oldItems = $stmt->fetchAll();

            foreach ($oldItems as $oldItem) {
                if ($oldItem['item_type'] == 'part' && $oldItem['inventory_id']) {
                    $stmt = $this->db->prepare("
                        UPDATE inventory 
                        SET current_quantity = current_quantity + ? 
                        WHERE id = ?
                    ");
                    $stmt->execute([$oldItem['quantity'], $oldItem['inventory_id']]);
                }
            }

            // حذف الأصناف القديمة
            $stmt = $this->db->prepare("DELETE FROM sale_items WHERE sale_id = ?");
            $stmt->execute([$saleId]);

            // إضافة الأصناف الجديدة
            foreach ($items as $item) {
                $inventory_id = isset($item['inventory_id']) ? (int) $item['inventory_id'] : null;
                $description = $item['description'];
                $quantity = (int) $item['quantity'];
                $unit_price = (float) $item['unit_price'];
                $total_price = $quantity * $unit_price;

                $stmt = $this->db->prepare("
                    INSERT INTO sale_items (sale_id, item_type, inventory_id, description, quantity, unit_price, total_price)
                    VALUES (?, ?, ?, ?, ?, ?, ?)
                ");
                $stmt->execute([$saleId, $item['type'], $inventory_id, $description, $quantity, $unit_price, $total_price]);

                if ($item['type'] == 'part' && $inventory_id) {
                    $stmt = $this->db->prepare("
                        UPDATE inventory 
                        SET current_quantity = current_quantity - ? 
                        WHERE id = ? AND current_quantity >= ?
                    ");
                    $stmt->execute([$quantity, $inventory_id, $quantity]);
                    if ($stmt->rowCount() == 0) {
                        throw new \Exception("الكمية غير متوفرة في المخزون للقطعة: $description");
                    }
                }
            }

            // تحديث العميل
            $customer_id = null;
            if (!empty($customer_name) || !empty($customer_phone)) {
                $stmt = $this->db->prepare("SELECT id FROM customers WHERE phone = ? OR full_name = ?");
                $stmt->execute([$customer_phone, $customer_name]);
                $existing = $stmt->fetch();
                if ($existing) {
                    $customer_id = $existing['id'];
                    if (!empty($customer_name)) {
                        $stmt = $this->db->prepare("UPDATE customers SET full_name = ? WHERE id = ?");
                        $stmt->execute([$customer_name, $customer_id]);
                    }
                } else {
                    $stmt = $this->db->prepare("INSERT INTO customers (full_name, phone) VALUES (?, ?)");
                    $stmt->execute([$customer_name, $customer_phone]);
                    $customer_id = $this->db->lastInsertId();
                }
            }

            // تحديث الفاتورة
            if ($remaining_amount <= 0) {
                $status = 'completed';
            } elseif ($paid_amount > 0) {
                $status = 'partially_paid';
            } else {
                $status = 'pending';
            }

            $stmt = $this->db->prepare("
                UPDATE sales 
                SET customer_id = ?, 
                    subtotal = ?, 
                    discount = ?, 
                    total_amount = ?, 
                    paid_amount = ?, 
                    remaining_amount = ?, 
                    payment_method = ?, 
                    wallet_id = ?, 
                    status = ?, 
                    notes = ?, 
                    updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([
                $customer_id,
                $subtotal,
                $discount,
                $total_amount,
                $paid_amount,
                $remaining_amount,
                $payment_method,
                $wallet_id,
                $status,
                $notes,
                $saleId
            ]);

            $this->audit->logUpdate('sales', $saleId, [
                'old_total' => $oldSale['total_amount'],
                'old_status' => $oldSale['status']
            ], [
                'new_total' => $total_amount,
                'new_status' => $status
            ]);

            $this->db->commit();
            $this->redirect("/sales/view/{$saleId}", '✅ تم تحديث الفاتورة بنجاح', 'success');

        } catch (\Exception $e) {
            $this->db->rollBack();
            $this->redirect("/sales/edit/{$saleId}", 'خطأ: ' . $e->getMessage(), 'error');
        }
    }

    // ============================================================
    // 📋 الفواتير المعلقة
    // ============================================================
    public function pending()
    {
        $this->requirePermission('view_financial');
        $stmt = $this->db->prepare("
            SELECT s.*, c.full_name as customer_name, c.phone as customer_phone
            FROM sales s
            LEFT JOIN customers c ON s.customer_id = c.id
            WHERE s.status IN ('pending', 'partially_paid') AND s.deleted_at IS NULL
            ORDER BY s.id ASC
        ");
        $stmt->execute();
        $sales = $stmt->fetchAll();
        
        $stmt = $this->db->query("
            SELECT COALESCE(SUM(remaining_amount), 0) as total_remaining
            FROM sales 
            WHERE status IN ('pending', 'partially_paid') AND deleted_at IS NULL
        ");
        $totalRemaining = $stmt->fetch()['total_remaining'] ?? 0;

        $this->view('sales/pending', [
            'title' => 'الفواتير المعلقة',
            'sales' => $sales,
            'totalRemaining' => $totalRemaining
        ]);
    }

    // ============================================================
    // 💰 إضافة دفعة
    // ============================================================
    public function addPayment()
    {
        $saleId = (int) $_POST['sale_id'];
        $amount = (float) $_POST['amount'];
        $payment_method = $_POST['payment_method'] ?? 'cash';
        $notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';

        if ($amount <= 0) {
            $this->redirect('/sales/pending', 'المبلغ يجب أن يكون أكبر من صفر', 'error');
            return;
        }

        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("SELECT * FROM sales WHERE id = ? AND deleted_at IS NULL");
            $stmt->execute([$saleId]);
            $sale = $stmt->fetch();

            if (!$sale) {
                throw new \Exception('الفاتورة غير موجودة');
            }

            $newPaid = $sale['paid_amount'] + $amount;
            $newRemaining = $sale['total_amount'] - $newPaid;

            if ($newRemaining < 0) {
                throw new \Exception('المبلغ يتجاوز المتبقي من الفاتورة');
            }

            $status = 'pending';
            if ($newRemaining <= 0) {
                $status = 'completed';
            } elseif ($newPaid > 0) {
                $status = 'partially_paid';
            }

            $stmt = $this->db->prepare("
                UPDATE sales 
                SET paid_amount = ?, remaining_amount = ?, status = ?, updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$newPaid, $newRemaining, $status, $saleId]);

            $stmt = $this->db->prepare("
                INSERT INTO sale_payments (sale_id, amount, payment_method, payment_date, notes, created_by)
                VALUES (?, ?, ?, NOW(), ?, ?)
            ");
            $stmt->execute([$saleId, $amount, $payment_method, $notes, $this->userId]);

            $this->audit->logUpdate('sales', $saleId, 
                ['paid_amount' => $sale['paid_amount'], 'status' => $sale['status']],
                ['paid_amount' => $newPaid, 'status' => $status]
            );

            $this->db->commit();
            $this->redirect("/sales/view/" . $saleId, 'تم تسجيل الدفعة بنجاح', 'success');

        } catch (\Exception $e) {
            $this->db->rollBack();
            $this->redirect('/sales/pending', 'خطأ: ' . $e->getMessage(), 'error');
        }
    }

    // ============================================================
    // 📊 بيانات الرسم البياني
    // ============================================================
    public function chartData()
    {
        $period = isset($_GET['period']) ? $_GET['period'] : 'month';
        
        if ($period === 'week') {
            $sql = "
                SELECT DATE(sale_date) as date, COALESCE(SUM(total_amount), 0) as total
                FROM sales
                WHERE sale_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
                  AND status = 'completed' AND deleted_at IS NULL
                GROUP BY DATE(sale_date)
                ORDER BY date ASC
            ";
        } elseif ($period === 'month') {
            $sql = "
                SELECT DATE(sale_date) as date, COALESCE(SUM(total_amount), 0) as total
                FROM sales
                WHERE MONTH(sale_date) = MONTH(CURDATE())
                  AND YEAR(sale_date) = YEAR(CURDATE())
                  AND status = 'completed' AND deleted_at IS NULL
                GROUP BY DATE(sale_date)
                ORDER BY date ASC
            ";
        } else {
            $sql = "
                SELECT DATE_FORMAT(sale_date, '%Y-%m') as date, COALESCE(SUM(total_amount), 0) as total
                FROM sales
                WHERE YEAR(sale_date) = YEAR(CURDATE())
                  AND status = 'completed' AND deleted_at IS NULL
                GROUP BY DATE_FORMAT(sale_date, '%Y-%m')
                ORDER BY date ASC
            ";
        }
        
        $stmt = $this->db->query($sql);
        $data = $stmt->fetchAll();
        
        $this->json($data);
    }

    // ============================================================
    // 📊 ملخص المبيعات
    // ============================================================
    public function summary()
    {
        $this->requirePermission('view_financial');

        $stmt = $this->db->query("
            SELECT 
                COUNT(*) as total_orders,
                COALESCE(SUM(total_amount), 0) as total_sales,
                COALESCE(SUM(paid_amount), 0) as total_paid,
                COALESCE(SUM(remaining_amount), 0) as total_remaining,
                COUNT(CASE WHEN status = 'completed' THEN 1 END) as completed_orders,
                COUNT(CASE WHEN status = 'pending' THEN 1 END) as pending_orders,
                COUNT(CASE WHEN status = 'partially_paid' THEN 1 END) as partially_paid_orders
            FROM sales
            WHERE deleted_at IS NULL
        ");
        $stats = $stmt->fetch();

        $stmt = $this->db->query("
            SELECT 
                COALESCE(SUM(total_amount), 0) as today_sales,
                COUNT(*) as today_orders
            FROM sales
            WHERE DATE(sale_date) = CURDATE() AND deleted_at IS NULL
        ");
        $today = $stmt->fetch();

        $stmt = $this->db->query("
            SELECT 
                COALESCE(SUM(total_amount), 0) as month_sales,
                COUNT(*) as month_orders
            FROM sales
            WHERE MONTH(sale_date) = MONTH(CURDATE()) 
              AND YEAR(sale_date) = YEAR(CURDATE())
              AND deleted_at IS NULL
        ");
        $month = $stmt->fetch();

        $stmt = $this->db->query("
            SELECT 
                c.full_name,
                c.phone,
                COUNT(s.id) as orders,
                COALESCE(SUM(s.total_amount), 0) as total
            FROM sales s
            LEFT JOIN customers c ON s.customer_id = c.id
            WHERE s.deleted_at IS NULL AND s.status = 'completed'
            GROUP BY s.customer_id
            ORDER BY total DESC
            LIMIT 5
        ");
        $topCustomers = $stmt->fetchAll();

        $this->view('sales/summary', [
            'title' => 'إجمالي المبيعات',
            'stats' => $stats,
            'today' => $today,
            'month' => $month,
            'topCustomers' => $topCustomers
        ]);
    }

    // ============================================================
    // 🔍 البحث عن قطع غيار للفاتورة
    // ============================================================
    public function searchParts()
    {
        $query = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        if (strlen($query) < 2) {
            $this->json([]);
            return;
        }

        $stmt = $this->db->prepare("
            SELECT id, name, selling_price, current_quantity
            FROM inventory
            WHERE (name LIKE ? OR barcode LIKE ?) AND current_quantity > 0 AND deleted_at IS NULL
            ORDER BY name ASC LIMIT 15
        ");
        $like = '%' . $query . '%';
        $stmt->execute([$like, $like]);
        $results = $stmt->fetchAll();
        
        $this->json($results);
    }

    // ============================================================
    // 📤 تصدير PDF (قريباً)
    // ============================================================
    public function exportPDF($id)
    {
        $this->redirect("/sales/view/{$id}", 'جاري التطوير...', 'info');
    }

    // ============================================================
    // 📱 إرسال الفاتورة عبر واتساب
    // ============================================================
    public function sendWhatsAppInvoice($id)
    {
        $stmt = $this->db->prepare("
            SELECT s.*, c.full_name as customer_name, c.phone as customer_phone
            FROM sales s
            LEFT JOIN customers c ON s.customer_id = c.id
            WHERE s.id = ? AND s.deleted_at IS NULL
        ");
        $stmt->execute([$id]);
        $sale = $stmt->fetch();

        if (!$sale) {
            $this->redirect('/sales', 'الفاتورة غير موجودة', 'error');
            return;
        }

        $phone = $sale['customer_phone'] ?? '';
        if (empty($phone)) {
            $this->redirect("/sales/view/{$id}", 'رقم الهاتف غير مسجل للعميل', 'error');
            return;
        }

        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (strpos($phone, '0') === 0) {
            $phone = '20' . substr($phone, 1);
        } elseif (strpos($phone, '20') !== 0 && strlen($phone) == 11) {
            $phone = '20' . substr($phone, 1);
        }

        $statusText = '';
        if ($sale['status'] == 'completed') {
            $statusText = '✅ مدفوعة';
        } elseif ($sale['status'] == 'pending') {
            $statusText = '⏳ معلقة';
        } else {
            $statusText = '💰 مدفوع جزئي';
        }

        $message = "📋 *فاتورة جديدة من مركز الصيانة*\n\n";
        $message .= "📄 رقم الفاتورة: *" . $sale['invoice_number'] . "*\n";
        $message .= "👤 العميل: " . ($sale['customer_name'] ?? 'عميل نقدي') . "\n";
        $message .= "💰 الإجمالي: *" . number_format($sale['total_amount'], 2) . " جنيه*\n";
        $message .= "💳 المدفوع: " . number_format($sale['paid_amount'] ?? 0, 2) . " جنيه\n";
        $message .= "📊 المتبقي: " . number_format($sale['remaining_amount'] ?? 0, 2) . " جنيه\n";
        $message .= "📌 الحالة: " . $statusText . "\n";
        $message .= "\n📅 " . date('Y-m-d h:i A', strtotime($sale['sale_date'])) . "\n";
        $message .= "\nشكراً لثقتكم بنا 🙏";

        $whatsappUrl = "https://wa.me/{$phone}?text=" . urlencode($message);
        header("Location: $whatsappUrl");
        exit;
    }
}