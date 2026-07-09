@extends('layouts.app')

@section('title', __('Notifications'))

@php
    $topbarTitle = __('Notifications');
@endphp

@section('app-content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ __('Notifications') }}
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    {{ __('All notifications sent to you') }}
                </p>
            </div>
        </div>

        <x-data-table
            title="{{ __('All Notifications') }}"
            tableId="notificationsTable"
            listUrl="{{ route('notifications.list') }}">
            <x-slot name="headers">
                <th class="px-6 py-3 text-left font-semibold">{{ __('Message') }}</th>
                <th class="px-6 py-3 text-left font-semibold">{{ __('Status') }}</th>
                <x-th-sort sortKey="created_at">{{ __('Received') }}</x-th-sort>
                <th class="px-6 py-3 text-right font-semibold">{{ __('Actions') }}</th>
            </x-slot>
        </x-data-table>
    </div>
</div>

<x-data-table-scripts />

@push('scripts')
<script>
function loadNotificationsTable() {
    loadTableData('notificationsTable', buildFilterUrl('notificationsTable'), function(item) {
        const row = document.createElement('tr');
        row.className = 'border-b hover:bg-gray-50 dark:hover:bg-gray-700' + (item.read ? '' : ' bg-blue-50 dark:bg-blue-900/20');
        row.innerHTML = `
            <td class="px-6 py-3">${item.message}</td>
            <td class="px-6 py-3">${item.read ? '{{ __('Read') }}' : '<span class="font-semibold text-blue-600">{{ __('Unread') }}</span>'}</td>
            <td class="px-6 py-3">${item.created_at}</td>
            <td class="px-6 py-3 text-right">
                <a href="${item.actions.view}" class="text-blue-600 hover:text-blue-900 text-sm font-medium">{{ __('View Product') }}</a>
            </td>
        `;
        return row;
    });
}
</script>
@endpush
@endsection
