<?php
namespace App\Controllers;

class CustomerController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $customerId = isset($_GET['customer_id']) ? (int) $_GET['customer_id'] : null;
        $searchTerm = isset($_GET['search']) ? trim($_GET['search']) : '';

        $customers = [];
        $customer = null;
        $sales = [];
        $devices = [];

        if ($customerId) {
            // جلب العميل
            $stmt = $this->db->prepare("SELECT * FROM customers WHERE id = ?");
            $stmt->execute([$customerId]);
            $customer = $stmt->fetch();

            if ($customer) {
                // جلب الفواتير
                $stmt = $this->db->prepare("
                    SELECT s.*, u.full_name as created_by_name
                    FROM sales s
                    LEFT JOIN users u ON s.user_id = u.id
                    WHERE s.customer_id = ? AND s.deleted_at IS NULL
                    ORDER BY s.id DESC
                ");
                $stmt->execute([$customerId]);
                $sales = $stmt->fetchAll();

                // جلب الأجهزة
                $stmt = $this->db->prepare("
                    SELECT d.*, ds.name as status_name
                    FROM devices d
                    LEFT JOIN device_statuses ds ON d.current_status_id = ds.id
                    WHERE d.customer_id = ? AND d.deleted_at IS NULL
                    ORDER BY d.id DESC
                ");
                $stmt->execute([$customerId]);
                $devices = $stmt->fetchAll();
            }
        } else {
            // جلب كل العملاء مع البحث
            if (!empty($searchTerm) && strlen($searchTerm) >= 2) {
                $like = '%' . $searchTerm . '%';
                $stmt = $this->db->prepare("
                    SELECT * FROM customers 
                    WHERE full_name LIKE ? OR phone LIKE ? 
                    ORDER BY full_name ASC
                ");
                $stmt->execute([$like, $like]);
            } else {
                $stmt = $this->db->query("SELECT * FROM customers ORDER BY id DESC");
            }
            $customers = $stmt->fetchAll();
        }

        $this->view('customers/index', [
            'title' => 'العملاء',
            'customers' => $customers,
            'customer' => $customer,
            'sales' => $sales,
            'devices' => $devices,
            'searchTerm' => $searchTerm
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
            SELECT DISTINCT id, full_name, phone FROM customers
            WHERE full_name LIKE ? OR phone LIKE ?
            LIMIT 10
        ");
        $stmt->execute([$like, $like]);
        $results = $stmt->fetchAll();

        echo json_encode($results);
    }
}