@php
    $selectedProductIds = collect(old('product_ids', $selectedProductIds ?? []))
        ->map(fn ($id) => (int) $id)
        ->all();
    $quantities = old('quantities', $quantitiesByProductId ?? []);
    $menuIsActive = old('is_active', $menu->is_active);
@endphp

<form action="{{ $action }}" method="POST" class="space-y-6">
    @csrf
    @if($method !== 'POST')
        @method($method)
    @endif

    <div class="grid gap-6 md:grid-cols-2">
        <div class="space-y-2">
            <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
            <input
                id="name"
                name="name"
                type="text"
                class="block w-full rounded-none border border-gray-300 px-3 py-2 text-sm focus:border-gray-500 focus:outline-none focus:ring-0"
                value="{{ old('name', $menu->name) }}"
                required
            >
            @error('name')
                <div class="text-sm text-red-600">{{ $message }}</div>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="price" class="block text-sm font-medium text-gray-700">Prix</label>
            <input
                id="price"
                name="price"
                type="number"
                min="0"
                step="0.01"
                class="block w-full rounded-none border border-gray-300 px-3 py-2 text-sm focus:border-gray-500 focus:outline-none focus:ring-0"
                value="{{ old('price', $menu->price) }}"
                required
            >
            @error('price')
                <div class="text-sm text-red-600">{{ $message }}</div>
            @enderror
        </div>

        <div class="space-y-2 md:col-span-2">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea
                id="description"
                name="description"
                class="block min-h-32 w-full rounded-none border border-gray-300 px-3 py-2 text-sm focus:border-gray-500 focus:outline-none focus:ring-0"
            >{{ old('description', $menu->description) }}</textarea>
            @error('description')
                <div class="text-sm text-red-600">{{ $message }}</div>
            @enderror
        </div>

        <div class="md:col-span-2">
            <label class="flex items-center gap-3 text-sm text-gray-700">
                <input type="hidden" name="is_active" value="0">
                <input type="checkbox" name="is_active" value="1" @checked((bool) $menuIsActive) class="rounded-none border-gray-300 text-gray-900 focus:ring-0">
                <span>Menu visible dans l'API publique</span>
            </label>
        </div>
    </div>

    <div class="space-y-2">
        <label class="block text-sm font-medium text-gray-700">Composition</label>
        <div class="overflow-x-auto border border-gray-300">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Choisir</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Produit</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Catégorie</th>
                        <th class="px-4 py-3 text-left font-medium text-gray-600">Disponibilité</th>
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
                                    <span class="inline-flex border border-gray-300 px-2 py-1 text-xs font-medium text-gray-700">Rupture</span>
                                @endif
                            </td>
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
            href="{{ route('menus.index') }}"
            class="inline-flex items-center justify-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-800 hover:bg-gray-100"
        >
            Annuler
        </a>
    </div>
</form>
