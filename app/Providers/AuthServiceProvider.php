<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPolicies();

        // Déclaration des droits utilisés par les routes et les vues.
        Gate::define('manage-users', fn (User $user): bool => $user->role === 'admin');
        Gate::define('manage-products', fn (User $user): bool => $user->role === 'admin');
        Gate::define('manage-menus', fn (User $user): bool => $user->role === 'admin');

        Gate::define('view-orders', fn (User $user): bool => in_array($user->role, ['admin', 'prep', 'cashier'], true));
        Gate::define('create-orders', fn (User $user): bool => in_array($user->role, ['admin', 'cashier'], true));
        Gate::define('manage-order-details', fn (User $user): bool => in_array($user->role, ['admin', 'cashier'], true));
        Gate::define('mark-order-ready', fn (User $user): bool => in_array($user->role, ['admin', 'prep'], true));
        Gate::define('mark-order-delivered', fn (User $user): bool => in_array($user->role, ['admin', 'cashier'], true));
    }
}
