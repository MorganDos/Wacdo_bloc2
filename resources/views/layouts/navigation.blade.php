@php
    $roleLabel = match (Auth::user()->role) {
        'admin' => 'administrateur',
        'prep' => 'preparation',
        'cashier' => 'comptoir',
        default => Auth::user()->role,
    };
@endphp

<nav class="border-b border-gray-300 bg-white">
    <div class="mx-auto flex max-w-6xl flex-col gap-4 px-4 py-4 sm:px-6 lg:px-8 lg:flex-row lg:items-center lg:justify-between">
        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-700">
            <a href="{{ route('dashboard') }}" class="text-base font-semibold text-gray-900">Wakdo Back Office</a>
            <a href="{{ route('dashboard') }}" class="hover:text-gray-900">Tableau de bord</a>
            @can('manage-products')
                <a href="{{ route('products.index') }}" class="hover:text-gray-900">Produits</a>
            @endcan
            @can('manage-menus')
                <a href="{{ route('menus.index') }}" class="hover:text-gray-900">Menus</a>
            @endcan
            @can('manage-users')
                <a href="{{ route('users.index') }}" class="hover:text-gray-900">Utilisateurs</a>
            @endcan
            @can('view-orders')
                <a href="{{ route('orders.index') }}" class="hover:text-gray-900">Commandes</a>
            @endcan
            <a href="{{ route('profile.edit') }}" class="hover:text-gray-900">Profil</a>
        </div>

        <div class="flex flex-col gap-3 text-sm text-gray-600 sm:flex-row sm:items-center">
            <span>{{ Auth::user()->name }} ({{ $roleLabel }})</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="inline-flex items-center justify-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-800 hover:bg-gray-100"
                >
                    Se deconnecter
                </button>
            </form>
        </div>
    </div>
</nav>
