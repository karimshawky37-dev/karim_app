<?php

namespace App\Actions\Inventory;

use App\Events\PartRestocked;
use App\Models\Inventory;
use App\Models\InventoryMovement;
use Illuminate\Support\Facades\DB;

class AddStockAction
{
    public function handle(array $data): Inventory
    {
        return DB::transaction(function () use ($data) {

            // 1. إنشاء الصنف أو تحديث الكمية لو موجود بنفس الاسم
            $item = Inventory::updateOrCreate(
                ['name' => $data['name'], 'category' => $data['category']],
                [
                    'sku' => $data['sku'] ?? 'SKU-' . date('Ymd') . '-' . rand(1000, 9999),
                    'barcode' => $data['barcode'] ?? null,
                    'brand' => $data['brand'] ?? null,
                    'model' => $data['model'] ?? null,
                    'supplier_name' => $data['supplier_name'] ?? null,
                    'purchase_price' => $data['purchase_price'],
                    'selling_price' => $data['selling_price'],
                    'alert_quantity' => $data['alert_quantity'] ?? 5,
                    'unit' => $data['unit'] ?? 'قطعة',
                    'location' => $data['location'] ?? null,
                ]
            );

            $quantity = $data['quantity'] ?? 1;
            $oldQuantity = $item->current_quantity;

            // 2. زيادة الكمية
            $item->increment('current_quantity', $quantity);

            // 3. تسجيل الحركة
            InventoryMovement::create([
                'inventory_id' => $item->id,
                'movement_type' => 'purchase',
                'quantity' => $quantity,
                'reference_id' => $data['invoice_id'] ?? null,
                'reference_type' => $data['reference_type'] ?? null,
                'price_at_movement' => $data['purchase_price'],
                'notes' => $data['notes'] ?? 'استلام مخزون',
                'created_by' => auth()->id(),
            ]);

            // 4. 🔥 إطلاق الحدث (اللي هيحدث الأجهزة المعلقة)
            event(new PartRestocked($item, $quantity));

            return $item;
        });
    }
}