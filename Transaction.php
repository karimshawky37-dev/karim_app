<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id', 'wallet_id', 'entity_id', 'transaction_date',
        'type', 'account_type', 'amount', 'balance_after',
        'reference_number', 'notes'
    ];

    protected $casts = [
        'transaction_date' => 'datetime',
    ];

    // العلاقات
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function wallet()
    {
        return $this->belongsTo(Wallet::class);
    }

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }

    // الحصول على آخر رصيد للمعادلة
    public static function getCurrentBalance()
    {
        return self::latest('id')->value('balance_after') ?? 0;
    }
}