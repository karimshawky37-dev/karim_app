<?php

namespace App\Http\Controllers;

use App\Models\Entity;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Transaction;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    /**
     * إنشاء فاتورة بيع آجلة (مع تسجيل الحركات المالية)
     */
    public function storeCreditSale(Request $request)
    {
        $validated = $request->validate([
            'entity_id' => 'required|exists:entities,id',
            'items' => 'required|array',
            'items.*.description' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'notes' => 'nullable|string',
        ]);

        $entity = Entity::findOrFail($validated['entity_id']);

        // حساب إجمالي الفاتورة
        $subtotal = 0;
        $items = [];
        foreach ($validated['items'] as $item) {
            $total = $item['quantity'] * $item['unit_price'];
            $subtotal += $total;
            $items[] = [
                'description' => $item['description'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $total,
            ];
        }

        $tax = round($subtotal * 0.14, 2); // ضريبة 14%
        $totalAmount = $subtotal + $tax;

        // توليد رقم فاتورة فريد
        $invoiceNumber = 'INV-' . date('Ymd') . '-' . Str::random(6);

        DB::beginTransaction();

        try {
            // 1. إنشاء الفاتورة
            $invoice = Invoice::create([
                'entity_id' => $entity->id,
                'wallet_id' => null, // آجلة، لا توجد محفظة
                'invoice_number' => $invoiceNumber,
                'type' => 'sale',
                'payment_type' => 'credit',
                'invoice_date' => $validated['invoice_date'],
                'due_date' => $validated['due_date'],
                'subtotal' => $subtotal,
                'discount' => 0,
                'tax' => $tax,
                'total_amount' => $totalAmount,
                'paid_amount' => 0,
                'remaining_amount' => $totalAmount,
                'status' => 'posted',
                'notes' => $validated['notes'] ?? null,
                'created_by' => auth()->id(),
            ]);

            // 2. إضافة بنود الفاتورة
            foreach ($items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'description' => $item['description'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'total_price' => $item['total_price'],
                ]);
            }

            // 3. جدولة الدفعات (الأقساط) - في حالة التقسيط
            if ($request->payment_type === 'installment' && $request->installments) {
                $installmentCount = $request->installments;
                $installmentAmount = round($totalAmount / $installmentCount, 2);
                for ($i = 1; $i <= $installmentCount; $i++) {
                    $dueDate = date('Y-m-d', strtotime("+{$i} month", strtotime($validated['invoice_date'])));
                    PaymentSchedule::create([
                        'invoice_id' => $invoice->id,
                        'due_date' => $dueDate,
                        'amount_due' => $installmentAmount,
                        'amount_paid' => 0,
                        'status' => 'pending',
                    ]);
                }
            } else {
                // دفعة واحدة (آجل عادي)
                PaymentSchedule::create([
                    'invoice_id' => $invoice->id,
                    'due_date' => $validated['due_date'],
                    'amount_due' => $totalAmount,
                    'amount_paid' => 0,
                    'status' => 'pending',
                ]);
            }

            // 4. تسجيل الحركات المحاسبية (Transactions)
            $balanceAfter = $this->getCurrentInvestmentBalance();

            // 4.1 زيادة ديون العملاء (أصول)
            Transaction::create([
                'invoice_id' => $invoice->id,
                'entity_id' => $entity->id,
                'transaction_date' => now(),
                'type' => 'sale',
                'account_type' => 'asset',
                'amount' => $totalAmount,
                'balance_after' => $balanceAfter + $totalAmount,
                'notes' => 'زيادة ديون العميل: ' . $entity->name,
            ]);
            $balanceAfter += $totalAmount;

            // 4.2 زيادة الإيرادات (حقوق ملكية)
            Transaction::create([
                'invoice_id' => $invoice->id,
                'transaction_date' => now(),
                'type' => 'sale',
                'account_type' => 'revenue',
                'amount' => $totalAmount,
                'balance_after' => $balanceAfter + $totalAmount,
                'notes' => 'تسجيل إيراد المبيعات',
            ]);
            $balanceAfter += $totalAmount;

            // هنا يمكن إضافة حركات أخرى للتكلفة والمخزون حسب منطق النظام

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم إنشاء فاتورة البيع الآجلة بنجاح',
                'invoice' => $invoice->load('items', 'entity'),
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إنشاء الفاتورة',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * استلام دفعة من عميل (سداد دين)
     */
    public function receivePayment(Request $request)
    {
        $validated = $request->validate([
            'invoice_id' => 'required|exists:invoices,id',
            'wallet_id' => 'required|exists:wallets,id',
            'amount' => 'required|numeric|min:0.01',
            'notes' => 'nullable|string',
        ]);

        $invoice = Invoice::with('entity')->findOrFail($validated['invoice_id']);
        $wallet = Wallet::findOrFail($validated['wallet_id']);

        if ($invoice->remaining_amount < $validated['amount']) {
            return response()->json([
                'success' => false,
                'message' => 'المبلغ المستلم يتجاوز المتبقي من الفاتورة',
            ], 422);
        }

        DB::beginTransaction();

        try {
            // تحديث الفاتورة
            $newPaid = $invoice->paid_amount + $validated['amount'];
            $newRemaining = $invoice->remaining_amount - $validated['amount'];
            $status = $newRemaining <= 0 ? 'paid' : 'partial';

            $invoice->update([
                'paid_amount' => $newPaid,
                'remaining_amount' => $newRemaining,
                'status' => $status,
                'wallet_id' => $wallet->id,
            ]);

            // تحديث رصيد المحفظة
            $wallet->increment('balance', $validated['amount']);

            // تسجيل الحركة (نقص ديون العملاء)
            $balanceAfter = $this->getCurrentInvestmentBalance();
            Transaction::create([
                'invoice_id' => $invoice->id,
                'wallet_id' => $wallet->id,
                'entity_id' => $invoice->entity_id,
                'transaction_date' => now(),
                'type' => 'receipt',
                'account_type' => 'asset',
                'amount' => -$validated['amount'],
                'balance_after' => $balanceAfter - $validated['amount'],
                'notes' => 'تحصيل من العميل: ' . $invoice->entity->name,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم تسجيل الدفعة بنجاح',
                'invoice' => $invoice,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء تسجيل الدفعة',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * الحصول على رصيد معادلة الاستثمار الحالي
     */
    private function getCurrentInvestmentBalance()
    {
        $lastTransaction = Transaction::orderBy('id', 'desc')->first();
        return $lastTransaction ? $lastTransaction->balance_after : 0;
    }

    /**
     * جلب إجمالي ديون العملاء (لشاشة الديون)
     */
    public function getCustomerDebts()
    {
        $debts = Entity::whereIn('type', ['customer', 'both'])
            ->withSum(['invoices as total_debt' => function ($q) {
                $q->where('type', 'sale')
                  ->where('status', '!=', 'paid')
                  ->where('remaining_amount', '>', 0);
            }], 'remaining_amount')
            ->having('total_debt', '>', 0)
            ->get(['id', 'name', 'phone', 'credit_limit']);

        return response()->json([
            'data' => $debts->map(function ($entity) {
                return [
                    'id' => $entity->id,
                    'name' => $entity->name,
                    'phone' => $entity->phone,
                    'total_debt' => $entity->total_debt ?? 0,
                    'credit_limit' => $entity->credit_limit,
                    'status' => ($entity->total_debt ?? 0) > $entity->credit_limit ? 'Exceeded' : 'OK',
                ];
            }),
        ]);
    }
}