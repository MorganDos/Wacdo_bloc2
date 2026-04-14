@extends('layouts.app')

@section('content')
    @php
        $roleLabel = match (auth()->user()->role) {
            'admin' => 'administrateur',
            'prep' => 'preparation',
            'cashier' => 'comptoir',
            default => auth()->user()->role,
        };
    @endphp

    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">Tableau de bord</h1>
        <p class="mt-2 text-sm text-gray-600">Connecte en tant que {{ $roleLabel }}.</p>

        <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            @can('manage-users')
                <a href="{{ route('users.index') }}" class="border border-gray-300 bg-white p-5 hover:bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Utilisateurs</h3>
                    <p class="mt-2 text-sm text-gray-600">Gérer les comptes.</p>
                </a>
            @endcan

            @can('manage-products')
                <a href="{{ route('products.index') }}" class="border border-gray-300 bg-white p-5 hover:bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Produits</h3>
                    <p class="mt-2 text-sm text-gray-600">Gérer les produits.</p>
                </a>
            @endcan

            @can('manage-menus')
                <a href="{{ route('menus.index') }}" class="border border-gray-300 bg-white p-5 hover:bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Menus</h3>
                    <p class="mt-2 text-sm text-gray-600">Gérer les menus.</p>
                </a>
            @endcan

            @can('view-orders')
                <a href="{{ route('orders.index') }}" class="border border-gray-300 bg-white p-5 hover:bg-gray-50">
                    <h3 class="text-lg font-semibold text-gray-900">Commandes</h3>
                    <p class="mt-2 text-sm text-gray-600">Suivre et gérer les préparations.</p>
                </a>
            @endcan

            <a href="{{ route('profile.edit') }}" class="border border-gray-300 bg-white p-5 hover:bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900">Profil</h3>
                <p class="mt-2 text-sm text-gray-600">Mettre a jour les informations du compte.</p>
            </a>
        </div>
    </div>
@endsection
