<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Entity;
use App\Models\DeviceStatus;
use App\Models\DeviceChecklist;
use App\Services\SearchService;
use App\Services\PartDetectionService;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    protected $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
        $this->middleware('auth');
    }

    // عرض قائمة الأجهزة مع البحث المباشر (Livewire سيتولى الباقي)
    public function index()
    {
        return view('devices.index');
    }

    // API للبحث المباشر (Livewire)
    public function search(Request $request)
    {
        $query = $request->get('q');
        $filters = $request->only(['status', 'technician']);
        $devices = $this->searchService->searchDevices($query, $filters);
        return response()->json($devices);
    }

    // عرض تفاصيل الجهاز
    public function show($id)
    {
        $device = Device::with(['customer', 'status', 'technician', 'checklists'])->findOrFail($id);
        return view('devices.show', compact('device'));
    }

    // استلام جهاز جديد (مع طباعة إيصال A4)
    public function store(Request $request)
    {
        // ... منطق الاستلام

        // بعد حفظ الجهاز، نعيد توجيه المستخدم لطباعة الإيصال
        return redirect()->route('devices.receipt', $device->id);
    }

    // طباعة إيصال الاستلام (A4)
    public function receipt($id)
    {
        $device = Device::with(['customer', 'status'])->findOrFail($id);
        return view('devices.receipt', compact('device'));
    }

    // طباعة ملصق حراري (Sticker)
    public function sticker($id)
    {
        $device = Device::with(['customer'])->findOrFail($id);
        return view('devices.sticker', compact('device'));
    }
}