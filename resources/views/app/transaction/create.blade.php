@extends('layouts.app')

@section('title', __('Create Transactions'))

@php
    $topbarTitle = __('Create Transactions');
@endphp

@section('app-content')
<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <ol class="flex space-x-2 text-sm text-gray-600 dark:text-gray-400">
                <li>
                    <a href="{{ route('transactions.index') }}" class="hover:text-gray-900 dark:hover:text-white">
                        {{ __('Transactions') }}
                    </a>
                </li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900 dark:text-white">{{ __('Create') }}</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                {{ __('Create Transactions') }}
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
            <form method="POST" action="{{ route('transactions.store') }}" class="space-y-4" id="createForm" onsubmit="handleFormSubmit(event)">
                @csrf

                <!-- Fields -->
        <div class="mb-4">
          <label for="invoice_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Invoice Number')</label>
          <input type="text" id="invoice_number" name="invoice_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ isset($item) ? $item->invoice_number : old('invoice_number') }}" required @if(isset($isView)) disabled @endif />
          @error('invoice_number')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="cashier_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Cashier Id')</label>
          <select id="cashier_id" name="cashier_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required @if(isset($isView)) disabled @endif>
            <option value="">-- @lang('Select') --</option>
            @foreach ($users as $user)
              <option value="{{ $user->id }}" {{ (isset($item) && $item->cashier_id == $user->id) || old('cashier_id') == $user->id ? 'selected' : '' }}>
                {{ $user->name }}
              </option>
            @endforeach
          </select>
          @error('cashier_id')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Customer Id (optional)')</label>
          <select id="customer_id" name="customer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" @if(isset($isView)) disabled @endif>
            <option value="">-- @lang('None') --</option>
            @foreach ($customers as $customer)
              <option value="{{ $customer->id }}" {{ (isset($item) && $item->customer_id == $customer->id) || old('customer_id') == $customer->id ? 'selected' : '' }}>
                {{ $customer->name }}
              </option>
            @endforeach
          </select>
          @error('customer_id')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="subtotal" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Subtotal')</label>
          <input type="number" id="subtotal" name="subtotal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ isset($item) ? $item->subtotal : old('subtotal') }}" required @if(isset($isView)) disabled @endif />
          @error('subtotal')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="discount_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Discount Amount')</label>
          <input type="number" id="discount_amount" name="discount_amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ isset($item) ? $item->discount_amount : old('discount_amount') }}" required @if(isset($isView)) disabled @endif />
          @error('discount_amount')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="tax_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Tax Amount')</label>
          <input type="number" id="tax_amount" name="tax_amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ isset($item) ? $item->tax_amount : old('tax_amount') }}" required @if(isset($isView)) disabled @endif />
          @error('tax_amount')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="total" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Total')</label>
          <input type="number" id="total" name="total" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ isset($item) ? $item->total : old('total') }}" required @if(isset($isView)) disabled @endif />
          @error('total')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="amount_tendered" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Amount Tendered')</label>
          <input type="number" id="amount_tendered" name="amount_tendered" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ isset($item) ? $item->amount_tendered : old('amount_tendered') }}" required @if(isset($isView)) disabled @endif />
          @error('amount_tendered')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="change_due" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Change Due')</label>
          <input type="number" id="change_due" name="change_due" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ isset($item) ? $item->change_due : old('change_due') }}" required @if(isset($isView)) disabled @endif />
          @error('change_due')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Payment Method')</label>
          <select id="payment_method" name="payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required @if(isset($isView)) disabled @endif>
            <option value="">-- @lang('Select') --</option>
            <option value="cash" {{ old('payment_method', isset($item) ? $item->payment_method : '') === 'cash' ? 'selected' : '' }}>@lang('Cash')</option>
            <option value="other" {{ old('payment_method', isset($item) ? $item->payment_method : '') === 'other' ? 'selected' : '' }}>@lang('Other')</option>
          </select>
          @error('payment_method')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Status')</label>
          <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required @if(isset($isView)) disabled @endif>
            <option value="">-- @lang('Select') --</option>
            <option value="completed" {{ old('status', isset($item) ? $item->status : '') === 'completed' ? 'selected' : '' }}>@lang('Completed')</option>
            <option value="voided" {{ old('status', isset($item) ? $item->status : '') === 'voided' ? 'selected' : '' }}>@lang('Voided')</option>
          </select>
          @error('status')
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
                    <a href="{{ route('transactions.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-50 transition ease-in-out duration-150 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
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