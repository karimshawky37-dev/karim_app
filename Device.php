<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Device extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'device_code', 'customer_id', 'brand', 'model', 'color', 'storage_capacity',
        'imei_1', 'imei_2', 'reported_issue', 'diagnosed_issue', 'current_status_id',
        'assigned_technician_id', 'received_by', 'intake_checklist', 'waiting_for_part',
        'rejection_reason', 'sale_id', 'received_at'
    ];

    protected $casts = [
        'intake_checklist' => 'array', // التحويل التلقائي من JSON
        'received_at' => 'datetime',
    ];

    // ========== العلاقات ==========
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function status()
    {
        return $this->belongsTo(DeviceStatus::class, 'current_status_id');
    }

    public function technician()
    {
        return $this->belongsTo(User::class, 'assigned_technician_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'received_by');
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function checklists()
    {
        return $this->hasMany(DeviceChecklist::class);
    }

    // ========== Accessors (مفيدة جداً) ==========
    
    // لون الحالة لعرضها في الـ UI
    public function getStatusColorAttribute(): string
    {
        return $this->status?->color ?? '#gray';
    }

    // الوقت المستغرق من الاستلام للتسليم (بالدقائق)
    public function getTurnaroundTimeAttribute(): ?int
    {
        if (!$this->received_at) return null;
        $end = $this->deleted_at ?? $this->updated_at ?? now();
        return $this->received_at->diffInMinutes($end);
    }

    // هل الجهاز لسه شغال عليه؟
    public function getIsActiveAttribute(): bool
    {
        return $this->status?->is_final == false;
    }
}