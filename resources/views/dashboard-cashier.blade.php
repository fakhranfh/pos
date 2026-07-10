@extends('layouts.app')

@section('title', 'Dashboard')

@php
    $topbarTitle = 'Dashboard';
@endphp

@section('app-content')
    <div class="space-y-space-lg">

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-space-lg">
            <!-- My Sales Today -->
            <div class="bg-surface border border-outline-variant rounded-lg p-space-lg hover:border-outline transition-colors duration-150">
                <div class="space-y-space-md">
                    <div class="flex items-center justify-between">
                        <span class="text-label-md text-secondary uppercase font-label-md">{{ __('My Sales Today') }}</span>
                        <span class="material-symbols-outlined text-success text-[20px]">attach_money</span>
                    </div>
                    <div>
                        <p class="font-headline-md text-headline-md text-on-surface">Rp {{ number_format($myTotalSalesToday, 0, ',', '.') }}</p>
                        <div class="flex items-center gap-space-xs mt-space-sm">
                            <span class="material-symbols-outlined text-[14px] text-secondary">calendar_today</span>
                            <p class="font-body-sm text-body-sm text-secondary">{{ now()->translatedFormat('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- My Transactions Today -->
            <div class="bg-surface border border-outline-variant rounded-lg p-space-lg hover:border-outline transition-colors duration-150">
                <div class="space-y-space-md">
                    <div class="flex items-center justify-between">
                        <span class="text-label-md text-secondary uppercase font-label-md">{{ __('My Transactions Today') }}</span>
                        <span class="material-symbols-outlined text-primary text-[20px]">receipt_long</span>
                    </div>
                    <div>
                        <p class="font-headline-md text-headline-md text-on-surface">{{ number_format($myTransactionsTodayCount) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Action -->
        <a href="{{ route('checkout.index') }}" class="flex items-center justify-between bg-primary text-on-primary rounded-lg p-space-lg hover:opacity-90 transition-opacity duration-150">
            <div class="flex items-center gap-space-md">
                <span class="material-symbols-outlined text-[24px]">point_of_sale</span>
                <span class="font-headline-sm text-headline-sm">{{ __('Start New Checkout') }}</span>
            </div>
            <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
        </a>

        <!-- My Recent Transactions -->
        <div class="bg-surface border border-outline-variant rounded-lg overflow-hidden">
            <div class="px-space-lg py-space-md border-b border-outline-variant flex items-center gap-space-md">
                <span class="material-symbols-outlined text-on-surface text-[20px]">history</span>
                <h2 class="font-headline-sm text-headline-sm text-on-surface">{{ __('My Recent Transactions') }}</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-outline-variant bg-surface-container-lowest">
                            <th scope="col" class="px-space-lg py-space-md text-left font-label-md text-label-md text-secondary uppercase">{{ __('Invoice') }}</th>
                            <th scope="col" class="px-space-lg py-space-md text-left font-label-md text-label-md text-secondary uppercase">{{ __('Total') }}</th>
                            <th scope="col" class="px-space-lg py-space-md text-left font-label-md text-label-md text-secondary uppercase">{{ __('Date') }}</th>
                            <th scope="col" class="px-space-lg py-space-md text-left font-label-md text-label-md text-secondary uppercase">{{ __('Status') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-outline-variant">
                        @forelse ($recentTransactions as $transaction)
                            <tr class="hover:bg-surface-container-lowest transition-colors duration-150">
                                <td class="px-space-lg py-space-md font-body-md text-on-surface">
                                    <a href="{{ route('checkout.receipt', $transaction) }}" class="hover:underline">{{ $transaction->invoice_number }}</a>
                                </td>
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
                                <td colspan="4" class="px-space-lg py-space-lg text-center font-body-md text-secondary">{{ __('No transactions yet.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
@endsection
