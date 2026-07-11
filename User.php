<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username', 'email', 'password_hash', 'full_name', 'phone', 'role', 'is_active'
    ];

    protected $hidden = [
        'password_hash',
    ];

    public function getAuthPassword()
    {
        return $this->password_hash;
    }

    // العلاقات
    public function shifts()
    {
        return $this->hasMany(Shift::class);
    }

    public function currentShift()
    {
        return $this->hasOne(Shift::class)->where('status', 'active');
    }

    public function attendance()
    {
        return $this->hasMany(Attendance::class);
    }

    public function createdInvoices()
    {
        return $this->hasMany(Invoice::class, 'created_by');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }
}