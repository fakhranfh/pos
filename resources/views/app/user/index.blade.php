@extends('layouts.app')

@section('title', __('Users'))

@php
    $topbarTitle = __('Users');
@endphp

@section('app-content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-6 flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                    {{ __('Users') }}
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    {{ __('Manage user roles') }}
                </p>
            </div>
        </div>

        <!-- Alerts -->
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded dark:bg-green-900 dark:border-green-600 dark:text-green-100">
                {{ session('success') }}
            </div>
        @endif

        <x-data-table
            title="{{ __('All Users') }}"
            tableId="usersTable"
            listUrl="{{ route('users.list') }}"
            :filters="[
                ['key' => 'name', 'label' => 'Name', 'type' => 'text'],
                ['key' => 'email', 'label' => 'Email', 'type' => 'text'],
                ['key' => 'role', 'label' => 'Role', 'type' => 'enum', 'options' => ['admin' => 'Admin', 'manager' => 'Manager', 'cashier' => 'Cashier']],
            ]">
            <x-slot name="headers">
                <x-th-sort sortKey="id">{{ __('ID') }}</x-th-sort>
                <x-th-sort sortKey="name">{{ __('Name') }}</x-th-sort>
                <x-th-sort sortKey="email">{{ __('Email') }}</x-th-sort>
                <x-th-sort sortKey="role">{{ __('Role') }}</x-th-sort>
                <x-th-sort sortKey="created_at">{{ __('Created') }}</x-th-sort>
                <th class="px-6 py-3 text-right font-semibold">{{ __('Actions') }}</th>
            </x-slot>
        </x-data-table>
    </div>
</div>

<x-data-table-scripts />

@push('scripts')
<script>
function loadUsersTable() {
    loadTableData('usersTable', buildFilterUrl('usersTable'), function(item) {
        const row = document.createElement('tr');
        row.className = 'border-b hover:bg-gray-50 dark:hover:bg-gray-700';
        const roleBadgeClasses = {
            Admin: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-100',
            Manager: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-100',
        };
        const roleBadgeClass = roleBadgeClasses[item.role] ?? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-100';
        row.innerHTML = `
            <td class="px-6 py-3">${item.id}</td>
            <td class="px-6 py-3">${item.name}</td>
            <td class="px-6 py-3">${item.email}</td>
            <td class="px-6 py-3"><span class="px-2 py-1 rounded-full text-xs font-medium ${roleBadgeClass}">${item.role}</span></td>
            <td class="px-6 py-3">${item.created_at}</td>
            <td class="px-6 py-3 text-right">
                <div class="flex gap-2 justify-end">
                    <a href="${item.actions.edit}" class="text-amber-600 hover:text-amber-900 text-sm font-medium">{{ __('Edit Role') }}</a>
                </div>
            </td>
        `;
        return row;
    });
}
</script>
@endpush
@endsection
