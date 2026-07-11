<?php

namespace App\Http\Controllers;

use App\Models\Partner;
use App\Models\FinancialSummary;
use App\Models\ProfitDistribution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class InvestmentController extends Controller
{
    // عرض لوحة التحكم الرئيسية
    public function index(Request $request)
    {
        // الشهر المطلوب (افتراضي: الشهر الحالي)
        $monthYear = $request->input('month', now()->format('Y-m'));

        // 1. حساب رأس المال ونسب الملكية
        $partnersData = Partner::calculateOwnershipPercentages();
        $totalCapital = Partner::sum('contribution');

        // 2. جلب أو حساب الملخص المالي للشهر
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

        // تحديث رواتب المديرين (لضمان الدقة)
        $totalManagerSalaries = Partner::where('is_manager', true)->sum('monthly_salary');
        $summary->total_manager_salaries = $totalManagerSalaries;

        // 3. حساب صافي الربح (Net Profit)
        // الصيغة: (الإيرادات) - (المصاريف التشغيلية + رواتب المديرين + تكلفة البضاعة)
        $netProfit = $summary->total_revenue
            - ($summary->total_operational_expenses
                + $summary->total_manager_salaries
                + $summary->total_cost_of_goods);

        $summary->net_profit = max(0, $netProfit); // لا يقل عن صفر
        $summary->save();

        // 4. توزيع الأرباح على الشركاء
        $distributions = [];
        if ($summary->net_profit > 0 && $totalCapital > 0) {
            foreach ($partnersData as $id => $data) {
                $percentage = $data['percentage'];
                $share = round(($percentage / 100) * $summary->net_profit, 2);

                // تحديث أو إنشاء سجل التوزيع
                $dist = ProfitDistribution::updateOrCreate(
                    [
                        'partner_id' => $id,
                        'financial_summary_id' => $summary->id,
                    ],
                    [
                        'ownership_percentage' => $percentage,
                        'share_amount' => $share,
                    ]
                );
                $distributions[] = (object) [
                    'name' => $data['name'],
                    'percentage' => $percentage,
                    'share' => $share,
                    'is_manager' => $data['is_manager'],
                ];
            }
        }

        // إحصائيات سريعة للكروت
        $stats = (object) [
            'total_capital' => $totalCapital,
            'net_profit' => $summary->net_profit,
            'manager_salaries' => $totalManagerSalaries,
            'total_revenue' => $summary->total_revenue,
            'total_expenses' => $summary->total_operational_expenses + $totalManagerSalaries + $summary->total_cost_of_goods,
        ];

        // تمرير البيانات للـ View
        return view('investment.dashboard', compact(
            'partnersData',
            'summary',
            'distributions',
            'stats',
            'monthYear',
            'totalCapital'
        ));
    }

    // إضافة شريك جديد
    public function storePartner(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contribution' => 'required|numeric|min:0.01',
            'is_manager' => 'nullable|boolean',
            'monthly_salary' => 'nullable|numeric|min:0',
        ]);

        Partner::create([
            'name' => $validated['name'],
            'contribution' => $validated['contribution'],
            'is_manager' => $request->has('is_manager') ? true : false,
            'monthly_salary' => $validated['monthly_salary'] ?? 0,
        ]);

        return redirect()->route('investment.dashboard')->with('success', 'تم إضافة الشريك بنجاح!');
    }

    // تحديث بيانات شريك
    public function updatePartner(Request $request, $id)
    {
        $partner = Partner::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contribution' => 'required|numeric|min:0.01',
            'is_manager' => 'nullable|boolean',
            'monthly_salary' => 'nullable|numeric|min:0',
        ]);

        $partner->update([
            'name' => $validated['name'],
            'contribution' => $validated['contribution'],
            'is_manager' => $request->has('is_manager') ? true : false,
            'monthly_salary' => $validated['monthly_salary'] ?? 0,
        ]);

        return redirect()->route('investment.dashboard')->with('success', 'تم تحديث بيانات الشريك!');
    }

    // حذف شريك
    public function deletePartner($id)
    {
        $partner = Partner::findOrFail($id);
        $partner->delete();

        return redirect()->route('investment.dashboard')->with('success', 'تم حذف الشريك!');
    }

    // تحديث الأرقام المالية للشهر (الإيرادات، المصاريف، تكلفة البضاعة)
    public function updateFinancials(Request $request)
    {
        $validated = $request->validate([
            'month_year' => 'required|date_format:Y-m',
            'total_revenue' => 'nullable|numeric|min:0',
            'total_operational_expenses' => 'nullable|numeric|min:0',
            'total_cost_of_goods' => 'nullable|numeric|min:0',
        ]);

        $summary = FinancialSummary::firstOrCreate(
            ['month_year' => $validated['month_year']],
            [
                'total_revenue' => 0,
                'total_operational_expenses' => 0,
                'total_cost_of_goods' => 0,
                'total_manager_salaries' => 0,
                'net_profit' => 0,
            ]
        );

        $summary->update([
            'total_revenue' => $validated['total_revenue'] ?? 0,
            'total_operational_expenses' => $validated['total_operational_expenses'] ?? 0,
            'total_cost_of_goods' => $validated['total_cost_of_goods'] ?? 0,
        ]);

        return redirect()->route('investment.dashboard', ['month' => $validated['month_year']])
            ->with('success', 'تم تحديث الأرقام المالية!');
    }
}