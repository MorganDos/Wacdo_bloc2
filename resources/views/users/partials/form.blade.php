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
                value="{{ old('name', $user->name) }}"
                required
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
                class="block w-full rounded-none border border-gray-300 px-3 py-2 text-sm focus:border-gray-500 focus:outline-none focus:ring-0"
                value="{{ old('email', $user->email) }}"
                required
            >
            @error('email')
                <div class="text-sm text-red-600">{{ $message }}</div>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
            <select
                id="role"
                name="role"
                class="block w-full rounded-none border border-gray-300 px-3 py-2 text-sm focus:border-gray-500 focus:outline-none focus:ring-0"
                required
            >
                <option value="admin" @selected(old('role', $user->role) === 'admin')>Admin</option>
                <option value="prep" @selected(old('role', $user->role) === 'prep')>Preparation</option>
                <option value="cashier" @selected(old('role', $user->role) === 'cashier')>Comptoir</option>
            </select>
            @error('role')
                <div class="text-sm text-red-600">{{ $message }}</div>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
            <input
                id="password"
                name="password"
                type="password"
                class="block w-full rounded-none border border-gray-300 px-3 py-2 text-sm focus:border-gray-500 focus:outline-none focus:ring-0"
                {{ $method === 'POST' ? 'required' : '' }}
            >
            <div class="text-sm text-gray-500">{{ $method === 'POST' ? '8 caracteres minimum.' : 'Laisser vide pour garder le mot de passe actuel.' }}</div>
            @error('password')
                <div class="text-sm text-red-600">{{ $message }}</div>
            @enderror
        </div>

        <div class="space-y-2">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmation du mot de passe</label>
            <input
                id="password_confirmation"
                name="password_confirmation"
                type="password"
                class="block w-full rounded-none border border-gray-300 px-3 py-2 text-sm focus:border-gray-500 focus:outline-none focus:ring-0"
                {{ $method === 'POST' ? 'required' : '' }}
            >
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
            href="{{ route('users.index') }}"
            class="inline-flex items-center justify-center border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-800 hover:bg-gray-100"
        >
            Annuler
        </a>
    </div>
</form>
