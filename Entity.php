<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entity extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type', 'name', 'phone', 'email', 'address', 'tax_number',
        'credit_limit', 'opening_balance', 'notes'
    ];

    // العلاقات
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function devices()
    {
        return $this->hasMany(Device::class, 'customer_id');
    }

    // حساب إجمالي الديون (للعملاء)
    public function getTotalDebtAttribute()
    {
        return $this->invoices()
            ->where('type', 'sale')
            ->whereNotIn('status', ['paid', 'cancelled'])
            ->sum('remaining_amount');
    }

    // حساب إجمالي الالتزامات (للموردين)
    public function getTotalPayableAttribute()
    {
        return $this->invoices()
            ->where('type', 'purchase')
            ->whereNotIn('status', ['paid', 'cancelled'])
            ->sum('remaining_amount');
    }
}