@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">Produits</h1>

        <div class="mt-6 border border-gray-300 bg-white">
            <div class="flex flex-col gap-3 border-b border-gray-300 px-5 py-4 text-sm text-gray-600 sm:flex-row sm:items-center sm:justify-between">
                <span>{{ $products->count() }} produit(s) dans le catalogue.</span>
                <a
                    href="{{ route('products.create') }}"
                    class="inline-flex items-center justify-center border border-gray-900 bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-black"
                >
                    Ajouter un produit
                </a>
            </div>

            @if(session('success'))
                <div class="mx-6 mt-4 border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-900">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mx-6 mt-4 border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-900">
                    {{ session('error') }}
                </div>
            @endif

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left font-medium text-gray-600">Image</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-600">Nom</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-600">Categorie</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-600">Prix</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-600">Disponibilite</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($products as $product)
                            <tr>
                                <td class="px-6 py-4 align-top">
                                    @if($product->image)
                                        <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" class="h-14 w-14 object-cover">
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 align-top text-gray-900">{{ $product->name }}</td>
                                <td class="px-6 py-4 align-top text-gray-600">{{ $product->category }}</td>
                                <td class="px-6 py-4 align-top text-gray-600">{{ number_format($product->price, 2, ',', ' ') }} EUR</td>
                                <td class="px-6 py-4 align-top">
                                    @if($product->availability === 'available')
                                        <span class="inline-flex border border-green-400 px-2 py-1 text-xs font-medium text-green-700">Disponible</span>
                                    @else
                                        <span class="inline-flex border border-gray-300 px-2 py-1 text-xs font-medium text-gray-700">Rupture</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <div class="flex flex-wrap gap-2">
                                        <a
                                            href="{{ route('products.edit', $product) }}"
                                            class="inline-flex items-center justify-center border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-800 hover:bg-gray-100"
                                        >
                                            Modifier
                                        </a>
                                        <form action="{{ route('products.destroy', $product) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button
                                                type="submit"
                                                class="inline-flex items-center justify-center border border-red-600 bg-red-600 px-3 py-2 text-sm font-medium text-white hover:bg-red-700"
                                            >
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-gray-500">Aucun produit.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
