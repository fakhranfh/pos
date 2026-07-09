@extends('layouts.app')

@section('title', __('Transactions'))

@php
    $topbarTitle = __('Transactions');
@endphp

@section('app-content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                {{ __('Transactions') }}
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                {{ __('Manage your Transactions') }}
            </p>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded dark:bg-green-900 dark:border-green-600 dark:text-green-100">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded dark:bg-red-900 dark:border-red-600 dark:text-red-100">
                {{ session('error') }}
            </div>
        @endif

        <x-data-table
            title="{{ __('All Transactions') }}"
            tableId="transactionsTable"
            listUrl="{{ route('transactions.list') }}"
            :filters="[
                ['key' => 'invoice_number', 'label' => 'Invoice Number', 'type' => 'text'],
                ['key' => 'payment_method', 'label' => 'Payment Method', 'type' => 'enum', 'options' => ['cash' => 'Cash', 'other' => 'Other']],
                ['key' => 'status', 'label' => 'Status', 'type' => 'enum', 'options' => ['completed' => 'Completed', 'voided' => 'Voided']],
                ['key' => 'created', 'label' => 'Created', 'type' => 'datetime'],
            ]">
            <x-slot name="headers">
                <x-th-sort sortKey="id">{{ __('ID') }}</x-th-sort>
                <x-th-sort sortKey="invoice_number">{{ __('Invoice Number') }}</x-th-sort>
                <x-th-sort sortKey="payment_method">{{ __('Payment Method') }}</x-th-sort>
                <x-th-sort sortKey="status">{{ __('Status') }}</x-th-sort>
                <x-th-sort sortKey="created_at">{{ __('Created') }}</x-th-sort>
                <th class="px-6 py-3 text-right font-semibold">{{ __('Actions') }}</th>
            </x-slot>
        </x-data-table>
    </div>
</div>

<x-data-table-scripts />

@push('scripts')
<script>
function loadTransactionsTable() {
    loadTableData('transactionsTable', buildFilterUrl('transactionsTable'), function(item) {
        const row = document.createElement('tr');
        row.className = 'border-b hover:bg-gray-50 dark:hover:bg-gray-700';
        row.innerHTML = `
            <td class="px-6 py-3">${item.id}</td>
            <td class="px-6 py-3">${item.invoice_number}</td>
            <td class="px-6 py-3">${item.payment_method}</td>
            <td class="px-6 py-3">${item.status}</td>
            <td class="px-6 py-3">${item.created_at}</td>
            <td class="px-6 py-3 text-right">
                <div class="flex gap-2 justify-end">
                    <a href="${item.actions.show}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">{{ __('View') }}</a>
                </div>
            </td>
        `;
        return row;
    });
}
</script>
@endpush
@endsection