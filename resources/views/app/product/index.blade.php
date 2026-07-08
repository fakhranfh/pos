@extends('layouts.app')

@section('title', __('Products'))

@php
    $topbarTitle = __('Products');
@endphp

@section('app-content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ __('Products') }}
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    {{ __('Manage your Products') }}
                </p>
            </div>
            <a href="{{ route('products.create') }}"
                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-50 transition ease-in-out duration-150">
                <span class="mr-2 font-bold text-lg">+</span>
                {{ __('New Products') }}
            </a>
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
            title="{{ __('All Products') }}"
            tableId="productsTable"
            listUrl="{{ route('products.list') }}"
            :filters="[
                ['key' => 'sku', 'label' => 'Sku', 'type' => 'text'],
                ['key' => 'name', 'label' => 'Name', 'type' => 'text'],
                ['key' => 'created', 'label' => 'Created', 'type' => 'datetime'],
            ]">
            <x-slot name="headers">
                <x-th-sort sortKey="id">{{ __('ID') }}</x-th-sort>
                <x-th-sort sortKey="sku">{{ __('Sku') }}</x-th-sort>
                <x-th-sort sortKey="name">{{ __('Name') }}</x-th-sort>
                <x-th-sort sortKey="created_at">{{ __('Created') }}</x-th-sort>
                <th class="px-6 py-3 text-right font-semibold">{{ __('Actions') }}</th>
            </x-slot>
        </x-data-table>
    </div>
</div>

<x-success-modal />

<x-confirm-modal
    id="confirmDeleteModal"
    title="{{ __('Are you sure?') }}"
    message="{{ __('This action cannot be undone.') }}"
    cancelLabel="{{ __('Cancel') }}"
    confirmLabel="{{ __('Delete') }}"
    iconBgColor="red"
    confirmBtnColor="red">
    <x-slot name="icon">
        <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
        </svg>
    </x-slot>
</x-confirm-modal>

<x-modal-scripts />
<x-data-table-scripts />

@push('scripts')
<script>
function loadProductsTable() {
    loadTableData('productsTable', buildFilterUrl('productsTable'), function(item) {
        const row = document.createElement('tr');
        row.className = 'border-b hover:bg-gray-50 dark:hover:bg-gray-700';
        row.innerHTML = `
            <td class="px-6 py-3">${item.id}</td>
            <td class="px-6 py-3">${item.sku}</td>
            <td class="px-6 py-3">${item.name}</td>
            <td class="px-6 py-3">${item.created_at}</td>
            <td class="px-6 py-3 text-right">
                <div class="flex gap-2 justify-end">
                    <a href="${item.actions.show}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">{{ __('View') }}</a>
                    <a href="${item.actions.edit}" class="text-amber-600 hover:text-amber-900 text-sm font-medium">{{ __('Edit') }}</a>
                    <button onclick="deleteItem('${item.actions.delete}')" class="text-red-600 hover:text-red-900 text-sm font-medium">{{ __('Delete') }}</button>
                </div>
            </td>
        `;
        return row;
    });
}
</script>
@endpush
@endsection