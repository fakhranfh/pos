@extends('layouts.app')

@section('title', __('Edit Transactions'))

@php
    $topbarTitle = __('Edit Transactions');
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
                <li>
                    <a href="{{ route('transactions.show', $item) }}" class="hover:text-gray-900 dark:hover:text-white">
                        {{ $item->invoice_number ?? $item->id }}
                    </a>
                </li>
                <li class="text-gray-400">/</li>
                <li class="text-gray-900 dark:text-white">{{ __('Edit') }}</li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                {{ __('Edit Transactions') }}
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                {{ __('Update the record details') }}
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
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            <!-- Main Form -->
            <div class="lg:col-span-3">
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                    <form method="POST" action="{{ route('transactions.update', $item) }}" class="space-y-4" id="editForm" onsubmit="handleEditFormSubmit(event)">
                        @csrf
                        @method('PUT')

                        <!-- Fields -->
        <div class="mb-4">
          <label for="invoice_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Invoice Number')</label>
          <input type="text" id="invoice_number" name="invoice_number" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ isset($item) ? $item->invoice_number : old('invoice_number') }}" required />
          @error('invoice_number')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="cashier_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Cashier Id')</label>
          <select id="cashier_id" name="cashier_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
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
          <select id="customer_id" name="customer_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
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
          <input type="number" id="subtotal" name="subtotal" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ isset($item) ? $item->subtotal : old('subtotal') }}" required />
          @error('subtotal')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="discount_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Discount Amount')</label>
          <input type="number" id="discount_amount" name="discount_amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ isset($item) ? $item->discount_amount : old('discount_amount') }}" required />
          @error('discount_amount')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="tax_amount" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Tax Amount')</label>
          <input type="number" id="tax_amount" name="tax_amount" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ isset($item) ? $item->tax_amount : old('tax_amount') }}" required />
          @error('tax_amount')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="total" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Total')</label>
          <input type="number" id="total" name="total" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ isset($item) ? $item->total : old('total') }}" required />
          @error('total')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="amount_tendered" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Amount Tendered')</label>
          <input type="number" id="amount_tendered" name="amount_tendered" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ isset($item) ? $item->amount_tendered : old('amount_tendered') }}" required />
          @error('amount_tendered')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="change_due" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Change Due')</label>
          <input type="number" id="change_due" name="change_due" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" value="{{ isset($item) ? $item->change_due : old('change_due') }}" required />
          @error('change_due')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Payment Method')</label>
          <select id="payment_method" name="payment_method" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
            <option value="">-- @lang('Select') --</option>
            <option value="cash" {{ old('payment_method', $item->payment_method) === 'cash' ? 'selected' : '' }}>@lang('Cash')</option>
            <option value="other" {{ old('payment_method', $item->payment_method) === 'other' ? 'selected' : '' }}>@lang('Other')</option>
          </select>
          @error('payment_method')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>
        <div class="mb-4">
          <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">@lang('Status')</label>
          <select id="status" name="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" required>
            <option value="">-- @lang('Select') --</option>
            <option value="completed" {{ old('status', $item->status) === 'completed' ? 'selected' : '' }}>@lang('Completed')</option>
            <option value="voided" {{ old('status', $item->status) === 'voided' ? 'selected' : '' }}>@lang('Voided')</option>
          </select>
          @error('status')
            <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
          @enderror
        </div>

                        <!-- Submit Buttons -->
                        <div class="flex gap-3 pt-6 border-t border-gray-200 dark:border-gray-700">
                            <button type="submit" id="editSubmitBtn" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-50 transition ease-in-out duration-150">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                                <span id="editSubmitText">{{ __('Save Changes') }}</span>
                            </button>
                            <a href="{{ route('transactions.show', $item) }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest hover:bg-gray-300 active:bg-gray-400 focus:outline-none focus:border-gray-400 focus:ring ring-gray-300 disabled:opacity-50 transition ease-in-out duration-150 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:hover:bg-gray-600">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
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
                                {{ $item->created_at?->format('d M Y H:i') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-gray-500 dark:text-gray-400">{{ __('Updated') }}</p>
                            <p class="text-gray-900 dark:text-white font-medium">
                                {{ $item->updated_at?->format('d M Y H:i') }}
                            </p>
                        </div>
                    </div>

                    <hr class="my-4 border-gray-200 dark:border-gray-700">

                    <button type="button"
                        class="w-full px-4 py-2 bg-red-100 text-red-700 border border-red-300 rounded-md font-semibold text-sm hover:bg-red-200 dark:bg-red-900 dark:text-red-100 dark:border-red-800 dark:hover:bg-red-800 transition"
                        id="deleteShowBtn"
                        onclick="document.getElementById('deleteModal').classList.remove('hidden')">
                        <svg class="w-5 h-5 mr-2 inline" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                        </svg>
                        {{ __('Delete') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Modal -->
<div id="deleteModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg max-w-sm w-full mx-4">
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ __('Confirm Delete') }}
            </h3>
        </div>
        <div class="px-6 py-4">
            <p class="text-gray-600 dark:text-gray-300">
                {{ __('Are you sure you want to delete this record? This action cannot be undone.') }}
            </p>
        </div>
        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 flex gap-3 justify-end">
            <button type="button"
                class="px-4 py-2 text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-600 transition"
                id="deleteModalCancelBtn"
                onclick="document.getElementById('deleteModal').classList.add('hidden')">
                {{ __('Cancel') }}
            </button>
            <form method="POST" action="{{ route('transactions.destroy', $item) }}" class="inline" id="deleteForm" onsubmit="handleDeleteSubmit(event)">
                @csrf
                @method('DELETE')
                <button type="submit"
                    id="deleteModalDeleteBtn"
                    class="px-4 py-2 bg-red-600 text-white border border-red-600 rounded-md hover:bg-red-700 transition">
                    {{ __('Delete') }}
                </button>
            </form>
        </div>
    </div>
</div>

<script>
    // Close modal when clicking outside
    document.getElementById('deleteModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            this.classList.add('hidden');
        }
    });

    // Handle edit form submission
    function handleEditFormSubmit(e) {
        const submitBtn = document.getElementById('editSubmitBtn');
        const submitText = document.getElementById('editSubmitText');

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.5';
            submitBtn.style.cursor = 'not-allowed';
            if (submitText) {
                submitText.textContent = '{{ __("Saving...") }}';
            }
        }
    }

    // Handle delete form submission
    function handleDeleteSubmit(e) {
        e.preventDefault();

        const deleteBtn = document.getElementById('deleteModalDeleteBtn');
        const cancelBtn = document.getElementById('deleteModalCancelBtn');
        const form = document.getElementById('deleteForm');

        // Show loading state
        if (deleteBtn) {
            deleteBtn.disabled = true;
            deleteBtn.textContent = '{{ __("Deleting...") }}';
            deleteBtn.style.opacity = '0.5';
            deleteBtn.style.cursor = 'not-allowed';
        }
        if (cancelBtn) {
            cancelBtn.disabled = true;
            cancelBtn.style.opacity = '0.5';
            cancelBtn.style.cursor = 'not-allowed';
        }

        // Submit form after short delay for visual feedback
        setTimeout(() => {
            form.submit();
        }, 300);
    }
</script>
@endsection