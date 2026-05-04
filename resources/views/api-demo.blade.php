@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-4xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">Démo API</h1>
        <p class="mt-2 text-sm text-gray-600">Cette page vérifie les routes JSON prévues pour la borne de commande.</p>

        <div class="mt-6 border border-gray-300 bg-white">
            <div class="border-b border-gray-300 px-5 py-4">
                <h2 class="text-base font-semibold text-gray-900">Lecture</h2>
            </div>
            <div class="flex flex-wrap gap-3 px-5 py-4">
                <button
                    id="loadProducts"
                    type="button"
                    class="inline-flex items-center justify-center border border-gray-900 bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-black"
                >
                    GET /api/products
                </button>
                <button
                    id="loadMenus"
                    type="button"
                    class="inline-flex items-center justify-center border border-gray-900 bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-black"
                >
                    GET /api/menus
                </button>
            </div>
        </div>

        <div class="mt-6 border border-gray-300 bg-white">
            <div class="border-b border-gray-300 px-5 py-4">
                <h2 class="text-base font-semibold text-gray-900">Création de commande</h2>
            </div>
            <form id="orderForm" class="grid gap-4 px-5 py-4 md:grid-cols-[1fr_120px_auto] md:items-end">
                <div class="space-y-2">
                    <label for="productSelect" class="block text-sm font-medium text-gray-700">Produit</label>
                    <select
                        id="productSelect"
                        class="block w-full rounded-none border border-gray-300 px-3 py-2 text-sm focus:border-gray-500 focus:outline-none focus:ring-0"
                        required
                    >
                        <option value="">Chargement...</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label for="quantityInput" class="block text-sm font-medium text-gray-700">Quantité</label>
                    <input
                        id="quantityInput"
                        type="number"
                        min="1"
                        value="1"
                        class="block w-full rounded-none border border-gray-300 px-3 py-2 text-sm focus:border-gray-500 focus:outline-none focus:ring-0"
                        required
                    >
                </div>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center border border-gray-900 bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-black"
                >
                    POST /api/orders
                </button>
            </form>
        </div>

        <div class="mt-6 border border-gray-300 bg-white">
            <div class="border-b border-gray-300 px-5 py-4">
                <h2 class="text-base font-semibold text-gray-900">Résultat</h2>
            </div>
            <div class="space-y-3 px-5 py-4">
                <div id="status" class="text-sm text-gray-500">En attente.</div>
                <pre id="output" class="min-h-64 overflow-auto border border-gray-200 bg-gray-50 p-4 text-xs text-gray-800"></pre>
            </div>
        </div>
    </div>

    <script>
        const statusElement = document.getElementById('status');
        const outputElement = document.getElementById('output');
        const productSelect = document.getElementById('productSelect');
        const quantityInput = document.getElementById('quantityInput');
        const orderForm = document.getElementById('orderForm');

        function showStatus(response) {
            statusElement.textContent = `${response.status} ${response.statusText}`;
            statusElement.className = response.ok ? 'text-sm text-green-700' : 'text-sm text-red-700';
        }

        function showJson(payload) {
            outputElement.textContent = JSON.stringify(payload, null, 2);
        }

        async function callApi(url, options = {}) {
            statusElement.textContent = 'Chargement...';
            statusElement.className = 'text-sm text-gray-500';

            const response = await fetch(url, {
                ...options,
                headers: {
                    Accept: 'application/json',
                    ...(options.headers || {}),
                },
            });

            const text = await response.text();
            let payload = null;

            try {
                payload = text ? JSON.parse(text) : null;
            } catch {
                payload = { message: text };
            }

            showStatus(response);
            showJson(payload);

            return { response, payload };
        }

        function fillProducts(products) {
            productSelect.replaceChildren();

            if (! products.length) {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'Aucun produit disponible';
                productSelect.appendChild(option);
                return;
            }

            products.forEach((product) => {
                const option = document.createElement('option');
                option.value = product.id;
                option.textContent = `${product.name} - ${Number(product.price).toFixed(2)} EUR`;
                productSelect.appendChild(option);
            });
        }

        async function loadProducts(displayResult = true) {
            const { response, payload } = await callApi('/api/products');

            if (response.ok && Array.isArray(payload)) {
                fillProducts(payload);
            }

            if (! displayResult) {
                statusElement.textContent = 'Produits chargés.';
                outputElement.textContent = '';
            }
        }

        async function createOrder(event) {
            event.preventDefault();

            const productId = Number(productSelect.value);
            const quantity = Math.max(1, Number(quantityInput.value || 1));

            if (! productId) {
                statusElement.textContent = 'Aucun produit sélectionné.';
                statusElement.className = 'text-sm text-red-700';
                return;
            }

            await callApi('/api/orders', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    products: [
                        {
                            product_id: productId,
                            quantity,
                        },
                    ],
                }),
            });
        }

        document.getElementById('loadProducts').addEventListener('click', () => loadProducts());
        document.getElementById('loadMenus').addEventListener('click', () => callApi('/api/menus'));
        orderForm.addEventListener('submit', createOrder);

        loadProducts(false);
    </script>
@endsection
