@extends('layouts.app')

@section('title', __('Sales Report'))

@php
    $topbarTitle = __('Sales Report');
@endphp

@section('app-content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                {{ __('Sales Report') }}
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                {{ __('Total sales and best-selling products for a date range') }}
            </p>
        </div>

        <!-- Filter -->
        <form id="salesReportFilterForm" class="mb-6 bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 flex flex-wrap items-end gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('From') }}</label>
                <input type="date" id="salesReportDateFrom" value="{{ $dateFrom }}"
                    class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ __('To') }}</label>
                <input type="date" id="salesReportDateTo" value="{{ $dateTo }}"
                    class="rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm">
            </div>
            <button type="submit"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-800 focus:outline-none focus:ring ring-blue-300">
                {{ __('Apply') }}
            </button>
        </form>

        <!-- Totals -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Sales') }}</p>
                <p id="salesReportTotalSales" class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                    Rp {{ number_format($totalSales, 0, ',', '.') }}
                </p>
            </div>
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('Total Transactions') }}</p>
                <p id="salesReportTotalTransactions" class="mt-1 text-2xl font-bold text-gray-900 dark:text-white">
                    {{ $totalTransactions }}
                </p>
            </div>
        </div>

        <!-- Top products -->
        <input type="hidden" data-filter-table="salesReportProductsTable" data-filter-key="date_from" value="{{ $dateFrom }}">
        <input type="hidden" data-filter-table="salesReportProductsTable" data-filter-key="date_to" value="{{ $dateTo }}">

        <x-data-table
            title="{{ __('Best-Selling Products') }}"
            tableId="salesReportProductsTable"
            listUrl="{{ route('reports.sales.products') }}">
            <x-slot name="headers">
                <x-th-sort sortKey="product_name">{{ __('Product') }}</x-th-sort>
                <x-th-sort sortKey="quantity">{{ __('Quantity Sold') }}</x-th-sort>
                <x-th-sort sortKey="revenue">{{ __('Revenue') }}</x-th-sort>
            </x-slot>
        </x-data-table>
    </div>
</div>

<x-data-table-scripts />

@push('scripts')
<script>
function loadSalesReportProductsTable() {
    loadTableData('salesReportProductsTable', buildFilterUrl('salesReportProductsTable'), function(item) {
        const row = document.createElement('tr');
        row.className = 'border-b hover:bg-gray-50 dark:hover:bg-gray-700';
        row.innerHTML = `
            <td class="px-6 py-3">${item.name}</td>
            <td class="px-6 py-3">${item.quantity}</td>
            <td class="px-6 py-3">Rp ${Number(item.revenue).toLocaleString('id-ID', { maximumFractionDigits: 0 })}</td>
        `;
        return row;
    });
}

function loadSalesReportTotals() {
    const totalSalesEl = document.getElementById('salesReportTotalSales');
    const totalTransactionsEl = document.getElementById('salesReportTotalTransactions');

    totalSalesEl.innerHTML = '<div class="h-7 w-32 bg-gray-200 dark:bg-gray-600 rounded animate-pulse"></div>';
    totalTransactionsEl.innerHTML = '<div class="h-7 w-16 bg-gray-200 dark:bg-gray-600 rounded animate-pulse"></div>';

    const params = new URLSearchParams({
        date_from: document.getElementById('salesReportDateFrom').value,
        date_to: document.getElementById('salesReportDateTo').value,
    });

    fetch(`{{ route('reports.sales.totals') }}?${params.toString()}`, { credentials: 'include' })
        .then(r => r.json())
        .then(data => {
            totalSalesEl.textContent = 'Rp ' + Number(data.totalSales).toLocaleString('id-ID', { maximumFractionDigits: 0 });
            totalTransactionsEl.textContent = data.totalTransactions;
        })
        .catch(e => {
            console.error('Failed to load sales totals:', e);
            totalSalesEl.textContent = '{{ __('Failed to load') }}';
            totalTransactionsEl.textContent = '{{ __('Failed to load') }}';
        });
}

function applySalesReportFilter() {
    const dateFrom = document.getElementById('salesReportDateFrom').value;
    const dateTo = document.getElementById('salesReportDateTo').value;

    document.querySelector('[data-filter-table="salesReportProductsTable"][data-filter-key="date_from"]').value = dateFrom;
    document.querySelector('[data-filter-table="salesReportProductsTable"][data-filter-key="date_to"]').value = dateTo;

    loadSalesReportTotals();
    applyFilters('salesReportProductsTable');
}

document.getElementById('salesReportFilterForm').addEventListener('submit', function (e) {
    e.preventDefault();
    applySalesReportFilter();
});
</script>
@endpush
@endsection
