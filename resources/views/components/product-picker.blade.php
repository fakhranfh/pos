@props([
    'id' => 'productPicker',
    'searchUrl',
    'onSelect',
    'showFilters' => false,
    'categories' => collect(),
    'infiniteScroll' => false,
    'autofocus' => false,
    'placeholder' => null,
    'gridClass' => 'grid-cols-2 sm:grid-cols-3 xl:grid-cols-4',
])

<div
    id="{{ $id }}"
    data-product-picker
    data-search-url="{{ $searchUrl }}"
    data-on-select="{{ $onSelect }}"
    data-infinite-scroll="{{ $infiniteScroll ? '1' : '0' }}"
>
    <div class="flex flex-col sm:flex-row gap-3">
        <input
            type="text"
            id="{{ $id }}-search"
            @if ($autofocus) autofocus @endif
            placeholder="{{ $placeholder ?? __('Search by name or SKU...') }}"
            class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-lg py-3 px-4"
        />

        @if ($showFilters)
            <select
                id="{{ $id }}-category"
                class="block w-full sm:w-56 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-lg py-3 px-4"
            >
                <option value="">{{ __('All Categories') }}</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        @endif
    </div>

    @if ($showFilters)
        <div class="mt-3 flex flex-col sm:flex-row gap-3">
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <input
                    type="number"
                    id="{{ $id }}-price-min"
                    min="0"
                    placeholder="{{ __('Min price') }}"
                    class="w-full sm:w-32 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white py-2 px-3"
                />
                <span class="text-gray-400">-</span>
                <input
                    type="number"
                    id="{{ $id }}-price-max"
                    min="0"
                    placeholder="{{ __('Max price') }}"
                    class="w-full sm:w-32 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white py-2 px-3"
                />
            </div>

            <select
                id="{{ $id }}-sort"
                class="block w-full sm:w-56 rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white py-2 px-3"
            >
                <option value="name:asc">{{ __('Name (A-Z)') }}</option>
                <option value="name:desc">{{ __('Name (Z-A)') }}</option>
                <option value="price:asc">{{ __('Price (Low to High)') }}</option>
                <option value="price:desc">{{ __('Price (High to Low)') }}</option>
                <option value="stock:asc">{{ __('Stock (Low to High)') }}</option>
                <option value="stock:desc">{{ __('Stock (High to Low)') }}</option>
            </select>

            <button
                type="button"
                id="{{ $id }}-reset"
                class="whitespace-nowrap inline-flex items-center justify-center px-4 py-2 rounded-md border border-gray-300 dark:border-gray-600 text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 transition"
            >
                {{ __('Reset Filters') }}
            </button>
        </div>
    @endif

    <div id="{{ $id }}-grid" class="product-picker-grid mt-4 grid {{ $gridClass }} gap-3 max-h-[65vh] overflow-y-auto"></div>
</div>
