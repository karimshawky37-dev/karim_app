<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    protected $fillable = ['name', 'contribution', 'is_manager', 'monthly_salary'];

    public function distributions()
    {
        return $this->hasMany(ProfitDistribution::class);
    }

    // حساب نسب الملكية ديناميكياً
    public static function getOwnershipPercentages()
    {
        $total = self::sum('contribution');
        if ($total == 0) return [];

        return self::all()->mapWithKeys(function ($partner) use ($total) {
            return [
                $partner->id => [
                    'name' => $partner->name,
                    'contribution' => $partner->contribution,
                    'percentage' => round(($partner->contribution / $total) * 100, 2),
                    'is_manager' => $partner->is_manager,
                    'monthly_salary' => $partner->monthly_salary,
                ]
            ];
        })->toArray();
    }
}