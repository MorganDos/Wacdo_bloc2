@extends('layouts.guest')

@section('content')
    <div class="space-y-6">
        <div class="space-y-2 text-center">
            <h1 class="text-2xl font-semibold text-gray-900">Connexion</h1>
            <p class="text-sm text-gray-600">Accès équipe Wakdo.</p>
        </div>

        @if (session('status'))
            <div class="border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-900">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="border border-red-300 bg-red-50 px-4 py-3 text-sm text-red-900">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div class="space-y-2">
                <label for="email" class="block text-sm font-medium text-gray-700">Adresse e-mail</label>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    class="block w-full rounded-none border border-gray-300 px-3 py-2 text-sm focus:border-gray-500 focus:outline-none focus:ring-0"
                >
            </div>

            <div class="space-y-2">
                <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    class="block w-full rounded-none border border-gray-300 px-3 py-2 text-sm focus:border-gray-500 focus:outline-none focus:ring-0"
                >
            </div>

            <label class="flex items-center gap-3 text-sm text-gray-600">
                <input
                    id="remember"
                    type="checkbox"
                    name="remember"
                    class="rounded-none border-gray-300 text-gray-900 focus:ring-0"
                >
                <span>Se souvenir de moi</span>
            </label>

            <button
                type="submit"
                class="inline-flex w-full items-center justify-center border border-gray-900 bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-black"
            >
                Se connecter
            </button>
        </form>
    </div>
@endsection
