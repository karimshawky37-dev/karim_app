<?php
namespace App\Controllers;

use App\Config\Database;

class ExpenseController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        // لا نطلب صلاحية مؤقتاً حتى يتم إنشاء جدول permissions
        // $this->requirePermission('view_financial');
        
        $stmt = $this->db->query("
            SELECT e.*, u.full_name as created_by_name
            FROM expenses e
            LEFT JOIN users u ON e.created_by = u.id
            ORDER BY e.id DESC
        ");
        $expenses = $stmt->fetchAll();

        $stmt = $this->db->query("SELECT COALESCE(SUM(amount), 0) as total FROM expenses");
        $totalExpenses = $stmt->fetch()['total'] ?? 0;
        
        $stmt = $this->db->query("
            SELECT COALESCE(SUM(amount), 0) as total 
            FROM expenses 
            WHERE MONTH(expense_date) = MONTH(CURDATE()) 
              AND YEAR(expense_date) = YEAR(CURDATE())
        ");
        $monthlyExpenses = $stmt->fetch()['total'] ?? 0;

        $this->view('expenses/index', [
            'title' => 'المصروفات',
            'expenses' => $expenses,
            'totalExpenses' => $totalExpenses,
            'monthlyExpenses' => $monthlyExpenses
        ]);
    }

    public function create()
    {
        $stmt = $this->db->query("SELECT id, wallet_name FROM wallets WHERE is_active = 1");
        $wallets = $stmt->fetchAll();

        $this->view('expenses/create', [
            'title' => 'إضافة مصروف',
            'wallets' => $wallets
        ]);
    }

    public function store()
    {
        $category = $_POST['expense_category'];
        $description = trim($_POST['description']);
        $amount = (float) $_POST['amount'];
        $expense_date = $_POST['expense_date'];
        $wallet_id = (int) $_POST['wallet_id'];

        if (empty($description) || $amount <= 0) {
            $this->redirect('/expenses/create', 'جميع الحقول مطلوبة', 'error');
            return;
        }

        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
                INSERT INTO expenses (expense_category, description, amount, expense_date, wallet_id, created_by)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$category, $description, $amount, $expense_date, $wallet_id, $this->userId]);

            $expenseId = $this->db->lastInsertId();

            // خصم من المحفظة
            $stmt = $this->db->prepare("
                INSERT INTO wallet_transactions 
                (wallet_id, transaction_type, amount, fee, balance_after, description, created_by, transaction_date)
                SELECT ?, 'withdraw', ?, 0, current_balance - ?, 
                       CONCAT('مصروف: ', ?), ?, NOW()
                FROM wallets WHERE id = ?
            ");
            $stmt->execute([$wallet_id, $amount, $amount, $description, $this->userId, $wallet_id]);

            $stmt = $this->db->prepare("UPDATE wallets SET current_balance = current_balance - ? WHERE id = ?");
            $stmt->execute([$amount, $wallet_id]);

            $this->db->commit();
            
            $this->audit->logCreate('expenses', $expenseId, [
                'category' => $category,
                'amount' => $amount,
                'wallet_id' => $wallet_id
            ]);

            $this->redirect('/expenses', 'تم إضافة المصروف بنجاح', 'success');

        } catch (\Exception $e) {
            $this->db->rollBack();
            $this->redirect('/expenses/create', 'حدث خطأ: ' . $e->getMessage(), 'error');
        }
    }
}