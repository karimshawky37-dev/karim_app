<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FinancialSummary extends Model
{
    protected $fillable = [
        'month_year', 'total_revenue', 'total_operational_expenses',
        'total_cost_of_goods', 'total_manager_salaries', 'net_profit'
    ];

    public function distributions()
    {
        return $this->hasMany(ProfitDistribution::class);
    }
}