@extends('layouts.app')

@section('title', $item->product_id ?? __('View Stock Movements'))

@php
    $topbarTitle = $item->product_id ?? __('View Stock Movements');
@endphp

@section('app-content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex space-x-2 text-sm text-gray-600 dark:text-gray-400">
                <li>
                    <a href="{{ route('stock-movements.index') }}" class="hover:text-gray-900 dark:hover:text-white">
                        {{ __('Stock Movements') }}
                    </a>
                </li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900 dark:text-white">{{ $item->type ?? $item->id }}</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                {{ $item->product_id ?? __('View Stock Movements') }}
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
          <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">@lang('Product Id')</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white">
            {{ $item->product_id }}
          </dd>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 py-4">
          <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">@lang('Type')</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white">
            {{ $item->type }}
          </dd>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 py-4">
          <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">@lang('Quantity Change')</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white">
            {{ $item->quantity_change }}
          </dd>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 py-4">
          <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">@lang('Reason')</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white">
            {{ $item->reason }}
          </dd>
        </div>
        <div class="border-t border-gray-200 dark:border-gray-700 py-4">
          <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">@lang('User Id')</dt>
          <dd class="mt-1 text-sm text-gray-900 dark:text-white">
            {{ $item->user_id }}
          </dd>
        </div>
                        </dl>
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
                                @userDatetime($item->created_at)
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-500 dark:text-gray-400">{{ __('Updated') }}</p>
                            <p class="text-gray-900 dark:text-white font-medium">
                                @userDatetime($item->updated_at)
                            </p>
                        </div>
                    </div>

                    <hr class="my-4 border-gray-200 dark:border-gray-700">

                    <a href="{{ route('stock-movements.index') }}"
                        class="w-full block px-4 py-2 bg-gray-200 text-gray-700 border border-gray-300 rounded-md font-semibold text-sm hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition text-center">
                        {{ __('Back to List') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection