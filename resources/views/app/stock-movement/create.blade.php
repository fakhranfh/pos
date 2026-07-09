@extends('layouts.app')

@section('title', __('Create Stock Movements'))

@php
    $topbarTitle = __('Create Stock Movements');
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
                <li class="text-gray-900 dark:text-white">{{ __('Create') }}</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                {{ __('Create Stock Movements') }}
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                {{ __('Fill in the form below to create a new record') }}
            </p>
        </div>

        <!-- Alerts -->
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded dark:bg-red-900 dark:border-red-600 dark:text-red-100">
                <h3 class="font-bold">{{ __('Please fix the following errors:') }}</h3>
                <ul class="mt-2 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
            <form method="POST" action="{{ route('stock-movements.store') }}" class="space-y-4" id="createForm" onsubmit="handleFormSubmit(event)">
                @csrf

                <!-- Fields -->
        <div class="mb-4">
          <label for="product_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Product')</label>
          <select id="product_id" name="product_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required @if(isset($isView)) disabled @endif>
            <option value="">-- @lang('Select') --</option>
            @foreach ($products as $product)
              <option value="{{ $product->id }}" {{ (isset($item) && $item->product_id == $product->id) || old('product_id', request('product_id')) == $product->id ? 'selected' : '' }}>
                {{ $product->name }}
              </option>
            @endforeach
          </select>
          @error('product_id')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Type')</label>
          <select id="type" name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required @if(isset($isView)) disabled @endif>
            <option value="">-- @lang('Select') --</option>
            <option value="sale" {{ old('type', isset($item) ? $item->type : '') === 'sale' ? 'selected' : '' }}>@lang('Sale')</option>
            <option value="stock_in" {{ old('type', isset($item) ? $item->type : '') === 'stock_in' ? 'selected' : '' }}>@lang('Stock In')</option>
            <option value="adjustment" {{ old('type', isset($item) ? $item->type : '') === 'adjustment' ? 'selected' : '' }}>@lang('Adjustment')</option>
            <option value="return" {{ old('type', isset($item) ? $item->type : '') === 'return' ? 'selected' : '' }}>@lang('Return')</option>
          </select>
          @error('type')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="quantity_change" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Quantity Change')</label>
          <input type="number" id="quantity_change" name="quantity_change" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ isset($item) ? $item->quantity_change : old('quantity_change') }}" required @if(isset($isView)) disabled @endif />
          @error('quantity_change')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="reason" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Reason')</label>
          <textarea id="reason" name="reason" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required @if(isset($isView)) disabled @endif>{{ isset($item) ? $item->reason : old('reason') }}</textarea>
          @error('reason')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>

                <!-- Submit Buttons -->
                <div class="flex gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                    <button type="submit" id="createSubmitBtn" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-50 transition ease-in-out duration-150">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                        <span id="createSubmitText">{{ __('Create') }}</span>
                    </button>
                    <a href="{{ route('stock-movements.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-50 transition ease-in-out duration-150 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                        {{ __('Cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function handleFormSubmit(e) {
    const submitBtn = document.getElementById('createSubmitBtn');
    const submitText = document.getElementById('createSubmitText');

    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.style.opacity = '0.5';
        submitBtn.style.cursor = 'not-allowed';
        if (submitText) {
            submitText.textContent = '{{ __("Saving...") }}';
        }
    }
}
</script>
@endsection