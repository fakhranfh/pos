@extends('layouts.app')

@section('title', __('Low Stock Alerts'))

@php
    $topbarTitle = __('Low Stock Alerts');
@endphp

@section('app-content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ __('Low Stock Alerts') }}
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    {{ __('Products at or below their low stock threshold') }}
                </p>
            </div>
            <a href="{{ route('products.index') }}"
                class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-50 transition ease-in-out duration-150 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                {{ __('Back to Products') }}
            </a>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded dark:bg-green-900 dark:border-green-600 dark:text-green-100">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
            @if ($items->isEmpty())
                <div class="p-6 text-center text-gray-600 dark:text-gray-400">
                    {{ __('No products are low on stock.') }}
                </div>
            @else
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-left font-semibold text-sm text-gray-700 dark:text-gray-300">{{ __('Sku') }}</th>
                            <th class="px-6 py-3 text-left font-semibold text-sm text-gray-700 dark:text-gray-300">{{ __('Name') }}</th>
                            <th class="px-6 py-3 text-left font-semibold text-sm text-gray-700 dark:text-gray-300">{{ __('Stock') }}</th>
                            <th class="px-6 py-3 text-left font-semibold text-sm text-gray-700 dark:text-gray-300">{{ __('Threshold') }}</th>
                            <th class="px-6 py-3 text-right font-semibold text-sm text-gray-700 dark:text-gray-300">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($items as $item)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-3">{{ $item->sku }}</td>
                                <td class="px-6 py-3">{{ $item->name }}</td>
                                <td class="px-6 py-3">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded text-sm font-medium
                                        {{ $item->stock <= 0 ? 'bg-red-100 text-red-700 dark:bg-red-900 dark:text-red-100' : 'bg-amber-100 text-amber-700 dark:bg-amber-900 dark:text-amber-100' }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86l-8.18 14.18A1.5 1.5 0 003.5 20h17a1.5 1.5 0 001.39-1.96L13.71 3.86a1.5 1.5 0 00-2.42 0z" />
                                        </svg>
                                        {{ $item->stock }} {{ $item->stock <= 0 ? __('(out of stock)') : __('(low)') }}
                                    </span>
                                </td>
                                <td class="px-6 py-3">{{ $item->low_stock_threshold }}</td>
                                <td class="px-6 py-3 text-right">
                                    <a href="{{ route('stock-movements.create', ['product_id' => $item->id]) }}"
                                        class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                        {{ __('Reorder / Adjust Stock') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
