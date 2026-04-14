@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">Ajouter une commande</h1>

        <div class="mt-6 border border-gray-300 bg-white p-5">
            @include('orders.partials.form', [
                'action' => route('orders.store'),
                'method' => 'POST',
                'submitLabel' => 'Ajouter',
            ])
        </div>
    </div>
@endsection
