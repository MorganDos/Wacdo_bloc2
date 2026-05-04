@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">Commandes</h1>
        <p class="mt-2 text-sm text-gray-600">Commandes actives triées par heure de retrait pour la préparation et le comptoir.</p>

        <div class="mt-6 border border-gray-300 bg-white">
            <div class="flex flex-col gap-3 border-b border-gray-300 px-5 py-4 text-sm text-gray-600 sm:flex-row sm:items-center sm:justify-between">
                @can('create-orders')
                    <a
                        href="{{ route('orders.create') }}"
                        class="inline-flex items-center justify-center border border-gray-900 bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-black"
                    >
                        Ajouter une commande
                    </a>
                @endcan
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
                            <th class="px-6 py-3 text-left font-medium text-gray-600">ID</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-600">Ticket</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-600">État</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-600">Heure</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-600">Produits</th>
                            <th class="px-6 py-3 text-left font-medium text-gray-600">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 bg-white">
                        @forelse($orders as $order)
                            <tr>
                                <td class="px-6 py-4 align-top text-gray-600">{{ $order->id }}</td>
                                <td class="px-6 py-4 align-top text-gray-900">{{ $order->ticket_number }}</td>
                                <td class="px-6 py-4 align-top">
                                    @if($order->status === 'pending')
                                        <span class="inline-flex border border-gray-300 px-2 py-1 text-xs font-medium text-gray-700">En attente</span>
                                    @elseif($order->status === 'ready')
                                        <span class="inline-flex border border-green-400 px-2 py-1 text-xs font-medium text-green-700">Prête</span>
                                    @else
                                        <span class="inline-flex border border-blue-400 px-2 py-1 text-xs font-medium text-blue-700">Livrée</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 align-top text-gray-600">{{ optional($order->delivery_at)->format('d/m/Y H:i') ?? '-' }}</td>
                                <td class="px-6 py-4 align-top text-gray-600">
                                    <div class="space-y-1">
                                        @foreach($order->products as $product)
                                            <div>{{ $product->pivot->quantity }} x {{ $product->name }}</div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <div class="flex flex-wrap gap-2">
                                        @can('manage-order-details')
                                            @if($order->status === 'pending')
                                                <a
                                                    href="{{ route('orders.edit', $order) }}"
                                                    class="inline-flex items-center justify-center border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-800 hover:bg-gray-100"
                                                >
                                                    Modifier
                                                </a>
                                            @endif
                                        @endcan

                                        @can('mark-order-ready')
                                            @if($order->status === 'pending')
                                                <form action="{{ route('orders.ready', $order) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center justify-center border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-800 hover:bg-gray-100"
                                                    >
                                                        Prête
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan

                                        @can('mark-order-delivered')
                                            @if($order->status === 'ready')
                                                <form action="{{ route('orders.delivered', $order) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <button
                                                        type="submit"
                                                        class="inline-flex items-center justify-center border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-800 hover:bg-gray-100"
                                                    >
                                                        Livrée
                                                    </button>
                                                </form>
                                            @endif
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-gray-500">Aucune commande.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
