@extends('layouts.app')

@section('page_title', __('app.dashboard'))

@section('content')
<div class="space-y-6 fade-in">

    <!-- ===== Investment Equation Card ===== -->
    <div class="glass-card p-6">
        <div class="flex flex-wrap justify-between items-start gap-4">
            <div>
                <h2 class="text-lg font-medium text-[var(--text-secondary)]">
                    <i class="fas fa-coins text-blue-500"></i> {{ __('app.investment_equation') }}
                </h2>
                <p class="text-3xl font-bold text-[var(--text-primary)] mt-1">
                    {{ number_format($investmentBalance ?? 0, 2) }} {{ __('app.currency') }}
                </p>
                <p class="text-sm text-[var(--text-muted)] mt-1">
                    {{ __('app.last_updated') }}: {{ now()->format('Y-m-d H:i') }}
                </p>
            </div>
            <div class="flex flex-wrap gap-4 text-sm bg-[var(--border-color)] p-4 rounded-xl">
                <div>
                    <span class="text-[var(--text-muted)]">{{ __('app.assets') }}:</span>
                    <span class="text-green-500 font-semibold">{{ number_format($stats['total_wallets'] ?? 0, 2) }}</span>
                </div>
                <div>
                    <span class="text-[var(--text-muted)]">{{ __('app.liabilities') }}:</span>
                    <span class="text-red-500 font-semibold">{{ number_format($stats['total_liabilities'] ?? 0, 2) }}</span>
                </div>
                <div>
                    <span class="text-[var(--text-muted)]">{{ __('app.net_investment') }}:</span>
                    <span class="text-blue-500 font-semibold">{{ number_format(($stats['total_wallets'] ?? 0) - ($stats['total_liabilities'] ?? 0), 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- ===== Quick Stats ===== -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="glass-card p-5 text-center">
            <p class="text-sm text-[var(--text-muted)]">{{ __('app.total_devices') }}</p>
            <p class="text-2xl font-bold text-[var(--text-primary)]">{{ $stats['total_devices'] ?? 0 }}</p>
        </div>
        <div class="glass-card p-5 text-center">
            <p class="text-sm text-[var(--text-muted)]">{{ __('app.total_customers') }}</p>
            <p class="text-2xl font-bold text-[var(--text-primary)]">{{ $stats['total_customers'] ?? 0 }}</p>
        </div>
        <div class="glass-card p-5 text-center">
            <p class="text-sm text-[var(--text-muted)]">{{ __('app.total_invoices') }}</p>
            <p class="text-2xl font-bold text-[var(--text-primary)]">{{ $stats['total_invoices'] ?? 0 }}</p>
        </div>
        <div class="glass-card p-5 text-center">
            <p class="text-sm text-[var(--text-muted)]">{{ __('app.wallet_balance') }}</p>
            <p class="text-2xl font-bold text-[var(--text-primary)]">{{ number_format($stats['total_wallets'] ?? 0, 2) }}</p>
        </div>
    </div>

    <!-- ===== Recent Invoices ===== -->
    <div class="glass-card p-6">
        <h3 class="text-lg font-medium text-[var(--text-primary)] mb-4">
            <i class="fas fa-file-invoice text-blue-500"></i> {{ __('app.recent_invoices') }}
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-[var(--text-muted)] border-b border-[var(--border-color)]">
                        <th class="text-right p-3">#</th>
                        <th class="text-right p-3">{{ __('app.customer') }}</th>
                        <th class="text-right p-3">{{ __('app.total') }}</th>
                        <th class="text-right p-3">{{ __('app.status') }}</th>
                        <th class="text-right p-3">{{ __('app.date') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentInvoices ?? [] as $invoice)
                    <tr class="border-b border-[var(--border-color)] hover:bg-[var(--border-color)] transition">
                        <td class="p-3 font-mono">{{ $invoice->invoice_number }}</td>
                        <td class="p-3">{{ $invoice->entity->name ?? '—' }}</td>
                        <td class="p-3 font-semibold">{{ number_format($invoice->total_amount, 2) }}</td>
                        <td class="p-3">
                            <span class="badge-glass 
                                {{ $invoice->status === 'paid' ? 'badge-success' : 
                                   ($invoice->status === 'pending' ? 'badge-warning' : 
                                   ($invoice->status === 'overdue' ? 'badge-danger' : 'badge-neutral')) }}">
                                {{ __("status.{$invoice->status}") }}
                            </span>
                        </td>
                        <td class="p-3 text-[var(--text-muted)]">{{ $invoice->invoice_date->format('Y-m-d') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="p-6 text-center text-[var(--text-muted)]">{{ __('app.no_invoices') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection