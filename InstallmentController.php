<?php
namespace App\Controllers;

use App\Config\Database;

class InstallmentController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
    }

    // ============================================================
    // 📋 قائمة الأقساط
    // ============================================================
    public function index()
    {
        $fromCustomers = isset($_GET['from']) && $_GET['from'] == 'customers';
        $customerId = isset($_GET['customer_id']) ? (int) $_GET['customer_id'] : null;

        $stmt = $this->db->prepare("
            SELECT i.*, c.full_name as customer_name, c.phone as customer_phone,
                   (SELECT COUNT(*) FROM installment_payments WHERE installment_id = i.id AND is_paid = 0 AND due_date < CURDATE()) as overdue_count
            FROM installments i
            LEFT JOIN customers c ON i.customer_id = c.id
            " . ($customerId ? "WHERE i.customer_id = ?" : "") . "
            ORDER BY i.id DESC
        ");
        if ($customerId) {
            $stmt->execute([$customerId]);
        } else {
            $stmt->execute();
        }
        $installments = $stmt->fetchAll();

        $stmt = $this->db->query("SELECT COUNT(*) as count, COALESCE(SUM(remaining_amount), 0) as total_debt FROM installments WHERE status = 'active'");
        $stats = $stmt->fetch();

        $this->view('installments/index', [
            'title' => 'الأقساط والديون',
            'installments' => $installments,
            'stats' => $stats,
            'fromCustomers' => $fromCustomers,
            'customerId' => $customerId
        ]);
    }

    // ============================================================
    // ➕ نموذج إضافة قسط جديد
    // ============================================================
    public function create()
    {
        $this->view('installments/create', [
            'title' => 'إضافة قسط جديد'
        ]);
    }

    // ============================================================
    // 💾 حفظ القسط الجديد
    // ============================================================
    public function store()
    {
        $customer_name = isset($_POST['customer_name']) ? trim($_POST['customer_name']) : '';
        $customer_phone = isset($_POST['customer_phone']) ? trim($_POST['customer_phone']) : '';
        $customer_id = null;

        if (!empty($customer_name) || !empty($customer_phone)) {
            $stmt = $this->db->prepare("SELECT id FROM customers WHERE phone = ?");
            $stmt->execute([$customer_phone]);
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

        $device_name = trim($_POST['device_name']);
        $total_amount = (float) $_POST['total_amount'];
        $down_payment = isset($_POST['down_payment']) ? (float) $_POST['down_payment'] : 0;
        $number_of_installments = (int) $_POST['number_of_installments'];
        $installment_value = (float) $_POST['installment_value'];
        $start_date = $_POST['start_date'];
        $end_date = $_POST['end_date'];
        $notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';

        $remaining_amount = $total_amount - $down_payment;

        if (empty($customer_id) || empty($device_name) || $total_amount <= 0 || $number_of_installments <= 0) {
            $this->redirect('/installments/create', 'جميع الحقول المطلوبة يجب تعبئتها بشكل صحيح', 'error');
            return;
        }

        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("
                INSERT INTO installments 
                (customer_id, device_name, total_amount, down_payment, remaining_amount, 
                 number_of_installments, installment_value, start_date, end_date, status, notes, created_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'active', ?, ?)
            ");
            $stmt->execute([$customer_id, $device_name, $total_amount, $down_payment, $remaining_amount,
                           $number_of_installments, $installment_value, $start_date, $end_date, $notes, $this->userId]);

            $installmentId = $this->db->lastInsertId();

            $date = new \DateTime($start_date);
            for ($i = 1; $i <= $number_of_installments; $i++) {
                $due_date = $date->format('Y-m-d');
                $stmt = $this->db->prepare("
                    INSERT INTO installment_payments 
                    (installment_id, payment_number, due_date, amount, is_paid)
                    VALUES (?, ?, ?, ?, 0)
                ");
                $stmt->execute([$installmentId, $i, $due_date, $installment_value]);
                $date->modify('+1 month');
            }

            $this->audit->logCreate('installments', $installmentId, [
                'customer_id' => $customer_id,
                'total_amount' => $total_amount,
                'number_of_installments' => $number_of_installments
            ]);

            $this->db->commit();
            $this->redirect("/installments/show/{$installmentId}", 'تم إنشاء القسط بنجاح', 'success');

        } catch (\Exception $e) {
            $this->db->rollBack();
            $this->redirect('/installments/create', 'خطأ: ' . $e->getMessage(), 'error');
        }
    }

    // ============================================================
    // 👁️ عرض تفاصيل القسط (تم تغيير الاسم لتجنب التعارض)
    // ============================================================
    public function viewInstallment($id)
    {
        $stmt = $this->db->prepare("
            SELECT i.*, c.full_name as customer_name, c.phone as customer_phone
            FROM installments i
            LEFT JOIN customers c ON i.customer_id = c.id
            WHERE i.id = ?
        ");
        $stmt->execute([$id]);
        $installment = $stmt->fetch();

        if (!$installment) {
            die("<h1>القسط غير موجود</h1><a href='/installments'>العودة</a>");
        }

        $stmt = $this->db->prepare("
            SELECT * FROM installment_payments WHERE installment_id = ? ORDER BY payment_number ASC
        ");
        $stmt->execute([$id]);
        $payments = $stmt->fetchAll();

        $paidTotal = 0;
        foreach ($payments as $p) {
            if ($p['is_paid']) {
                $paidTotal += $p['paid_amount'];
            }
        }

        $this->view('installments/view', [
            'title' => 'تفاصيل القسط',
            'installment' => $installment,
            'payments' => $payments,
            'paidTotal' => $paidTotal
        ]);
    }

    // ============================================================
    // 💰 إضافة دفعة على القسط (نسخة مستقرة)
    // ============================================================
    public function addPayment()
    {
        $installment_id = (int) $_POST['installment_id'];
        $amount = (float) $_POST['amount'];
        $penalty = isset($_POST['penalty']) ? (float) $_POST['penalty'] : 0;
        $notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';

        if ($amount <= 0) {
            $this->redirect("/installments/show/{$installment_id}", 'لا يمكن إضافة دفعة بقيمة صفر', 'error');
            return;
        }

        try {
            $this->db->beginTransaction();

            $stmt = $this->db->prepare("SELECT * FROM installments WHERE id = ?");
            $stmt->execute([$installment_id]);
            $installment = $stmt->fetch();

            if (!$installment) {
                throw new \Exception('القسط غير موجود');
            }

            $stmt = $this->db->prepare("
                SELECT id, amount, COALESCE(paid_amount, 0) as paid_amount, is_paid
                FROM installment_payments 
                WHERE installment_id = ? 
                  AND (is_paid = 0 OR paid_amount < amount)
                ORDER BY payment_number ASC
            ");
            $stmt->execute([$installment_id]);
            $payments = $stmt->fetchAll();

            if (empty($payments)) {
                throw new \Exception('جميع الأقساط مدفوعة بالكامل');
            }

            $remainingAmount = $amount;
            $totalPaid = 0;

            foreach ($payments as $payment) {
                if ($remainingAmount <= 0) {
                    break;
                }

                $installmentAmount = $payment['amount'];
                $alreadyPaid = $payment['paid_amount'] ?? 0;
                $remainingForThisInstallment = $installmentAmount - $alreadyPaid;

                $payForThis = min($remainingAmount, $remainingForThisInstallment);
                $remainingAmount -= $payForThis;
                $totalPaid += $payForThis;

                $newPaidAmount = $alreadyPaid + $payForThis;
                $isFullyPaid = $newPaidAmount >= $installmentAmount;

                if ($isFullyPaid) {
                    $stmt = $this->db->prepare("
                        UPDATE installment_payments 
                        SET paid_amount = ?, is_paid = 1, payment_date = NOW(), penalty = ?, notes = ?
                        WHERE id = ?
                    ");
                    $stmt->execute([$installmentAmount, $penalty, $notes, $payment['id']]);
                } else {
                    $stmt = $this->db->prepare("
                        UPDATE installment_payments 
                        SET paid_amount = ?, penalty = ?, notes = ?
                        WHERE id = ?
                    ");
                    $stmt->execute([$newPaidAmount, $penalty, $notes, $payment['id']]);
                }
            }

            $newRemaining = $installment['remaining_amount'] - $totalPaid - $penalty;
            if ($newRemaining < 0) {
                $newRemaining = 0;
            }

            $newStatus = $newRemaining <= 0 ? 'completed' : 'active';

            $stmt = $this->db->prepare("
                UPDATE installments 
                SET remaining_amount = ?, status = ?, updated_at = NOW()
                WHERE id = ?
            ");
            $stmt->execute([$newRemaining, $newStatus, $installment_id]);

            $this->audit->logUpdate('installments', $installment_id, 
                ['remaining_amount' => $installment['remaining_amount']],
                ['remaining_amount' => $newRemaining, 'paid' => $totalPaid]
            );

            $this->db->commit();

            $message = $newStatus == 'completed' ? 'تم سداد القسط بالكامل!' : 'تم تسجيل الدفعة بنجاح.';
            $this->redirect("/installments/show/{$installment_id}", $message, 'success');

        } catch (\Exception $e) {
            $this->db->rollBack();
            $this->redirect("/installments/show/{$installment_id}", 'خطأ: ' . $e->getMessage(), 'error');
        }
    }

    // ============================================================
    // 🗑️ حذف قسط
    // ============================================================
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM installments WHERE id = ?");
        $stmt->execute([$id]);
        $stmt = $this->db->prepare("DELETE FROM installment_payments WHERE installment_id = ?");
        $stmt->execute([$id]);
        
        $this->audit->logDelete('installments', $id, ['reason' => 'حذف يدوي']);
        $this->redirect('/installments', 'تم حذف القسط بنجاح', 'success');
    }

    // ============================================================
    // ⚠️ الأقساط المتأخرة
    // ============================================================
    public function overdue()
    {
        $stmt = $this->db->prepare("
            SELECT i.*, c.full_name as customer_name, c.phone as customer_phone,
                   (SELECT COUNT(*) FROM installment_payments WHERE installment_id = i.id AND is_paid = 0 AND due_date < CURDATE()) as overdue_count
            FROM installments i
            LEFT JOIN customers c ON i.customer_id = c.id
            WHERE i.status = 'active'
            ORDER BY (SELECT MIN(due_date) FROM installment_payments WHERE installment_id = i.id AND is_paid = 0) ASC
        ");
        $stmt->execute();
        $installments = $stmt->fetchAll();

        $this->view('installments/overdue', [
            'title' => 'الأقساط المتأخرة',
            'installments' => $installments
        ]);
    }
}