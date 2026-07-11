<?php
namespace App\Controllers;

class ReportsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->requirePermission('view_reports');
    }

    public function index()
    {
        $this->view('reports/index', ['title' => 'التقارير الدورية']);
    }

    public function chartSales()
    {
        $period = isset($_GET['period']) ? $_GET['period'] : 'month';
        $year = isset($_GET['year']) ? (int) $_GET['year'] : date('Y');
        $month = isset($_GET['month']) ? (int) $_GET['month'] : date('m');

        if ($period === 'week') {
            $sql = "SELECT DATE(sale_date) as label, COALESCE(SUM(total_amount), 0) as value FROM sales WHERE sale_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND status = 'completed' AND deleted_at IS NULL GROUP BY DATE(sale_date) ORDER BY label ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
        } elseif ($period === 'month') {
            $sql = "SELECT DATE(sale_date) as label, COALESCE(SUM(total_amount), 0) as value FROM sales WHERE MONTH(sale_date) = ? AND YEAR(sale_date) = ? AND status = 'completed' AND deleted_at IS NULL GROUP BY DATE(sale_date) ORDER BY label ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$month, $year]);
        } else {
            $sql = "SELECT DATE_FORMAT(sale_date, '%Y-%m') as label, COALESCE(SUM(total_amount), 0) as value FROM sales WHERE YEAR(sale_date) = ? AND status = 'completed' AND deleted_at IS NULL GROUP BY DATE_FORMAT(sale_date, '%Y-%m') ORDER BY label ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$year]);
        }
        $data = $stmt->fetchAll();
        $this->json($data);
    }

    public function chartDeviceStatus()
    {
        $stmt = $this->db->query("SELECT ds.name as label, COUNT(d.id) as value FROM devices d JOIN device_statuses ds ON d.current_status_id = ds.id WHERE d.deleted_at IS NULL GROUP BY d.current_status_id");
        $data = $stmt->fetchAll();
        $this->json($data);
    }

    public function chartTechnicians()
    {
        $startDate = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $endDate = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

        $stmt = $this->db->prepare("
            SELECT u.full_name as label, COUNT(rj.id) as value, COUNT(CASE WHEN rj.is_completed = 1 THEN 1 END) as completed
            FROM users u LEFT JOIN repair_jobs rj ON u.id = rj.technician_id AND DATE(rj.created_at) BETWEEN ? AND ?
            WHERE u.role = 'technician' AND u.is_active = 1
            GROUP BY u.id ORDER BY completed DESC
        ");
        $stmt->execute([$startDate, $endDate]);
        $data = $stmt->fetchAll();
        $this->json($data);
    }

    public function chartPopularDevices()
    {
        $limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 10;
        $stmt = $this->db->prepare("SELECT CONCAT(brand, ' ', model) as label, COUNT(*) as value FROM devices WHERE deleted_at IS NULL GROUP BY brand, model ORDER BY value DESC LIMIT ?");
        $stmt->execute([$limit]);
        $data = $stmt->fetchAll();
        $this->json($data);
    }

    public function chartProfit()
    {
        $period = isset($_GET['period']) ? $_GET['period'] : 'month';
        $year = isset($_GET['year']) ? (int) $_GET['year'] : date('Y');
        $month = isset($_GET['month']) ? (int) $_GET['month'] : date('m');

        if ($period === 'month') {
            $sql = "SELECT DATE(s.sale_date) as label, COALESCE(SUM(s.total_amount), 0) as sales, COALESCE(SUM(e.amount), 0) as expenses, COALESCE(SUM(s.total_amount) - SUM(e.amount), 0) as profit FROM sales s LEFT JOIN expenses e ON DATE(e.expense_date) = DATE(s.sale_date) WHERE MONTH(s.sale_date) = ? AND YEAR(s.sale_date) = ? AND s.status = 'completed' AND s.deleted_at IS NULL GROUP BY DATE(s.sale_date) ORDER BY label ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$month, $year]);
        } elseif ($period === 'year') {
            $sql = "SELECT DATE_FORMAT(s.sale_date, '%Y-%m') as label, COALESCE(SUM(s.total_amount), 0) as sales, COALESCE(SUM(e.amount), 0) as expenses, COALESCE(SUM(s.total_amount) - SUM(e.amount), 0) as profit FROM sales s LEFT JOIN expenses e ON MONTH(e.expense_date) = MONTH(s.sale_date) AND YEAR(e.expense_date) = YEAR(s.sale_date) WHERE YEAR(s.sale_date) = ? AND s.status = 'completed' AND s.deleted_at IS NULL GROUP BY DATE_FORMAT(s.sale_date, '%Y-%m') ORDER BY label ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$year]);
        } else {
            $sql = "SELECT DATE(s.sale_date) as label, COALESCE(SUM(s.total_amount), 0) as sales, COALESCE(SUM(e.amount), 0) as expenses, COALESCE(SUM(s.total_amount) - SUM(e.amount), 0) as profit FROM sales s LEFT JOIN expenses e ON DATE(e.expense_date) = DATE(s.sale_date) WHERE s.sale_date >= DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND s.status = 'completed' AND s.deleted_at IS NULL GROUP BY DATE(s.sale_date) ORDER BY label ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
        }
        $data = $stmt->fetchAll();
        $this->json($data);
    }

    public function sales()
    {
        $period = isset($_GET['period']) ? $_GET['period'] : 'month';
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');
        $status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';

        $statusCondition = "AND s.deleted_at IS NULL";
        if ($status_filter === 'completed') $statusCondition .= " AND s.status = 'completed'";
        elseif ($status_filter === 'pending') $statusCondition .= " AND s.status = 'pending'";
        elseif ($status_filter === 'partially') $statusCondition .= " AND s.status = 'partially_paid'";

        $stmt = $this->db->prepare("SELECT DATE(s.sale_date) as date, COUNT(*) as count, COALESCE(SUM(s.total_amount), 0) as total, COALESCE(SUM(s.paid_amount), 0) as paid, COALESCE(SUM(s.remaining_amount), 0) as remaining FROM sales s WHERE DATE(s.sale_date) BETWEEN ? AND ? {$statusCondition} GROUP BY DATE(s.sale_date) ORDER BY date ASC");
        $stmt->execute([$start_date, $end_date]);
        $data = $stmt->fetchAll();

        $stmt = $this->db->prepare("SELECT COUNT(*) as total_count, COALESCE(SUM(total_amount), 0) as total_amount, COALESCE(AVG(total_amount), 0) as avg_amount, COALESCE(SUM(paid_amount), 0) as total_paid, COALESCE(SUM(remaining_amount), 0) as total_remaining FROM sales s WHERE DATE(s.sale_date) BETWEEN ? AND ? {$statusCondition}");
        $stmt->execute([$start_date, $end_date]);
        $stats = $stmt->fetch();

        $stmt = $this->db->prepare("SELECT c.full_name, c.phone, COUNT(s.id) as orders_count, COALESCE(SUM(s.total_amount), 0) as total_spent FROM sales s LEFT JOIN customers c ON s.customer_id = c.id WHERE DATE(s.sale_date) BETWEEN ? AND ? {$statusCondition} GROUP BY s.customer_id ORDER BY total_spent DESC LIMIT 10");
        $stmt->execute([$start_date, $end_date]);
        $topCustomers = $stmt->fetchAll();

        $this->view('reports/sales', [
            'title' => 'تقرير المبيعات',
            'data' => $data,
            'stats' => $stats,
            'topCustomers' => $topCustomers,
            'period' => $period,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'status_filter' => $status_filter
        ]);
    }

    public function profit()
    {
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

        $stmt = $this->db->prepare("
            SELECT DATE(s.sale_date) as date, COUNT(DISTINCT s.id) as orders_count, COALESCE(SUM(s.total_amount), 0) as total_sales, COALESCE(SUM(si.total_price), 0) as parts_cost, COALESCE(SUM(s.total_amount) - SUM(si.total_price), 0) as profit
            FROM sales s LEFT JOIN sale_items si ON s.id = si.sale_id AND si.item_type = 'part'
            WHERE s.status = 'completed' AND s.deleted_at IS NULL AND DATE(s.sale_date) BETWEEN ? AND ?
            GROUP BY DATE(s.sale_date) ORDER BY date ASC
        ");
        $stmt->execute([$start_date, $end_date]);
        $data = $stmt->fetchAll();

        $this->view('reports/profit', [
            'title' => 'تقرير أرباح الصيانة',
            'data' => $data,
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);
    }

    public function inventory()
    {
        $stmt = $this->db->query("
            SELECT i.name, i.category, i.current_quantity, i.alert_quantity,
                   COUNT(im.id) as movements_count,
                   COALESCE(SUM(CASE WHEN im.movement_type = 'purchase' THEN im.quantity ELSE 0 END), 0) as total_in,
                   COALESCE(SUM(CASE WHEN im.movement_type = 'sale' THEN im.quantity ELSE 0 END), 0) as total_out,
                   COALESCE(SUM(CASE WHEN im.movement_type = 'repair_use' THEN im.quantity ELSE 0 END), 0) as total_repair
            FROM inventory i LEFT JOIN inventory_movements im ON i.id = im.inventory_id
            WHERE i.deleted_at IS NULL
            GROUP BY i.id ORDER BY i.name ASC
        ");
        $data = $stmt->fetchAll();

        $this->view('reports/inventory', [
            'title' => 'تقرير حركة المخزون',
            'data' => $data
        ]);
    }

    public function technicians()
    {
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

        $stmt = $this->db->prepare("
            SELECT u.id, u.full_name,
                   COUNT(rj.id) as total_jobs,
                   COUNT(CASE WHEN rj.is_completed = 1 THEN 1 END) as completed_jobs,
                   COUNT(CASE WHEN rj.is_completed = 0 THEN 1 END) as pending_jobs,
                   AVG(TIMESTAMPDIFF(HOUR, rj.started_at, rj.completed_at)) as avg_hours,
                   COUNT(d.id) as current_devices
            FROM users u
            LEFT JOIN repair_jobs rj ON u.id = rj.technician_id AND DATE(rj.created_at) BETWEEN ? AND ?
            LEFT JOIN devices d ON u.id = d.assigned_technician_id AND d.deleted_at IS NULL
            WHERE u.role = 'technician' AND u.is_active = 1
            GROUP BY u.id ORDER BY completed_jobs DESC
        ");
        $stmt->execute([$start_date, $end_date]);
        $data = $stmt->fetchAll();

        $this->view('reports/technicians', [
            'title' => 'تقرير أداء الفنيين',
            'data' => $data,
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);
    }

    public function customers()
    {
        $start_date = isset($_GET['start_date']) ? $_GET['start_date'] : date('Y-m-01');
        $end_date = isset($_GET['end_date']) ? $_GET['end_date'] : date('Y-m-d');

        $stmt = $this->db->prepare("
            SELECT c.id, c.full_name, c.phone, c.email,
                   COUNT(d.id) as devices_count, COUNT(s.id) as orders_count,
                   COALESCE(SUM(s.total_amount), 0) as total_spent,
                   MAX(s.sale_date) as last_order_date,
                   DATEDIFF(NOW(), MAX(s.sale_date)) as days_since_last_order
            FROM customers c
            LEFT JOIN devices d ON c.id = d.customer_id AND d.deleted_at IS NULL
            LEFT JOIN sales s ON c.id = s.customer_id AND s.status = 'completed' AND s.deleted_at IS NULL AND DATE(s.sale_date) BETWEEN ? AND ?
            WHERE c.deleted_at IS NULL
            GROUP BY c.id HAVING orders_count >= 2 ORDER BY total_spent DESC
        ");
        $stmt->execute([$start_date, $end_date]);
        $data = $stmt->fetchAll();

        $this->view('reports/customers', [
            'title' => 'تقرير العملاء المتكررين',
            'data' => $data,
            'start_date' => $start_date,
            'end_date' => $end_date
        ]);
    }

    public function exportSalesCsv()
    {
        // ... (نفس الكود السابق) ...
    }
}