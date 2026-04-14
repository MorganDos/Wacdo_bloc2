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
                value="{{ old('name', $product->name) }}"
                required
            >
            @error('name')
                <div class="text-sm text-red-600">{{ $message }}</div>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="category" class="block text-sm font-medium text-gray-700">Catégorie</label>
            <input
                id="category"
                name="category"
                type="text"
                class="block w-full rounded-none border border-gray-300 px-3 py-2 text-sm focus:border-gray-500 focus:outline-none focus:ring-0"
                value="{{ old('category', $product->category) }}"
                required
            >
            @error('category')
                <div class="text-sm text-red-600">{{ $message }}</div>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="price" class="block text-sm font-medium text-gray-700">Prix</label>
            <input
                id="price"
                name="price"
                type="number"
                step="0.01"
                min="0"
                class="block w-full rounded-none border border-gray-300 px-3 py-2 text-sm focus:border-gray-500 focus:outline-none focus:ring-0"
                value="{{ old('price', $product->price) }}"
                required
            >
            @error('price')
                <div class="text-sm text-red-600">{{ $message }}</div>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="availability" class="block text-sm font-medium text-gray-700">Disponibilité</label>
            <select
                id="availability"
                name="availability"
                class="block w-full rounded-none border border-gray-300 px-3 py-2 text-sm focus:border-gray-500 focus:outline-none focus:ring-0"
                required
            >
                <option value="available" @selected(old('availability', $product->availability) === 'available')>Disponible</option>
                <option value="out_of_stock" @selected(old('availability', $product->availability) === 'out_of_stock')>Rupture</option>
            </select>
            @error('availability')
                <div class="text-sm text-red-600">{{ $message }}</div>
            @enderror
        </div>

        <div class="space-y-2 md:col-span-2">
            <label for="image" class="block text-sm font-medium text-gray-700">Chemin de l'image</label>
            <input
                id="image"
                name="image"
                type="text"
                class="block w-full rounded-none border border-gray-300 px-3 py-2 text-sm focus:border-gray-500 focus:outline-none focus:ring-0"
                value="{{ old('image', $product->image) }}"
                placeholder="/img/produits/..."
            >
            @error('image')
                <div class="text-sm text-red-600">{{ $message }}</div>
            @enderror
        </div>

        <div class="space-y-2 md:col-span-2">
            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
            <textarea
                id="description"
                name="description"
                class="block min-h-32 w-full rounded-none border border-gray-300 px-3 py-2 text-sm focus:border-gray-500 focus:outline-none focus:ring-0"
            >{{ old('description', $product->description) }}</textarea>
            @error('description')
                <div class="text-sm text-red-600">{{ $message }}</div>
            @enderror
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        <button
            type="submit"
            class="inline-flex items-center justify-center border border-gray-900 bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-black"
        >
            {{ $submitLabel }}
        </button>
        <a
            href="{{ route('products.index') }}"
            class="inline-flex items-center justify-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-800 hover:bg-gray-100"
        >
            Annuler
        </a>
    </div>
</form>
