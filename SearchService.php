<?php

namespace App\Services;

use App\Models\Device;
use App\Models\Entity;
use App\Models\Invoice;
use Illuminate\Support\Facades\DB;

class SearchService
{
    /**
     * البحث عن الأجهزة بسرعة (Live Search)
     */
    public function searchDevices($query, $filters = [])
    {
        $search = Device::with(['customer', 'status', 'technician'])
            ->where(function ($q) use ($query) {
                $q->where('device_code', 'LIKE', "%{$query}%")
                    ->orWhere('brand', 'LIKE', "%{$query}%")
                    ->orWhere('model', 'LIKE', "%{$query}%")
                    ->orWhere('imei_1', 'LIKE', "%{$query}%")
                    ->orWhere('imei_2', 'LIKE', "%{$query}%")
                    ->orWhereHas('customer', function ($cq) use ($query) {
                        $cq->where('name', 'LIKE', "%{$query}%")
                            ->orWhere('phone', 'LIKE', "%{$query}%");
                    });
            });

        if (!empty($filters['status'])) {
            $search->whereHas('status', function ($sq) use ($filters) {
                $sq->where('slug', $filters['status']);
            });
        }

        if (!empty($filters['technician'])) {
            $search->where('assigned_technician_id', $filters['technician']);
        }

        return $search->limit(20)->get();
    }

    /**
     * البحث عن العملاء والموردين مع إجمالي الديون
     */
    public function searchEntities($query, $type = 'customer')
    {
        $entities = Entity::where('type', $type)
            ->orWhere('type', 'both')
            ->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('phone', 'LIKE', "%{$query}%")
                    ->orWhere('email', 'LIKE', "%{$query}%");
            })
            ->withSum(['invoices as total_debt' => function ($q) use ($type) {
                $q->where('type', $type === 'customer' ? 'sale' : 'purchase')
                    ->whereNotIn('status', ['paid', 'cancelled']);
            }], 'remaining_amount')
            ->limit(20)
            ->get();

        return $entities;
    }

    /**
     * جلب ديون العملاء للتقرير (مع تجميع)
     */
    public function getCustomerDebts($search = null)
    {
        $query = Entity::whereIn('type', ['customer', 'both'])
            ->withSum(['invoices as total_debt' => function ($q) {
                $q->where('type', 'sale')
                    ->whereNotIn('status', ['paid', 'cancelled']);
            }], 'remaining_amount')
            ->having('total_debt', '>', 0);

        if ($search) {
            $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('phone', 'LIKE', "%{$search}%");
        }

        return $query->orderBy('total_debt', 'desc')->get();
    }
}