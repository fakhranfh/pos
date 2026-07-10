@extends('layouts.app')

@section('title', $item->invoice_number ?? __('View Transactions'))

@php
    $topbarTitle = $item->invoice_number ?? __('View Transactions');
@endphp

@section('app-content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex space-x-2 text-sm text-gray-600 dark:text-gray-400">
                <li>
                    <a href="{{ route('transactions.index') }}" class="hover:text-gray-900 dark:hover:text-white">
                        {{ __('Transactions') }}
                    </a>
                </li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900 dark:text-white">{{ $item->invoice_number ?? $item->id }}</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                {{ $item->invoice_number ?? __('View Transactions') }}
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                {{ __('Details for this record') }}
            </p>
        </div>

        <!-- Content Card -->
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-3">
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ __('Details') }}
                        </h3>
                    </div>
                    <div class="px-6">
                        <dl class="divide-y divide-gray-200 dark:divide-gray-700">
        <div class="border-t border-gray-200 dark:border-gray-700 py-4">
          <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">@lang('Invoice Number')</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white">
            {{ $item->invoice_number }}
          </dd>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 py-4">
          <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">@lang('Cashier')</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white">
            {{ $item->cashier?->name }}
          </dd>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 py-4">
          <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">@lang('Subtotal')</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white">
            Rp {{ number_format($item->subtotal, 0, ',', '.') }}
          </dd>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 py-4">
          <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">@lang('Discount Amount')</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white">
            Rp {{ number_format($item->discount_amount, 0, ',', '.') }}
          </dd>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 py-4">
          <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">@lang('Tax Amount')</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white">
            Rp {{ number_format($item->tax_amount, 0, ',', '.') }}
          </dd>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 py-4">
          <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">@lang('Total')</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white">
            Rp {{ number_format($item->total, 0, ',', '.') }}
          </dd>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 py-4">
          <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">@lang('Amount Tendered')</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white">
            Rp {{ number_format($item->amount_tendered, 0, ',', '.') }}
          </dd>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 py-4">
          <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">@lang('Change Due')</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white">
            Rp {{ number_format($item->change_due, 0, ',', '.') }}
          </dd>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 py-4">
          <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">@lang('Payment Method')</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white">
            {{ $item->payment_method?->label() }}
          </dd>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 py-4">
          <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">@lang('Status')</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white">
            {{ $item->status?->label() }}
          </dd>
        </div>
                        </dl>
                    </div>

                    <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                            {{ __('Transaction Items') }}
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                                <thead>
                                    <tr class="text-left text-gray-600 dark:text-gray-400">
                                        <th class="px-4 py-2 font-medium">{{ __('Product') }}</th>
                                        <th class="px-4 py-2 font-medium text-right">{{ __('Unit Price') }}</th>
                                        <th class="px-4 py-2 font-medium text-right">{{ __('Quantity') }}</th>
                                        <th class="px-4 py-2 font-medium text-right">{{ __('Discount') }}</th>
                                        <th class="px-4 py-2 font-medium text-right">{{ __('Line Total') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    @forelse ($item->items as $transactionItem)
                                        <tr class="text-gray-900 dark:text-white">
                                            <td class="px-4 py-2">{{ $transactionItem->product_name }}</td>
                                            <td class="px-4 py-2 text-right">Rp {{ number_format($transactionItem->unit_price, 0, ',', '.') }}</td>
                                            <td class="px-4 py-2 text-right">{{ $transactionItem->quantity }}</td>
                                            <td class="px-4 py-2 text-right">Rp {{ number_format($transactionItem->discount_amount, 0, ',', '.') }}</td>
                                            <td class="px-4 py-2 text-right">Rp {{ number_format($transactionItem->line_total, 0, ',', '.') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-4 py-4 text-center text-gray-500 dark:text-gray-400">
                                                {{ __('No transaction items found.') }}
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        {{ __('Information') }}
                    </h3>

                    <div class="space-y-4 text-sm">
                        <div>
                            <p class="text-gray-500 dark:text-gray-400">{{ __('Created') }}</p>
                            <p class="text-gray-900 dark:text-white font-medium">
                                {{ $item->formatted_created_at }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-500 dark:text-gray-400">{{ __('Updated') }}</p>
                            <p class="text-gray-900 dark:text-white font-medium">
                                {{ $item->formatted_updated_at }}
                            </p>
                        </div>
                    </div>

                    <hr class="my-4 border-gray-200 dark:border-gray-700">

                    <div class="space-y-2">
                        <a href="{{ route('transactions.index') }}"
                            class="w-full block px-4 py-2 bg-gray-200 text-gray-700 border border-gray-300 rounded-md font-semibold text-sm hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition text-center">
                            {{ __('Back to List') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection