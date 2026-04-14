<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Ajoute les comptes internes de démonstration.
     */
    public function run(): void
    {
        $users = [
            [
                'name' => 'Wakdo Admin',
                'email' => 'admin@wakdo.test',
                'role' => 'admin',
            ],
            [
                'name' => 'Wakdo Prep',
                'email' => 'prep@wakdo.test',
                'role' => 'prep',
            ],
            [
                'name' => 'Wakdo Cashier',
                'email' => 'cashier@wakdo.test',
                'role' => 'cashier',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                [
                    'name' => $user['name'],
                    'password' => 'password',
                    'role' => $user['role'],
                    'email_verified_at' => now(),
                ],
            );
        }
    }
}
