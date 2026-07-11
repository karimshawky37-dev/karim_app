<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inventory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'sku', 'barcode', 'name', 'category', 'brand', 'model',
        'supplier_id', 'supplier_name', 'purchase_price', 'selling_price',
        'min_selling_price', 'alert_quantity', 'current_quantity',
        'reserved_quantity', 'unit', 'location', 'is_active'
    ];

    // العلاقات
    public function supplier() { return $this->belongsTo(Supplier::class); }
    public function movements() { return $this->hasMany(InventoryMovement::class); }

    // أكسسور مفيد: هل الكمية منخفضة؟
    public function getIsLowStockAttribute(): bool
    {
        return $this->current_quantity <= $this->alert_quantity;
    }

    // حساب القيمة الإجمالية للمخزون
    public function getTotalValueAttribute(): float
    {
        return $this->current_quantity * $this->purchase_price;
    }
}