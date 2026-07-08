@props(['sortKey', 'align' => 'left'])

<th
    class="px-6 py-3 text-{{ $align }} font-semibold cursor-pointer select-none hover:text-gray-900 dark:hover:text-white"
    data-sort-key="{{ $sortKey }}"
    onclick="toggleSort(this)"
>
    <span class="inline-flex items-center gap-1">
        {{ $slot }}
        <span class="sort-indicator text-gray-400 dark:text-gray-500 text-xs">&varr;</span>
    </span>
</th>
