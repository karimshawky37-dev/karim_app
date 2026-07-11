<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Device;
use App\Models\Entity;
use App\Models\Transaction;
use App\Models\Wallet;
use App\Services\InvestmentService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $investmentService;

    public function __construct(InvestmentService $investmentService)
    {
        $this->investmentService = $investmentService;
    }

    public function index()
    {
        // بيانات معادلة الاستثمار
        $investmentBalance = $this->investmentService->getCurrentBalance();

        // إحصائيات سريعة
        $stats = [
            'total_devices' => Device::count(),
            'total_customers' => Entity::where('type', 'customer')->orWhere('type', 'both')->count(),
            'total_invoices' => Invoice::count(),
            'total_wallets' => Wallet::sum('balance'),
        ];

        // آخر الفواتير
        $recentInvoices = Invoice::with('entity')->latest()->limit(5)->get();

        return view('dashboard.index', compact('investmentBalance', 'stats', 'recentInvoices'));
    }
}