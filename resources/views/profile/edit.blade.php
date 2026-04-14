@extends('layouts.app')

@section('content')
    <div class="mx-auto max-w-6xl px-4 py-8 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">Profil</h1>
        <p class="mt-2 text-sm text-gray-600">Modifier vos informations de compte.</p>

        <div class="mt-6 max-w-2xl border border-gray-300 bg-white p-5">
            @if (session('status') === 'profile-updated')
                <div class="mb-4 border border-green-300 bg-green-50 px-4 py-3 text-sm text-green-900">
                    Profil mis a jour.
                </div>
            @endif

            <form method="post" action="{{ route('profile.update') }}" class="space-y-5">
                @csrf
                @method('patch')

                <div class="space-y-2">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name', $user->name) }}"
                        class="block w-full rounded-none border border-gray-300 px-3 py-2 text-sm focus:border-gray-500 focus:outline-none focus:ring-0"
                    >
                    @error('name')
                        <div class="text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-gray-700">Adresse e-mail</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email', $user->email) }}"
                        class="block w-full rounded-none border border-gray-300 px-3 py-2 text-sm focus:border-gray-500 focus:outline-none focus:ring-0"
                    >
                    @error('email')
                        <div class="text-sm text-red-600">{{ $message }}</div>
                    @enderror
                </div>

                <button
                    type="submit"
                    class="inline-flex items-center justify-center border border-gray-900 bg-gray-900 px-4 py-2 text-sm font-medium text-white hover:bg-black"
                >
                    Enregistrer
                </button>
            </form>
        </div>
    </div>
@endsection
