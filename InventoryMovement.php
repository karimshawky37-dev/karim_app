<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InventoryMovement extends Model
{
    protected $fillable = [
        'inventory_id', 'movement_type', 'quantity', 'reference_id',
        'reference_type', 'price_at_movement', 'notes', 'created_by'
    ];

    public function inventory() { return $this->belongsTo(Inventory::class); }
    public function creator() { return $this->belongsTo(User::class, 'created_by'); }
}