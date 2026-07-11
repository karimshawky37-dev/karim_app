
<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Support\Facades\DB;

class InvestmentService
{
    /**
     * تحديث معادلة الاستثمار عند إنشاء أو تعديل فاتورة
     */
    public function updateFromInvoice(Invoice $invoice)
    {
        DB::transaction(function () use ($invoice) {
            // حذف الحركات القديمة لهذه الفاتورة (لتجنب التكرار)
            Transaction::where('invoice_id', $invoice->id)->delete();

            // الحصول على آخر رصيد
            $lastBalance = Transaction::getCurrentBalance();

            // 1. إذا كانت فاتورة بيع
            if ($invoice->type === 'sale') {
                // زيادة الأصول (ديون العملاء) بالقيمة المتبقية
                if ($invoice->remaining_amount > 0) {
                    $lastBalance += $invoice->remaining_amount;
                    Transaction::create([
                        'invoice_id' => $invoice->id,
                        'entity_id' => $invoice->entity_id,
                        'transaction_date' => now(),
                        'type' => 'sale',
                        'account_type' => 'asset',
                        'amount' => $invoice->remaining_amount,
                        'balance_after' => $lastBalance,
                        'notes' => 'ديون عميل من فاتورة #' . $invoice->invoice_number,
                    ]);
                }

                // زيادة الإيرادات بالقيمة الإجمالية
                $lastBalance += $invoice->total_amount;
                Transaction::create([
                    'invoice_id' => $invoice->id,
                    'transaction_date' => now(),
                    'type' => 'sale',
                    'account_type' => 'revenue',
                    'amount' => $invoice->total_amount,
                    'balance_after' => $lastBalance,
                    'notes' => 'إيراد مبيعات فاتورة #' . $invoice->invoice_number,
                ]);

                // إذا كان هناك مبلغ مدفوع نقداً، نتعامل مع المحفظة
                if ($invoice->paid_amount > 0 && $invoice->wallet_id) {
                    // تحديث رصيد المحفظة
                    $wallet = Wallet::find($invoice->wallet_id);
                    if ($wallet) {
                        $wallet->increment('balance', $invoice->paid_amount);
                        // تسجيل حركة محفظة
                        $lastBalance += $invoice->paid_amount;
                        Transaction::create([
                            'invoice_id' => $invoice->id,
                            'wallet_id' => $invoice->wallet_id,
                            'transaction_date' => now(),
                            'type' => 'receipt',
                            'account_type' => 'asset',
                            'amount' => $invoice->paid_amount,
                            'balance_after' => $lastBalance,
                            'notes' => 'دفعة نقدية من فاتورة #' . $invoice->invoice_number,
                        ]);
                    }
                }
            }

            // 2. إذا كانت فاتورة شراء
            if ($invoice->type === 'purchase') {
                // زيادة الالتزامات (ديون الموردين)
                if ($invoice->remaining_amount > 0) {
                    $lastBalance -= $invoice->remaining_amount;
                    Transaction::create([
                        'invoice_id' => $invoice->id,
                        'entity_id' => $invoice->entity_id,
                        'transaction_date' => now(),
                        'type' => 'purchase',
                        'account_type' => 'liability',
                        'amount' => $invoice->remaining_amount,
                        'balance_after' => $lastBalance,
                        'notes' => 'ديون مورد من فاتورة #' . $invoice->invoice_number,
                    ]);
                }

                // زيادة المصروفات أو تكلفة البضاعة
                $lastBalance -= $invoice->total_amount;
                Transaction::create([
                    'invoice_id' => $invoice->id,
                    'transaction_date' => now(),
                    'type' => 'purchase',
                    'account_type' => 'expense',
                    'amount' => $invoice->total_amount,
                    'balance_after' => $lastBalance,
                    'notes' => 'تكلفة مشتريات فاتورة #' . $invoice->invoice_number,
                ]);

                // إذا تم دفع نقداً، نخصم من المحفظة
                if ($invoice->paid_amount > 0 && $invoice->wallet_id) {
                    $wallet = Wallet::find($invoice->wallet_id);
                    if ($wallet) {
                        $wallet->decrement('balance', $invoice->paid_amount);
                        $lastBalance -= $invoice->paid_amount;
                        Transaction::create([
                            'invoice_id' => $invoice->id,
                            'wallet_id' => $invoice->wallet_id,
                            'transaction_date' => now(),
                            'type' => 'payment',
                            'account_type' => 'asset',
                            'amount' => $invoice->paid_amount,
                            'balance_after' => $lastBalance,
                            'notes' => 'دفعة نقدية لمورد فاتورة #' . $invoice->invoice_number,
                        ]);
                    }
                }
            }

            // 3. تحديث رصيد معادلة الاستثمار في جدول الإعدادات (اختياري للتخزين المؤقت)
            $this->updateInvestmentCache($lastBalance);
        });
    }

    /**
     * إعادة حساب المعادلة بالكامل من الصفر (في حالة التصحيح)
     */
    public function recalculate()
    {
        // حذف جميع الحركات وإعادة بنائها من الفواتير (معقد)
        // يمكن تنفيذها حسب الحاجة
    }

    /**
     * الحصول على رصيد المعادلة الحالي
     */
    public function getCurrentBalance()
    {
        return Transaction::getCurrentBalance();
    }

    /**
     * تحديث الكاش في الإعدادات (اختياري)
     */
    private function updateInvestmentCache($balance)
    {
        \App\Models\Setting::updateOrCreate(
            ['setting_key' => 'investment_balance'],
            ['setting_value' => $balance, 'setting_group' => 'financial']
        );
    }
}