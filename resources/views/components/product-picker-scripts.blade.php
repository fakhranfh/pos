@push('scripts')
<script>
(function () {
    const placeholderImageUrl = 'data:image/svg+xml;utf8,' + encodeURIComponent(
        '<svg xmlns="http://www.w3.org/2000/svg" width="300" height="300">' +
        '<rect width="300" height="300" fill="#e5e7eb" />' +
        '<g fill="#9ca3af"><path d="M100 120h100v80H100z" fill="none" stroke="#9ca3af" stroke-width="8"/>' +
        '<circle cx="125" cy="145" r="10"/><path d="M100 190l35-35 25 25 30-30 35 35v10H100z"/></g>' +
        '</svg>'
    );
    const skeletonClass = 'product-picker-skeleton';

    function formatCurrency(value) {
        return 'Rp ' + Number(value || 0).toLocaleString('id-ID', { maximumFractionDigits: 0 });
    }

    function skeletonCardHtml() {
        return `
            <div class="${skeletonClass} animate-pulse bg-gray-50 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 rounded-lg p-3">
                <div class="w-full h-24 bg-gray-200 dark:bg-gray-600 rounded mb-2"></div>
                <div class="h-3 bg-gray-200 dark:bg-gray-600 rounded w-3/4 mb-2"></div>
                <div class="h-2 bg-gray-200 dark:bg-gray-600 rounded w-1/2 mb-2"></div>
                <div class="h-3 bg-gray-200 dark:bg-gray-600 rounded w-1/3 mb-2"></div>
                <div class="h-2 bg-gray-200 dark:bg-gray-600 rounded w-1/4"></div>
            </div>
        `;
    }

    // Turns a `data-product-picker` root element into a live search + grid picker.
    // Calls `window[onSelect](product)` (from the `data-on-select` attribute) when a card is clicked.
    function initProductPicker(root) {
        const id = root.id;
        const searchUrl = root.dataset.searchUrl;
        const onSelectName = root.dataset.onSelect;
        const infiniteScroll = root.dataset.infiniteScroll === '1';

        const search = document.getElementById(`${id}-search`);
        const grid = document.getElementById(`${id}-grid`);
        const categoryFilter = document.getElementById(`${id}-category`);
        const priceMin = document.getElementById(`${id}-price-min`);
        const priceMax = document.getElementById(`${id}-price-max`);
        const sortBy = document.getElementById(`${id}-sort`);
        const resetFilters = document.getElementById(`${id}-reset`);

        if (!search || !grid) {
            return;
        }

        let searchTimer = null;
        let nextPage = 1;
        let isLoading = false;
        let hasMore = true;

        function buildQuery() {
            const params = new URLSearchParams();
            params.set('q', search.value);

            if (categoryFilter && categoryFilter.value) {
                params.set('category_id', categoryFilter.value);
            }
            if (priceMin && priceMin.value !== '') {
                params.set('price_min', priceMin.value);
            }
            if (priceMax && priceMax.value !== '') {
                params.set('price_max', priceMax.value);
            }
            if (sortBy && sortBy.value) {
                const [sort, direction] = sortBy.value.split(':');
                params.set('sort', sort);
                params.set('direction', direction);
            }

            return params;
        }

        function fetchProducts() {
            nextPage = 1;
            hasMore = true;
            grid.innerHTML = Array.from({ length: 8 }).map(skeletonCardHtml).join('');
            loadPage({ replace: true });
        }

        function loadMore() {
            if (isLoading || !hasMore || !infiniteScroll) {
                return;
            }
            grid.insertAdjacentHTML('beforeend', Array.from({ length: 4 }).map(skeletonCardHtml).join(''));
            loadPage({ replace: false });
        }

        function removeSkeletons() {
            grid.querySelectorAll(`.${skeletonClass}`).forEach((el) => el.remove());
        }

        function loadPage({ replace }) {
            isLoading = true;

            const params = buildQuery();
            params.set('page', nextPage);

            fetch(`${searchUrl}?${params.toString()}`)
                .then((res) => res.json())
                .then((json) => {
                    removeSkeletons();
                    hasMore = Boolean(json.next_page);
                    nextPage = json.next_page || nextPage;
                    renderProducts(json.data, { replace });
                })
                .catch(() => {
                    removeSkeletons();
                    if (replace) {
                        grid.innerHTML = '<p class="col-span-full text-center text-red-500 py-8">' +
                            '{{ __('Failed to load products.') }}</p>';
                    }
                })
                .finally(() => {
                    isLoading = false;
                });
        }

        function renderProducts(products, { replace }) {
            if (replace && !products.length) {
                grid.innerHTML = '<p class="col-span-full text-center text-gray-500 dark:text-gray-400 py-8">' +
                    '{{ __('No products found') }}</p>';
                return;
            }

            if (replace) {
                grid.innerHTML = '';
            }

            const html = products.map((product) => `
                <button
                    type="button"
                    class="product-picker-card text-left bg-gray-50 dark:bg-gray-700 hover:bg-blue-50 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 rounded-lg p-3 transition"
                    data-id="${product.id}"
                    data-name="${encodeURIComponent(product.name)}"
                    data-sku="${encodeURIComponent(product.sku || '')}"
                    data-price="${product.price ?? ''}"
                    data-stock="${product.stock ?? ''}"
                    data-image="${encodeURIComponent(product.image_url || '')}"
                >
                    <img src="${product.image_url || placeholderImageUrl}" alt="${product.name}" class="w-full h-24 object-cover rounded mb-2 bg-gray-200 dark:bg-gray-600">
                    <div class="font-semibold text-gray-900 dark:text-white text-sm truncate">${product.name}</div>
                    <div class="text-xs text-gray-500 dark:text-gray-400">${product.sku ?? ''}</div>
                    ${product.price !== undefined ? `<div class="mt-1 text-sm font-bold text-blue-600 dark:text-blue-400">${formatCurrency(product.price)}</div>` : ''}
                    ${product.stock !== undefined ? `<div class="text-xs text-gray-400">{{ __('Stock') }}: ${product.stock}</div>` : ''}
                </button>
            `).join('');

            grid.insertAdjacentHTML('beforeend', html);

            products.forEach((product) => {
                const card = grid.querySelector(`.product-picker-card[data-id="${product.id}"]:not([data-bound])`);
                if (!card) {
                    return;
                }
                card.setAttribute('data-bound', '1');
                card.addEventListener('click', () => {
                    const callback = onSelectName && window[onSelectName];
                    if (typeof callback === 'function') {
                        callback({
                            id: Number(card.dataset.id),
                            name: decodeURIComponent(card.dataset.name),
                            sku: decodeURIComponent(card.dataset.sku),
                            price: card.dataset.price !== '' ? Number(card.dataset.price) : undefined,
                            stock: card.dataset.stock !== '' ? Number(card.dataset.stock) : undefined,
                            image_url: card.dataset.image ? decodeURIComponent(card.dataset.image) : '',
                        });
                    }
                });
            });
        }

        search.addEventListener('input', () => {
            clearTimeout(searchTimer);
            searchTimer = setTimeout(() => fetchProducts(), 250);
        });

        [categoryFilter, sortBy].forEach((el) => el && el.addEventListener('change', () => fetchProducts()));

        [priceMin, priceMax].forEach((el) => {
            if (!el) {
                return;
            }
            el.addEventListener('input', () => {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => fetchProducts(), 400);
            });
        });

        if (resetFilters) {
            resetFilters.addEventListener('click', () => {
                clearTimeout(searchTimer);
                search.value = '';
                if (categoryFilter) categoryFilter.value = '';
                if (priceMin) priceMin.value = '';
                if (priceMax) priceMax.value = '';
                if (sortBy) sortBy.value = 'name:asc';
                fetchProducts();
            });
        }

        if (infiniteScroll) {
            grid.addEventListener('scroll', () => {
                const threshold = 150;
                if (grid.scrollTop + grid.clientHeight >= grid.scrollHeight - threshold) {
                    loadMore();
                }
            });
        }

        root.productPicker = { refresh: fetchProducts };

        fetchProducts();
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('[data-product-picker]').forEach(initProductPicker);
    });
})();
</script>
@endpush
