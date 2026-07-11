<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'entity_id', 'wallet_id', 'invoice_number', 'type', 'payment_type',
        'invoice_date', 'due_date', 'subtotal', 'discount', 'tax',
        'total_amount', 'paid_amount', 'remaining_amount', 'status', 'notes', 'created_by'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
    ];

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function paymentSchedules()
    {
        return $this->hasMany(PaymentSchedule::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}