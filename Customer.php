<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'full_name', 'phone', 'email', 'address', 'national_id', 'notes'
    ];

    // ========== العلاقات ==========
    public function devices()
    {
        return $this->hasMany(Device::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function installments()
    {
        return $this->hasMany(Installment::class);
    }

    // ========== Accessors (الـ 360 View) ==========
    
    // إجمالي المشتريات (Lifetime Value)
    public function getLifetimeValueAttribute(): float
    {
        return (float) $this->sales()->where('status', 'completed')->sum('total_amount');
    }

    // الديون المستحقة المتبقية
    public function getPendingDuesAttribute(): float
    {
        return (float) $this->installments()->where('status', 'active')->sum('remaining_amount');
    }

    // آخر جهاز استلمه
    public function getLastDeviceAttribute()
    {
        return $this->devices()->latest('received_at')->first();
    }

    // عدد الأجهزة تحت الصيانة حالياً
    public function getActiveDevicesCountAttribute(): int
    {
        return $this->devices()
            ->whereHas('status', function ($q) {
                $q->where('is_final', false);
            })
            ->count();
    }
}