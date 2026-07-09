@extends('layouts.app')

@section('title', __('Receipt'))

@php
    $topbarTitle = __('Receipt');
@endphp

@section('app-content')
<div class="max-w-lg mx-auto">
    @if (session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded dark:bg-green-900 dark:border-green-600 dark:text-green-100">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6" id="receipt">
        <div class="text-center mb-4">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">{{ __('Receipt') }}</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400">{{ $transaction->invoice_number }}</p>
            <p class="text-xs text-gray-400">{{ $transaction->formatted_created_at }}</p>
        </div>

        <div class="text-sm text-gray-600 dark:text-gray-400 mb-4 space-y-1">
            <div class="flex justify-between">
                <span>{{ __('Cashier') }}</span>
                <span>{{ $transaction->cashier->name ?? '-' }}</span>
            </div>
        </div>

        <table class="w-full text-sm mb-4">
            <thead>
                <tr class="border-b border-gray-200 dark:border-gray-700 text-left text-gray-500 dark:text-gray-400">
                    <th class="py-1">{{ __('Item') }}</th>
                    <th class="py-1 text-center">{{ __('Qty') }}</th>
                    <th class="py-1 text-right">{{ __('Total') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaction->items as $item)
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <td class="py-1">{{ $item->product_name }}</td>
                        <td class="py-1 text-center">{{ $item->quantity }}</td>
                        <td class="py-1 text-right">{{ number_format($item->line_total, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-sm space-y-1">
            <div class="flex justify-between">
                <span>{{ __('Subtotal') }}</span>
                <span>{{ number_format($transaction->subtotal, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span>{{ __('Discount') }}</span>
                <span>-{{ number_format($transaction->discount_amount, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between font-bold text-base border-t border-gray-200 dark:border-gray-700 pt-1">
                <span>{{ __('Total') }}</span>
                <span>{{ number_format($transaction->total, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span>{{ __('Paid') }} ({{ $transaction->payment_method?->label() }})</span>
                <span>{{ number_format($transaction->amount_tendered, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between">
                <span>{{ __('Change') }}</span>
                <span>{{ number_format($transaction->change_due, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    <div class="mt-4 flex gap-3 print:hidden">
        <button type="button" onclick="window.print()" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-blue-600 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700">
            {{ __('Print') }}
        </button>
        <a href="{{ route('checkout.index') }}" class="flex-1 inline-flex justify-center items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600">
            {{ __('New Sale') }}
        </a>
    </div>
</div>
@endsection
