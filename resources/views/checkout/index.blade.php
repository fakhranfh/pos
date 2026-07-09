@extends('layouts.app')

@section('title', __('Checkout'))

@php
    $topbarTitle = __('Checkout');
@endphp

@section('app-content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Product search / grid -->
    <div class="lg:col-span-2">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4">
            <input
                type="text"
                id="productSearch"
                autofocus
                placeholder="{{ __('Search by name or SKU, or scan barcode...') }}"
                class="block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-lg py-3 px-4"
            />

            <div id="productGrid" class="mt-4 grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-3 max-h-[65vh] overflow-y-auto"></div>
        </div>
    </div>

    <!-- Cart / payment -->
    <div class="lg:col-span-1">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-4 flex flex-col h-full">
            <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-3">{{ __('Cart') }}</h2>

            <div id="cartError" class="hidden mb-3 p-3 bg-red-100 border border-red-400 text-red-700 rounded dark:bg-red-900 dark:border-red-600 dark:text-red-100 text-sm"></div>

            <div id="cartItems" class="flex-1 overflow-y-auto space-y-2 mb-3">
                <p id="cartEmptyState" class="text-center text-gray-500 dark:text-gray-400 py-8">
                    {{ __('Cart is empty. Search and click a product to add it.') }}
                </p>
            </div>

            <div class="border-t border-gray-200 dark:border-gray-700 pt-3 space-y-2">
                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('Subtotal') }}</span>
                    <span id="subtotalDisplay">Rp 0</span>
                </div>

                <div class="flex justify-between items-center text-sm">
                    <label for="discountAmount" class="text-gray-600 dark:text-gray-400">{{ __('Discount') }}</label>
                    <input type="number" id="discountAmount" min="0" value="0" class="w-28 text-right rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white" />
                </div>

                <div class="flex justify-between text-base font-bold pt-1 border-t border-gray-200 dark:border-gray-700">
                    <span>{{ __('Total') }}</span>
                    <span id="totalDisplay">Rp 0</span>
                </div>

                <div>
                    <label for="paymentMethod" class="block text-sm text-gray-600 dark:text-gray-400">{{ __('Payment Method') }}</label>
                    <select id="paymentMethod" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                        <option value="cash">{{ __('Cash') }}</option>
                        <option value="other">{{ __('Other') }}</option>
                    </select>
                </div>

                <div>
                    <label for="amountTendered" class="block text-sm text-gray-600 dark:text-gray-400">{{ __('Amount Tendered') }}</label>
                    <input type="number" id="amountTendered" min="0" value="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-lg" />
                </div>

                <div class="flex justify-between text-sm">
                    <span class="text-gray-600 dark:text-gray-400">{{ __('Change Due') }}</span>
                    <span id="changeDisplay" class="font-semibold">Rp 0</span>
                </div>

                <button
                    id="payButton"
                    type="button"
                    class="w-full inline-flex justify-center items-center px-4 py-3 bg-blue-600 border border-transparent rounded-md font-semibold text-sm text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring ring-blue-300 disabled:opacity-50 transition ease-in-out duration-150"
                >
                    {{ __('Pay') }}
                </button>
            </div>
        </div>
    </div>
</div>

<script>
(function () {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    const searchUrl = "{{ route('checkout.products') }}";
    const storeUrl = "{{ route('checkout.store') }}";
    const placeholderImageUrl = 'data:image/svg+xml;utf8,' + encodeURIComponent(
        '<svg xmlns="http://www.w3.org/2000/svg" width="300" height="300">' +
        '<rect width="300" height="300" fill="#e5e7eb" />' +
        '<g fill="#9ca3af"><path d="M100 120h100v80H100z" fill="none" stroke="#9ca3af" stroke-width="8"/>' +
        '<circle cx="125" cy="145" r="10"/><path d="M100 190l35-35 25 25 30-30 35 35v10H100z"/></g>' +
        '</svg>'
    );

    const productSearch = document.getElementById('productSearch');
    const productGrid = document.getElementById('productGrid');
    const cartItems = document.getElementById('cartItems');
    const cartEmptyState = document.getElementById('cartEmptyState');
    const cartError = document.getElementById('cartError');
    const subtotalDisplay = document.getElementById('subtotalDisplay');
    const totalDisplay = document.getElementById('totalDisplay');
    const changeDisplay = document.getElementById('changeDisplay');
    const discountAmount = document.getElementById('discountAmount');
    const amountTendered = document.getElementById('amountTendered');
    const payButton = document.getElementById('payButton');

    let cart = [];
    let searchTimer = null;
    let currentTerm = '';
    let nextPage = 1;
    let isLoading = false;
    let hasMore = true;
    const skeletonClass = 'product-skeleton';

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

    function renderSkeleton(count = 8) {
        productGrid.innerHTML = Array.from({ length: count }).map(skeletonCardHtml).join('');
    }

    function appendSkeleton(count = 4) {
        productGrid.insertAdjacentHTML('beforeend', Array.from({ length: count }).map(skeletonCardHtml).join(''));
    }

    function removeSkeletons() {
        productGrid.querySelectorAll(`.${skeletonClass}`).forEach((el) => el.remove());
    }

    function fetchProducts(term) {
        currentTerm = term;
        nextPage = 1;
        hasMore = true;
        renderSkeleton();
        loadPage({ replace: true });
    }

    function loadMore() {
        if (isLoading || !hasMore) {
            return;
        }
        appendSkeleton();
        loadPage({ replace: false });
    }

    function loadPage({ replace }) {
        isLoading = true;

        fetch(`${searchUrl}?q=${encodeURIComponent(currentTerm)}&page=${nextPage}`)
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
                    productGrid.innerHTML = '<p class="col-span-full text-center text-red-500 py-8">' +
                        '{{ __('Failed to load products.') }}</p>';
                }
            })
            .finally(() => {
                isLoading = false;
            });
    }

    function renderProducts(products, { replace }) {
        if (replace && !products.length) {
            productGrid.innerHTML = '<p class="col-span-full text-center text-gray-500 dark:text-gray-400 py-8">' +
                '{{ __('No products found') }}</p>';
            return;
        }

        if (replace) {
            productGrid.innerHTML = '';
        }

        const html = products.map((product) => `
            <button
                type="button"
                class="product-card text-left bg-gray-50 dark:bg-gray-700 hover:bg-blue-50 dark:hover:bg-gray-600 border border-gray-200 dark:border-gray-600 rounded-lg p-3 transition"
                data-id="${product.id}"
                data-name="${encodeURIComponent(product.name)}"
                data-price="${product.price}"
                data-stock="${product.stock}"
            >
                <img src="${product.image_url || placeholderImageUrl}" alt="${product.name}" class="w-full h-24 object-cover rounded mb-2 bg-gray-200 dark:bg-gray-600">
                <div class="font-semibold text-gray-900 dark:text-white text-sm">${product.name}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400">${product.sku}</div>
                <div class="mt-1 text-sm font-bold text-blue-600 dark:text-blue-400">${formatCurrency(product.price)}</div>
                <div class="text-xs text-gray-400">{{ __('Stock') }}: ${product.stock}</div>
            </button>
        `).join('');

        productGrid.insertAdjacentHTML('beforeend', html);

        products.forEach((product) => {
            const card = productGrid.querySelector(`.product-card[data-id="${product.id}"]:not([data-bound])`);
            if (!card) {
                return;
            }
            card.setAttribute('data-bound', '1');
            card.addEventListener('click', () => addToCart({
                id: Number(card.dataset.id),
                name: decodeURIComponent(card.dataset.name),
                price: Number(card.dataset.price),
                stock: Number(card.dataset.stock),
            }));
        });
    }

    function addToCart(product) {
        clearError();
        const existing = cart.find((line) => line.id === product.id);

        if (existing) {
            if (existing.quantity + 1 > product.stock) {
                showError(`{{ __('Only') }} ${product.stock} {{ __('unit(s) of') }} ${product.name} {{ __('available.') }}`);
                return;
            }
            existing.quantity += 1;
        } else {
            cart.push({ id: product.id, name: product.name, price: product.price, stock: product.stock, quantity: 1 });
        }

        renderCart();
    }

    function updateQuantity(id, delta) {
        const line = cart.find((line) => line.id === id);
        if (!line) return;

        const newQuantity = line.quantity + delta;

        if (newQuantity > line.stock) {
            showError(`{{ __('Only') }} ${line.stock} {{ __('unit(s) of') }} ${line.name} {{ __('available.') }}`);
            return;
        }

        if (newQuantity <= 0) {
            cart = cart.filter((line) => line.id !== id);
        } else {
            line.quantity = newQuantity;
        }

        clearError();
        renderCart();
    }

    function removeFromCart(id) {
        cart = cart.filter((line) => line.id !== id);
        clearError();
        renderCart();
    }

    function showError(message) {
        cartError.textContent = message;
        cartError.classList.remove('hidden');
    }

    function clearError() {
        cartError.classList.add('hidden');
        cartError.textContent = '';
    }

    function renderCart() {
        if (!cart.length) {
            cartItems.innerHTML = '';
            cartItems.appendChild(cartEmptyState);
            cartEmptyState.classList.remove('hidden');
        } else {
            cartEmptyState.classList.add('hidden');
            cartItems.innerHTML = cart.map((line) => `
                <div class="flex items-center justify-between bg-gray-50 dark:bg-gray-700 rounded-md p-2">
                    <div class="flex-1 min-w-0">
                        <div class="text-sm font-medium text-gray-900 dark:text-white truncate">${line.name}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">${formatCurrency(line.price)} x ${line.quantity}</div>
                    </div>
                    <div class="flex items-center gap-1 ml-2">
                        <button type="button" class="qty-btn px-2 py-1 bg-gray-200 dark:bg-gray-600 rounded" data-id="${line.id}" data-delta="-1">-</button>
                        <span class="w-6 text-center text-sm">${line.quantity}</span>
                        <button type="button" class="qty-btn px-2 py-1 bg-gray-200 dark:bg-gray-600 rounded" data-id="${line.id}" data-delta="1">+</button>
                        <button type="button" class="remove-btn px-2 py-1 text-red-600 hover:text-red-800" data-id="${line.id}">&times;</button>
                    </div>
                </div>
            `).join('');

            cartItems.querySelectorAll('.qty-btn').forEach((btn) => {
                btn.addEventListener('click', () => updateQuantity(Number(btn.dataset.id), Number(btn.dataset.delta)));
            });
            cartItems.querySelectorAll('.remove-btn').forEach((btn) => {
                btn.addEventListener('click', () => removeFromCart(Number(btn.dataset.id)));
            });
        }

        updateTotals();
    }

    function subtotal() {
        return cart.reduce((sum, line) => sum + line.price * line.quantity, 0);
    }

    function updateTotals() {
        const sub = subtotal();
        const discount = Math.min(Number(discountAmount.value || 0), sub);
        const total = Math.max(sub - discount, 0);
        const tendered = Number(amountTendered.value || 0);

        subtotalDisplay.textContent = formatCurrency(sub);
        totalDisplay.textContent = formatCurrency(total);
        changeDisplay.textContent = formatCurrency(Math.max(tendered - total, 0));
    }

    discountAmount.addEventListener('input', updateTotals);
    amountTendered.addEventListener('input', updateTotals);

    productSearch.addEventListener('input', () => {
        clearTimeout(searchTimer);
        const term = productSearch.value;
        searchTimer = setTimeout(() => fetchProducts(term), 250);
    });

    productGrid.addEventListener('scroll', () => {
        const threshold = 150;
        if (productGrid.scrollTop + productGrid.clientHeight >= productGrid.scrollHeight - threshold) {
            loadMore();
        }
    });

    payButton.addEventListener('click', () => {
        clearError();

        if (!cart.length) {
            showError('{{ __('Add at least one product to the cart.') }}');
            return;
        }

        const sub = subtotal();
        const discount = Math.min(Number(discountAmount.value || 0), sub);
        const total = Math.max(sub - discount, 0);
        const tendered = Number(amountTendered.value || 0);

        if (tendered < total) {
            showError('{{ __('Amount tendered must be at least the total due.') }}');
            return;
        }

        payButton.disabled = true;
        payButton.textContent = '{{ __('Processing...') }}';

        fetch(storeUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                items: cart.map((line) => ({ product_id: line.id, quantity: line.quantity })),
                discount_amount: discount,
                payment_method: document.getElementById('paymentMethod').value,
                amount_tendered: tendered,
            }),
        })
            .then(async (res) => {
                if (res.redirected) {
                    window.location.href = res.url;
                    return;
                }

                if (!res.ok) {
                    const json = await res.json();
                    const message = json.errors ? Object.values(json.errors).flat().join(' ') : (json.message || '{{ __('Something went wrong.') }}');
                    throw new Error(message);
                }
            })
            .catch((err) => {
                showError(err.message);
                payButton.disabled = false;
                payButton.textContent = '{{ __('Pay') }}';
            });
    });

    fetchProducts('');
})();
</script>
@endsection
