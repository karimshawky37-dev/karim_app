<?php
namespace App\Controllers;

class InventoryController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

        $sql = "
            SELECT i.*, s.name as supplier_name
            FROM inventory i
            LEFT JOIN suppliers s ON i.supplier_id = s.id
            WHERE i.deleted_at IS NULL
        ";
        $params = [];

        if (!empty($searchTerm) && strlen($searchTerm) >= 2) {
            $like = '%' . $searchTerm . '%';
            $sql .= " AND (i.name LIKE ? OR i.category LIKE ? OR i.barcode LIKE ? OR s.name LIKE ?)";
            $params = [$like, $like, $like, $like];
        } elseif (!empty($searchTerm) && strlen($searchTerm) < 2) {
            $sql .= " AND 1=0";
        }

        $sql .= " ORDER BY i.name ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $items = $stmt->fetchAll();

        $totalValue = 0;
        $lowStockCount = 0;
        foreach ($items as $item) {
            $totalValue += $item['current_quantity'] * $item['purchase_price'];
            if ($item['current_quantity'] <= $item['alert_quantity']) {
                $lowStockCount++;
            }
        }

        $this->view('inventory/index', [
            'title' => 'المخزون',
            'items' => $items,
            'totalValue' => $totalValue,
            'lowStockCount' => $lowStockCount,
            'searchTerm' => $searchTerm
        ]);
    }

    public function create()
    {
        $this->view('inventory/create_bulk', [
            'title' => 'إضافة صنف جديد'
        ]);
    }

    public function store()
    {
        $invoice_number = trim($_POST['invoice_number'] ?? 'INV-' . date('Ymd') . '-' . rand(100, 999));
        $supplier_name = trim($_POST['supplier_name'] ?? '');
        $invoice_date = $_POST['invoice_date'] ?? date('Y-m-d');

        $items = [];
        foreach ($_POST['items'] as $item) {
            if (!empty($item['name']) && !empty($item['category']) && $item['purchase_price'] > 0) {
                $items[] = [
                    'name' => trim($item['name']),
                    'category' => trim($item['category']),
                    'quantity' => (int) ($item['quantity'] ?? 1),
                    'purchase_price' => (float) $item['purchase_price'],
                    'selling_price' => (float) $item['selling_price'],
                    'alert_quantity' => (int) ($item['alert_quantity'] ?? 5),
                    'location' => trim($item['location'] ?? ''),
                    'barcode' => trim($item['barcode'] ?? '')
                ];
            }
        }

        if (empty($items)) {
            $this->redirect('/inventory/create', 'يجب إضافة صنف واحد على الأقل', 'error');
            return;
        }

        try {
            $this->db->beginTransaction();

            $supplier_id = null;
            if (!empty($supplier_name)) {
                $stmt = $this->db->prepare("SELECT id FROM suppliers WHERE name = ?");
                $stmt->execute([$supplier_name]);
                $existing = $stmt->fetch();
                if ($existing) {
                    $supplier_id = $existing['id'];
                } else {
                    $stmt = $this->db->prepare("INSERT INTO suppliers (name, created_at) VALUES (?, NOW())");
                    $stmt->execute([$supplier_name]);
                    $supplier_id = $this->db->lastInsertId();
                }
            }

            $totalItems = count($items);
            $totalQuantity = array_sum(array_column($items, 'quantity'));
            $stmt = $this->db->prepare("
                INSERT INTO purchase_invoices 
                (invoice_number, invoice_date, supplier_id, total_items, quantity, received_quantity, discrepancy, created_by, created_at)
                VALUES (?, ?, ?, ?, ?, ?, 0, ?, NOW())
            ");
            $stmt->execute([$invoice_number, $invoice_date, $supplier_id, $totalItems, $totalQuantity, $totalQuantity, $this->userId]);
            $invoiceId = $this->db->lastInsertId();

            $inserted = 0;
            foreach ($items as $item) {
                $sku = 'SKU-' . date('Ymd') . '-' . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);

                $stmt = $this->db->prepare("
                    INSERT INTO inventory 
                    (sku, barcode, name, category, supplier_id, supplier_name, purchase_price, selling_price, 
                     current_quantity, alert_quantity, location, created_at)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
                ");
                $stmt->execute([
                    $sku,
                    $item['barcode'] ?: null,
                    $item['name'],
                    $item['category'],
                    $supplier_id,
                    $supplier_name,
                    $item['purchase_price'],
                    $item['selling_price'],
                    $item['quantity'],
                    $item['alert_quantity'],
                    $item['location'] ?: null
                ]);

                $itemId = $this->db->lastInsertId();
                $inserted++;

                $movementQty = $item['quantity'] > 0 ? $item['quantity'] : 1;
                $notes = 'فاتورة استلام: ' . $invoice_number;
                $stmt = $this->db->prepare("
                    INSERT INTO inventory_movements 
                    (inventory_id, movement_type, quantity, notes, created_by, created_at)
                    VALUES (?, 'purchase', ?, ?, ?, NOW())
                ");
                $stmt->execute([$itemId, $movementQty, $notes, $this->userId]);

                // 🔥 تحديث الأجهزة المعلقة
                $this->checkWaitingDevices($item['name'], $itemId);
            }

            $this->db->commit();
            $this->redirect('/inventory', '✅ تم إضافة فاتورة استلام رقم ' . $invoice_number . ' بـ ' . $inserted . ' صنف', 'success');

        } catch (\Exception $e) {
            $this->db->rollBack();
            $this->redirect('/inventory/create', 'خطأ: ' . $e->getMessage(), 'error');
        }
    }

    /**
     * ✅ تحديث الأجهزة المعلقة عند توفر القطعة (تحويلها لـ Pending)
     */
    private function checkWaitingDevices($partName, $inventoryId)
    {
        $partName = trim($partName);
        if (empty($partName)) return;

        $searchTerm = '%' . $partName . '%';

        $stmt = $this->db->prepare("
            SELECT id, device_code, assigned_technician_id, waiting_for_part
            FROM devices 
            WHERE waiting_for_part IS NOT NULL 
              AND waiting_for_part != '' 
              AND LOWER(TRIM(waiting_for_part)) LIKE LOWER(TRIM(?))
              AND current_status_id IN (SELECT id FROM device_statuses WHERE slug IN ('suspended', 'pending'))
              AND deleted_at IS NULL
        ");
        $stmt->execute([$searchTerm]);
        $waitingDevices = $stmt->fetchAll();

        if (count($waitingDevices) > 0) {
            // ✅ نغير الحالة لـ pending عشان الفني يبدأ فيه
            $pendingStatus = $this->getStatusIdBySlug('pending');

            foreach ($waitingDevices as $device) {
                // تحديث الجهاز
                $stmt = $this->db->prepare("
                    UPDATE devices 
                    SET current_status_id = ?, waiting_for_part = NULL, updated_at = NOW() 
                    WHERE id = ?
                ");
                $stmt->execute([$pendingStatus, $device['id']]);

                // سجل الصيانة
                $stmt = $this->db->prepare("
                    INSERT INTO device_maintenance_log 
                    (device_id, action, description, performed_by, performed_at, created_at) 
                    VALUES (?, 'parts_arrived', CONCAT('✅ قطعة غيار متوفرة: ', ?), ?, NOW(), NOW())
                ");
                $stmt->execute([$device['id'], $partName, $this->userId]);

                // إشعار للمدير
                $this->sendNotification(1, 'inventory', '📦 قطعة غيار متوفرة',
                    "قطعة '$partName' أصبحت متوفرة. جهاز {$device['device_code']} جاهز للإصلاح",
                    "/devices/{$device['id']}"
                );

                // إشعار للفني
                if ($device['assigned_technician_id']) {
                    $this->sendNotification($device['assigned_technician_id'], 'inventory', '🔧 قطعة غيار متوفرة لجهازك',
                        "قطعة '$partName' أصبحت متوفرة لجهاز {$device['device_code']}.",
                        "/devices/{$device['id']}"
                    );
                }
            }
        }
    }

    private function getStatusIdBySlug($slug)
    {
        $stmt = $this->db->prepare("SELECT id FROM device_statuses WHERE slug = ?");
        $stmt->execute([$slug]);
        $row = $stmt->fetch();
        return $row ? (int) $row['id'] : 1;
    }

    public function edit($id)
    {
        $stmt = $this->db->prepare("
            SELECT i.*, s.name as supplier_name
            FROM inventory i
            LEFT JOIN suppliers s ON i.supplier_id = s.id
            WHERE i.id = ?
        ");
        $stmt->execute([$id]);
        $item = $stmt->fetch();

        if (!$item) {
            die("الصنف غير موجود");
        }

        $stmt = $this->db->query("SELECT id, name FROM suppliers ORDER BY name");
        $suppliers = $stmt->fetchAll();

        $this->view('inventory/edit', [
            'title' => 'تعديل صنف',
            'item' => $item,
            'suppliers' => $suppliers
        ]);
    }

    public function update()
    {
        $id = (int) $_POST['id'];
        $name = trim($_POST['name']);
        $category = trim($_POST['category']);
        $purchase_price = (float) $_POST['purchase_price'];
        $selling_price = (float) $_POST['selling_price'];
        $quantity = (int) $_POST['quantity'];
        $alert_quantity = (int) $_POST['alert_quantity'];
        $location = trim($_POST['location'] ?? '');
        $barcode = trim($_POST['barcode'] ?? '');
        $supplier_id = isset($_POST['supplier_id']) && !empty($_POST['supplier_id']) ? (int) $_POST['supplier_id'] : null;

        $supplier_name = null;
        if ($supplier_id) {
            $stmt = $this->db->prepare("SELECT name FROM suppliers WHERE id = ?");
            $stmt->execute([$supplier_id]);
            $supplier = $stmt->fetch();
            $supplier_name = $supplier['name'] ?? null;
        }

        $stmt = $this->db->prepare("
            UPDATE inventory 
            SET name = ?, category = ?, purchase_price = ?, selling_price = ?, 
                current_quantity = ?, alert_quantity = ?, location = ?, barcode = ?,
                supplier_id = ?, supplier_name = ?, updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->execute([$name, $category, $purchase_price, $selling_price, $quantity, $alert_quantity,
                        $location, $barcode, $supplier_id, $supplier_name, $id]);

        $this->redirect('/inventory', '✅ تم تحديث الصنف', 'success');
    }

    public function delete($id)
    {
        try {
            $this->db->beginTransaction();
            $stmt = $this->db->prepare("DELETE FROM inventory_movements WHERE inventory_id = ?");
            $stmt->execute([$id]);
            $stmt = $this->db->prepare("DELETE FROM inventory WHERE id = ?");
            $stmt->execute([$id]);
            $this->db->commit();
            $this->redirect('/inventory', 'تم حذف الصنف', 'success');
        } catch (\Exception $e) {
            $this->db->rollBack();
            $this->redirect('/inventory', 'خطأ: ' . $e->getMessage(), 'error');
        }
    }

    public function lowStock()
    {
        $stmt = $this->db->prepare("
            SELECT i.*, s.name as supplier_name
            FROM inventory i
            LEFT JOIN suppliers s ON i.supplier_id = s.id
            WHERE i.current_quantity <= i.alert_quantity 
            ORDER BY (i.alert_quantity - i.current_quantity) DESC, i.name ASC
        ");
        $stmt->execute();
        $items = $stmt->fetchAll();

        $this->view('inventory/low_stock', [
            'title' => 'نواقص المخزون',
            'items' => $items
        ]);
    }

    public function autocomplete()
    {
        $query = isset($_GET['q']) ? trim($_GET['q']) : '';
        header('Content-Type: application/json');

        if (strlen($query) < 1) {
            echo json_encode([]);
            return;
        }

        $like = '%' . $query . '%';
        $stmt = $this->db->prepare("
            SELECT id, name, category, current_quantity, location, barcode
            FROM inventory 
            WHERE name LIKE ? OR category LIKE ? OR barcode LIKE ?
            LIMIT 10
        ");
        $stmt->execute([$like, $like, $like]);
        $results = $stmt->fetchAll();

        echo json_encode($results);
    }

    public function forceUpdateWaitingDevices()
    {
        $this->requirePermission('manage_inventory');
        $partName = isset($_POST['part_name']) ? trim($_POST['part_name']) : '';
        if (empty($partName)) {
            $this->redirect('/inventory', '⚠️ اكتب اسم القطعة', 'error');
            return;
        }

        $searchTerm = '%' . $partName . '%';
        $stmt = $this->db->prepare("
            SELECT id, device_code, assigned_technician_id 
            FROM devices 
            WHERE waiting_for_part IS NOT NULL 
              AND waiting_for_part != '' 
              AND LOWER(TRIM(waiting_for_part)) LIKE LOWER(TRIM(?))
              AND current_status_id IN (SELECT id FROM device_statuses WHERE slug IN ('suspended', 'pending'))
              AND deleted_at IS NULL
        ");
        $stmt->execute([$searchTerm]);
        $waitingDevices = $stmt->fetchAll();

        if (count($waitingDevices) == 0) {
            $this->redirect('/inventory', '⚠️ لا توجد أجهزة منتظرة لهذه القطعة', 'warning');
            return;
        }

        $pendingStatus = $this->getStatusIdBySlug('pending');
        $updated = 0;

        foreach ($waitingDevices as $device) {
            $stmt = $this->db->prepare("
                UPDATE devices 
                SET current_status_id = ?, waiting_for_part = NULL, updated_at = NOW() 
                WHERE id = ?
            ");
            $stmt->execute([$pendingStatus, $device['id']]);
            $updated++;

            if ($device['assigned_technician_id']) {
                $this->sendNotification($device['assigned_technician_id'], 'inventory', '🔧 قطعة غيار متوفرة',
                    "قطعة '$partName' متوفرة. جهاز {$device['device_code']} جاهز للإصلاح.",
                    "/devices/{$device['id']}"
                );
            }
        }

        $this->redirect('/inventory', "✅ تم تحديث $updated جهاز (قطعة: $partName)", 'success');
    }

    public function count()
    {
        $this->requirePermission('manage_inventory');
        $stmt = $this->db->query("
            SELECT id, name, category, current_quantity, alert_quantity, location
            FROM inventory
            ORDER BY name ASC
        ");
        $items = $stmt->fetchAll();

        $this->view('inventory/count', [
            'title' => 'جرد المخزون',
            'items' => $items
        ]);
    }

    public function countUpdate()
    {
        $this->requirePermission('manage_inventory');
        $counts = $_POST['count'] ?? [];
        $notes = isset($_POST['notes']) ? trim($_POST['notes']) : 'جرد دوري';

        try {
            $this->db->beginTransaction();
            $differences = [];

            foreach ($counts as $id => $actual) {
                $actual = (int) $actual;
                $stmt = $this->db->prepare("SELECT current_quantity FROM inventory WHERE id = ?");
                $stmt->execute([$id]);
                $current = $stmt->fetch()['current_quantity'] ?? 0;

                if ($actual != $current) {
                    $differences[] = [
                        'id' => $id,
                        'old' => $current,
                        'new' => $actual,
                        'diff' => $actual - $current
                    ];

                    $stmt = $this->db->prepare("UPDATE inventory SET current_quantity = ? WHERE id = ?");
                    $stmt->execute([$actual, $id]);

                    $stmt = $this->db->prepare("
                        INSERT INTO inventory_movements 
                        (inventory_id, movement_type, quantity, notes, created_by, created_at)
                        VALUES (?, 'adjustment', ?, CONCAT('جرد: ', ?), ?, NOW())
                    ");
                    $stmt->execute([$id, $actual - $current, $notes, $this->userId]);
                }
            }

            $this->audit->logCreate('inventory_count', 0, [
                'notes' => $notes,
                'differences' => $differences
            ]);

            $this->db->commit();
            $_SESSION['count_results'] = $differences;
            $this->redirect('/inventory/count-report', 'تم تحديث الجرد بنجاح', 'success');

        } catch (\Exception $e) {
            $this->db->rollBack();
            $this->redirect('/inventory/count', 'خطأ: ' . $e->getMessage(), 'error');
        }
    }

    public function countReport()
    {
        $this->requirePermission('manage_inventory');
        $results = $_SESSION['count_results'] ?? [];
        unset($_SESSION['count_results']);

        $items = [];
        foreach ($results as $r) {
            $stmt = $this->db->prepare("SELECT name FROM inventory WHERE id = ?");
            $stmt->execute([$r['id']]);
            $name = $stmt->fetch()['name'] ?? 'غير معروف';
            $items[] = array_merge($r, ['name' => $name]);
        }

        $this->view('inventory/count_report', [
            'title' => 'تقرير الجرد',
            'items' => $items
        ]);
    }
}