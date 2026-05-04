@php
    $selectedProductIds = collect(old('product_ids', $selectedProductIds ?? []))
        ->map(fn ($id) => (int) $id)
        ->all();
    $quantities = old('quantities', $quantitiesByProductId ?? []);
    $deliveryAt = old('delivery_at', optional($order?->delivery_at)->format('Y-m-d\TH:i'));
@endphp

<form action="{{ $action }}" method="POST" class="space-y-6">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    @if($order)
        <div class="border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-900">
            Commande {{ $order->ticket_number }} en cours de modification.
        </div>
    @endif

    <div class="space-y-2">
        <label for="delivery_at" class="block text-sm font-medium text-gray-700">Heure de retrait</label>
        <input
            id="delivery_at"
            name="delivery_at"
            type="datetime-local"
            class="block w-full rounded-none border border-gray-300 px-3 py-2 text-sm focus:border-gray-500 focus:outline-none focus:ring-0"
            value="{{ $deliveryAt }}"
        >
        @error('delivery_at')
            <div class="text-sm text-red-600">{{ $message }}</div>
        @enderror
    </div>

    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700">Produits</label>
        <div class="overflow-x-auto border border-gray-300">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Choisir</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Produit</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Catégorie</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Disponibilité</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Prix</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Quantité</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 bg-white">
                    @foreach($products as $product)
                        <tr>
                            <td class="px-4 py-3 align-top">
                                <input
                                    type="checkbox"
                                    name="product_ids[]"
                                    value="{{ $product->id }}"
                                    @checked(in_array($product->id, $selectedProductIds, true))
                                    class="rounded-none border-gray-300 text-gray-900 focus:ring-0"
                                >
                            </td>
                            <td class="px-4 py-3 align-top text-gray-900">{{ $product->name }}</td>
                            <td class="px-4 py-3 align-top text-gray-600">{{ $product->category }}</td>
                            <td class="px-4 py-3 align-top">
                                @if($product->availability === 'available')
                                    <span class="inline-flex border border-green-400 px-2 py-1 text-xs font-medium text-green-700">Disponible</span>
                                @else
                                    <span class="inline-flex border border-gray-300 px-2 py-1 text-xs font-medium text-gray-700">Indisponible</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 align-top text-gray-600">{{ number_format($product->price, 2, ',', ' ') }} EUR</td>
                            <td class="px-4 py-3 align-top">
                                <input
                                    type="number"
                                    name="quantities[{{ $product->id }}]"
                                    min="1"
                                    value="{{ $quantities[$product->id] ?? 1 }}"
                                    class="block w-24 rounded-none border border-gray-300 px-3 py-2 text-sm focus:border-gray-500 focus:outline-none focus:ring-0"
                                >
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @error('product_ids')
            <div class="text-sm text-red-600">{{ $message }}</div>
        @enderror
    </div>

    <div class="flex flex-wrap gap-3">
        <button
            type="submit"
            class="inline-flex items-center justify-center border border-gray-900 bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-black"
        >
            {{ $submitLabel }}
        </button>
        <a
            href="{{ route('orders.index') }}"
            class="inline-flex items-center justify-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-800 hover:bg-gray-100"
        >
            Annuler
        </a>
    </div>
</form>
