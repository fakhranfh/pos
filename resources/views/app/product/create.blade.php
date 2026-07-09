@extends('layouts.app')

@section('title', __('Create Products'))

@php
    $topbarTitle = __('Create Products');
@endphp

@section('app-content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex space-x-2 text-sm text-gray-600 dark:text-gray-400">
                <li>
                    <a href="{{ route('products.index') }}" class="hover:text-gray-900 dark:hover:text-white">
                        {{ __('Products') }}
                    </a>
                </li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900 dark:text-white">{{ __('Create') }}</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                {{ __('Create Products') }}
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
            <form method="POST" action="{{ route('products.store') }}" class="space-y-4" id="createForm" onsubmit="handleFormSubmit(event)" enctype="multipart/form-data">
                @csrf

                <!-- Fields -->
        <div class="mb-4">
          <label for="category_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Category Id')</label>
          <select id="category_id" name="category_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required @if(isset($isView)) disabled @endif>
            <option value="">-- @lang('Select') --</option>
            @foreach ($categories as $category)
              <option value="{{ $category->id }}" {{ (isset($item) && $item->category_id == $category->id) || old('category_id') == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
              </option>
            @endforeach
          </select>
          @error('category_id')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="sku" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Sku')</label>
          <input type="text" id="sku" name="sku" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ isset($item) ? $item->sku : old('sku') }}" required @if(isset($isView)) disabled @endif />
          @error('sku')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Name')</label>
          <input type="text" id="name" name="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ isset($item) ? $item->name : old('name') }}" required @if(isset($isView)) disabled @endif />
          @error('name')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Price')</label>
          <input type="number" id="price" name="price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ isset($item) ? $item->price : old('price') }}" required @if(isset($isView)) disabled @endif />
          @error('price')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="cost_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Cost Price')</label>
          <input type="number" id="cost_price" name="cost_price" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ isset($item) ? $item->cost_price : old('cost_price') }}" required @if(isset($isView)) disabled @endif />
          @error('cost_price')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="stock" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Stock')</label>
          <input type="number" id="stock" name="stock" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ isset($item) ? $item->stock : old('stock') }}" required @if(isset($isView)) disabled @endif />
          @error('stock')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="low_stock_threshold" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Low Stock Threshold')</label>
          <input type="number" id="low_stock_threshold" name="low_stock_threshold" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ isset($item) ? $item->low_stock_threshold : old('low_stock_threshold') }}" required @if(isset($isView)) disabled @endif />
          @error('low_stock_threshold')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Image')</label>
          <input type="file" id="image" name="image" accept="image/*" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" @if(isset($isView)) disabled @endif />
          @error('image')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="is_active" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Is Active')</label>
          <div class="mt-1 space-y-2">
  <div class="flex items-center">
    <input class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600" type="radio" id="is_active_1" name="is_active" value="1"
      @if((isset($item) && $item->is_active == 1) || old('is_active') == 1) checked @endif>
    <label class="ml-2 text-sm text-gray-700 dark:text-gray-300" for="is_active_1">@lang('Yes')</label>
  </div>
  <div class="flex items-center">
    <input class="rounded border-gray-300 text-blue-600 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600" type="radio" id="is_active_0" name="is_active" value="0"
      @if((isset($item) && $item->is_active == 0) || old('is_active') == 0) checked @endif>
    <label class="ml-2 text-sm text-gray-700 dark:text-gray-300" for="is_active_0">@lang('No')</label>
  </div>
</div>
          @error('is_active')
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
                    <a href="{{ route('products.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-50 transition ease-in-out duration-150 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
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