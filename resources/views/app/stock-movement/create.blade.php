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
          <label for="productSearch" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Product')</label>

          <div id="selectedProduct" class="{{ isset($item) ? '' : 'hidden' }} mt-1 flex items-center gap-3 p-3 bg-blue-50 dark:bg-gray-700 border border-blue-200 dark:border-gray-600 rounded-md">
              <img id="selectedProductImage" src="{{ isset($item) ? $item->product?->image_url : '' }}" alt="" class="w-12 h-12 object-cover rounded bg-gray-200 dark:bg-gray-600">
              <div class="flex-1 min-w-0">
                  <div id="selectedProductName" class="font-medium text-gray-900 dark:text-white truncate">{{ isset($item) ? $item->product?->name : '' }}</div>
                  <div id="selectedProductSku" class="text-xs text-gray-500 dark:text-gray-400">{{ isset($item) ? $item->product?->sku : '' }}</div>
              </div>
              @if(! isset($isView))
                  <button type="button" id="clearProductBtn" class="text-red-600 hover:text-red-800 text-sm">&times; @lang('Change')</button>
              @endif
          </div>

          @if(! isset($isView))
              <div id="stockMovementProductPickerWrapper" class="{{ isset($item) ? 'hidden' : '' }} mt-1">
                  <x-product-picker
                      id="stockMovementProductPicker"
                      :search-url="route('checkout.products')"
                      on-select="onStockMovementProductSelected"
                      :grid-class="'grid-cols-2 sm:grid-cols-3'"
                      :infinite-scroll="true"
                  />
              </div>
          @endif

          <input type="hidden" id="product_id" name="product_id" value="{{ (isset($item) ? $item->product_id : null) ?? old('product_id', request('product_id')) }}" />
          @error('product_id')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Type')</label>
          <select id="type" name="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required @if(isset($isView)) disabled @endif>
            <option value="">-- @lang('Select') --</option>
            <option value="stock_in" {{ old('type', isset($item) ? $item->type?->value : '') === 'stock_in' ? 'selected' : '' }}>@lang('Stock In')</option>
            <option value="adjustment" {{ old('type', isset($item) ? $item->type?->value : '') === 'adjustment' ? 'selected' : '' }}>@lang('Adjustment')</option>
            <option value="return" {{ old('type', isset($item) ? $item->type?->value : '') === 'return' ? 'selected' : '' }}>@lang('Return')</option>
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
(function () {
    const productIdInput = document.getElementById('product_id');
    const pickerWrapper = document.getElementById('stockMovementProductPickerWrapper');
    const selectedProduct = document.getElementById('selectedProduct');
    const selectedProductImage = document.getElementById('selectedProductImage');
    const selectedProductName = document.getElementById('selectedProductName');
    const selectedProductSku = document.getElementById('selectedProductSku');
    const clearProductBtn = document.getElementById('clearProductBtn');
    const placeholderImageUrl = 'data:image/svg+xml;utf8,' + encodeURIComponent(
        '<svg xmlns="http://www.w3.org/2000/svg" width="300" height="300">' +
        '<rect width="300" height="300" fill="#e5e7eb" />' +
        '<g fill="#9ca3af"><path d="M100 120h100v80H100z" fill="none" stroke="#9ca3af" stroke-width="8"/>' +
        '<circle cx="125" cy="145" r="10"/><path d="M100 190l35-35 25 25 30-30 35 35v10H100z"/></g>' +
        '</svg>'
    );

    if (!pickerWrapper) {
        return;
    }

    window.onStockMovementProductSelected = function (product) {
        productIdInput.value = product.id;
        selectedProductImage.src = product.image_url || placeholderImageUrl;
        selectedProductName.textContent = product.name;
        selectedProductSku.textContent = product.sku;
        selectedProduct.classList.remove('hidden');
        pickerWrapper.classList.add('hidden');
    };

    if (clearProductBtn) {
        clearProductBtn.addEventListener('click', () => {
            productIdInput.value = '';
            selectedProduct.classList.add('hidden');
            pickerWrapper.classList.remove('hidden');
        });
    }
})();

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

<x-product-picker-scripts />
@endsection