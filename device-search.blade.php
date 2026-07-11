<div class="space-y-4">
    <!-- Search & Filters -->
    <div class="glass p-4 rounded-xl border border-white/5 flex flex-wrap gap-3 items-center">
        <input type="text" wire:model.live.debounce.300ms="search" placeholder="🔍 ابحث بالكود، الشركة، الموديل، أو العميل..." 
               class="flex-1 min-w-[200px] bg-transparent border border-white/10 rounded-lg px-4 py-2 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50">
        
        <select wire:model.live="statusFilter" class="bg-transparent border border-white/10 rounded-lg px-3 py-2 text-white">
            <option value="">كل الحالات</option>
            @foreach($statuses as $status)
            <option value="{{ $status->slug }}">{{ $status->name }}</option>
            @endforeach
        </select>

        <select wire:model.live="technicianFilter" class="bg-transparent border border-white/10 rounded-lg px-3 py-2 text-white">
            <option value="">كل الفنيين</option>
            @foreach($technicians as $tech)
            <option value="{{ $tech->id }}">{{ $tech->full_name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Results Table -->
    <div class="glass rounded-xl border border-white/10 overflow-hidden">
        <table class="w-full text-sm">
            <thead class="border-b border-white/5">
                <tr class="text-slate-400">
                    <th class="p-3 text-right">الكود</th>
                    <th class="p-3 text-right">الجهاز</th>
                    <th class="p-3 text-right">العميل</th>
                    <th class="p-3 text-right">الحالة</th>
                    <th class="p-3 text-right">الفني</th>
                    <th class="p-3 text-right">الإجراء</th>
                </tr>
            </thead>
            <tbody>
                @forelse($devices as $device)
                <tr class="border-b border-white/5 hover:bg-white/5 transition">
                    <td class="p-3 font-mono">{{ $device->device_code }}</td>
                    <td class="p-3">{{ $device->brand }} {{ $device->model }}</td>
                    <td class="p-3">{{ $device->customer->name ?? '' }}</td>
                    <td class="p-3">
                        <span class="px-2 py-1 rounded-full text-xs" style="background: {{ $device->status->color }}20; color: {{ $device->status->color }}">
                            {{ $device->status->name }}
                        </span>
                    </td>
                    <td class="p-3">{{ $device->technician->full_name ?? '—' }}</td>
                    <td class="p-3 flex gap-2">
                        <a href="{{ route('devices.show', $device->id) }}" class="text-blue-400 hover:text-blue-300"><i class="fas fa-eye"></i></a>
                        <a href="{{ route('devices.receipt', $device->id) }}" target="_blank" class="text-green-400 hover:text-green-300"><i class="fas fa-print"></i></a>
                        <!-- تم إزالة زر إنشاء فاتورة -->
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="p-6 text-center text-slate-400">لا توجد أجهزة</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>