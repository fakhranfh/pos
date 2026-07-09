@extends('layouts.app')

@section('title', 'Dashboard')

@php
    $topbarTitle = 'Dashboard';
@endphp

@section('app-content')
    <div class="space-y-space-lg">

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-space-lg">
            <!-- Today's Sales -->
            <div class="bg-surface border border-outline-variant rounded-lg p-space-lg hover:border-outline transition-colors duration-150">
                <div class="space-y-space-md">
                    <div class="flex items-center justify-between">
                        <span class="text-label-md text-secondary uppercase font-label-md">{{ __("Today's Sales") }}</span>
                        <span class="material-symbols-outlined text-success text-[20px]">attach_money</span>
                    </div>
                    <div>
                        <p class="font-headline-md text-headline-md text-on-surface">Rp {{ number_format($totalSalesToday, 0, ',', '.') }}</p>
                        <div class="flex items-center gap-space-xs mt-space-sm">
                            <span class="material-symbols-outlined text-[14px] text-secondary">calendar_today</span>
                            <p class="font-body-sm text-body-sm text-secondary">{{ now()->translatedFormat('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Today's Transactions -->
            <div class="bg-surface border border-outline-variant rounded-lg p-space-lg hover:border-outline transition-colors duration-150">
                <div class="space-y-space-md">
                    <div class="flex items-center justify-between">
                        <span class="text-label-md text-secondary uppercase font-label-md">{{ __('Transactions Today') }}</span>
                        <span class="material-symbols-outlined text-primary text-[20px]">receipt_long</span>
                    </div>
                    <div>
                        <p class="font-headline-md text-headline-md text-on-surface">{{ number_format($totalTransactionsToday) }}</p>
                        <div class="flex items-center gap-space-xs mt-space-sm">
                            <a href="{{ route('transactions.index') }}" class="font-body-sm text-body-sm text-primary hover:underline">{{ __('View all transactions') }}</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Low Stock -->
            <div class="bg-surface border border-outline-variant rounded-lg p-space-lg hover:border-outline transition-colors duration-150">
                <div class="space-y-space-md">
                    <div class="flex items-center justify-between">
                        <span class="text-label-md text-secondary uppercase font-label-md">{{ __('Low Stock Products') }}</span>
                        <span class="material-symbols-outlined {{ $lowStockCount > 0 ? 'text-error' : 'text-success' }} text-[20px]">inventory_2</span>
                    </div>
                    <div>
                        <p class="font-headline-md text-headline-md {{ $lowStockCount > 0 ? 'text-error' : 'text-on-surface' }}">{{ number_format($lowStockCount) }}</p>
                        <div class="flex items-center gap-space-xs mt-space-sm">
                            <a href="{{ route('products.low-stock') }}" class="font-body-sm text-body-sm text-primary hover:underline">{{ __('View low stock list') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-space-lg">
            <!-- Best-Selling Products Today -->
            <div class="bg-surface border border-outline-variant rounded-lg overflow-hidden">
                <div class="px-space-lg py-space-md border-b border-outline-variant flex items-center justify-between">
                    <div class="flex items-center gap-space-md">
                        <span class="material-symbols-outlined text-on-surface text-[20px]">trending_up</span>
                        <h2 class="font-headline-sm text-headline-sm text-on-surface">{{ __('Best-Selling Products Today') }}</h2>
                    </div>
                    <a href="{{ route('reports.sales') }}" class="font-body-sm text-body-sm text-primary hover:underline">{{ __('Full report') }}</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-outline-variant bg-surface-container-lowest">
                                <th scope="col" class="px-space-lg py-space-md text-left font-label-md text-label-md text-secondary uppercase">{{ __('Product') }}</th>
                                <th scope="col" class="px-space-lg py-space-md text-left font-label-md text-label-md text-secondary uppercase">{{ __('Qty Sold') }}</th>
                                <th scope="col" class="px-space-lg py-space-md text-left font-label-md text-label-md text-secondary uppercase">{{ __('Revenue') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            @forelse ($topProducts as $item)
                                <tr class="hover:bg-surface-container-lowest transition-colors duration-150">
                                    <td class="px-space-lg py-space-md font-body-md text-on-surface">{{ $item->product_name }}</td>
                                    <td class="px-space-lg py-space-md font-body-md text-secondary">{{ (int) $item->quantity }}</td>
                                    <td class="px-space-lg py-space-md font-body-md text-secondary">Rp {{ number_format($item->revenue, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-space-lg py-space-lg text-center font-body-md text-secondary">{{ __('No sales recorded today.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Low Stock Products -->
            <div class="bg-surface border border-outline-variant rounded-lg overflow-hidden">
                <div class="px-space-lg py-space-md border-b border-outline-variant flex items-center justify-between">
                    <div class="flex items-center gap-space-md">
                        <span class="material-symbols-outlined text-on-surface text-[20px]">warning</span>
                        <h2 class="font-headline-sm text-headline-sm text-on-surface">{{ __('Low Stock Products') }}</h2>
                    </div>
                    <a href="{{ route('products.low-stock') }}" class="font-body-sm text-body-sm text-primary hover:underline">{{ __('View all') }}</a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead>
                            <tr class="border-b border-outline-variant bg-surface-container-lowest">
                                <th scope="col" class="px-space-lg py-space-md text-left font-label-md text-label-md text-secondary uppercase">{{ __('Product') }}</th>
                                <th scope="col" class="px-space-lg py-space-md text-left font-label-md text-label-md text-secondary uppercase">{{ __('Stock') }}</th>
                                <th scope="col" class="px-space-lg py-space-md text-left font-label-md text-label-md text-secondary uppercase">{{ __('Threshold') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-outline-variant">
                            @forelse ($lowStockProducts as $item)
                                <tr class="hover:bg-surface-container-lowest transition-colors duration-150">
                                    <td class="px-space-lg py-space-md font-body-md text-on-surface">{{ $item->name }}</td>
                                    <td class="px-space-lg py-space-md">
                                        <div class="inline-flex items-center gap-space-xs px-space-sm py-space-xs rounded-full {{ $item->stock <= 0 ? 'bg-error/10 border border-error/20' : 'bg-warning/10 border border-warning/20' }}">
                                            <span class="font-label-md text-label-md {{ $item->stock <= 0 ? 'text-error' : 'text-warning' }}">{{ $item->stock }}</span>
                                        </div>
                                    </td>
                                    <td class="px-space-lg py-space-md font-body-md text-secondary">{{ $item->low_stock_threshold }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-space-lg py-space-lg text-center font-body-md text-secondary">{{ __('No products are low on stock.') }}</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Recent Transactions -->
        <div class="bg-surface border border-outline-variant rounded-lg overflow-hidden">
            <div class="px-space-lg py-space-md border-b border-outline-variant flex items-center justify-between">
                <div class="flex items-center gap-space-md">
                    <span class="material-symbols-outlined text-on-surface text-[20px]">history</span>
                    <h2 class="font-headline-sm text-headline-sm text-on-surface">{{ __('Recent Transactions') }}</h2>
                </div>
                <a href="{{ route('transactions.index') }}" class="font-body-sm text-body-sm text-primary hover:underline">{{ __('View all') }}</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-outline-variant bg-surface-container-lowest">
                            <th scope="col" class="px-space-lg py-space-md text-left font-label-md text-label-md text-secondary uppercase">{{ __('Invoice') }}</th>
                            <th scope="col" class="px-space-lg py-space-md text-left font-label-md text-label-md text-secondary uppercase">{{ __('Cashier') }}</th>
                            <th scope="col" class="px-space-lg py-space-md text-left font-label-md text-label-md text-secondary uppercase">{{ __('Total') }}</th>
                            <th scope="col" class="px-space-lg py-space-md text-left font-label-md text-label-md text-secondary uppercase">{{ __('Date') }}</th>
                            <th scope="col" class="px-space-lg py-space-md text-left font-label-md text-label-md text-secondary uppercase">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse ($recentTransactions as $transaction)
                            <tr class="hover:bg-surface-container-lowest transition-colors duration-150">
                                <td class="px-space-lg py-space-md font-body-md text-on-surface">
                                    <a href="{{ route('transactions.show', $transaction) }}" class="hover:underline">{{ $transaction->invoice_number }}</a>
                                </td>
                                <td class="px-space-lg py-space-md font-body-md text-secondary">{{ $transaction->cashier?->name ?? '-' }}</td>
                                <td class="px-space-lg py-space-md font-body-md text-secondary">Rp {{ number_format($transaction->total, 0, ',', '.') }}</td>
                                <td class="px-space-lg py-space-md font-body-md text-secondary">{{ $transaction->created_at?->format('d M Y H:i') }}</td>
                                <td class="px-space-lg py-space-md">
                                    @if ($transaction->status->value === 'completed')
                                        <div class="inline-flex items-center gap-space-xs px-space-sm py-space-xs rounded-full bg-success/10 border border-success/20">
                                            <span class="material-symbols-outlined text-[14px] text-success" data-weight="fill">check_circle</span>
                                            <span class="font-label-md text-label-md text-success">{{ $transaction->status->label() }}</span>
                                        </div>
                                    @else
                                        <div class="inline-flex items-center gap-space-xs px-space-sm py-space-xs rounded-full bg-error/10 border border-error/20">
                                            <span class="material-symbols-outlined text-[14px] text-error" data-weight="fill">cancel</span>
                                            <span class="font-label-md text-label-md text-error">{{ $transaction->status->label() }}</span>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-space-lg py-space-lg text-center font-body-md text-secondary">{{ __('No transactions yet.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
