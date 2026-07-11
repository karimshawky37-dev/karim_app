<?php

namespace App\Services;

use App\Models\Partner;
use App\Models\FinancialSummary;
use App\Models\ProfitDistribution;
use Illuminate\Support\Facades\DB;

class InvestmentCalculator
{
    public function calculate($monthYear)
    {
        // 1. حساب رأس المال ونسب الملكية
        $partnersData = Partner::getOwnershipPercentages();
        $totalCapital = Partner::sum('contribution');

        // 2. جلب أو إنشاء الملخص المالي
        $summary = FinancialSummary::firstOrCreate(
            ['month_year' => $monthYear],
            [
                'total_revenue' => 0,
                'total_operational_expenses' => 0,
                'total_cost_of_goods' => 0,
                'total_manager_salaries' => 0,
                'net_profit' => 0,
            ]
        );

        // 3. تحديث رواتب المديرين
        $totalManagerSalaries = Partner::where('is_manager', true)->sum('monthly_salary');
        $summary->total_manager_salaries = $totalManagerSalaries;

        // 4. حساب صافي الربح
        $netProfit = $summary->total_revenue
            - ($summary->total_operational_expenses
                + $summary->total_manager_salaries
                + $summary->total_cost_of_goods);

        $summary->net_profit = max(0, $netProfit);
        $summary->save();

        // 5. توزيع الأرباح
        $distributions = [];
        if ($summary->net_profit > 0 && $totalCapital > 0) {
            foreach ($partnersData as $id => $data) {
                $share = round(($data['percentage'] / 100) * $summary->net_profit, 2);
                ProfitDistribution::updateOrCreate(
                    [
                        'partner_id' => $id,
                        'financial_summary_id' => $summary->id,
                    ],
                    [
                        'ownership_percentage' => $data['percentage'],
                        'share_amount' => $share,
                    ]
                );
                $distributions[] = (object)[
                    'name' => $data['name'],
                    'percentage' => $data['percentage'],
                    'share' => $share,
                ];
            }
        }

        // 6. إحصائيات للكروت
        $stats = (object)[
            'total_capital' => $totalCapital,
            'net_profit' => $summary->net_profit,
            'manager_salaries' => $totalManagerSalaries,
            'total_expenses' => $summary->total_operational_expenses + $totalManagerSalaries + $summary->total_cost_of_goods,
        ];

        return [
            'partnersData' => $partnersData,
            'summary' => $summary,
            'distributions' => $distributions,
            'stats' => $stats,
        ];
    }
}