<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Lance les seeders dans l'ordre utile pour les relations.
     */
    public function run(): void
    {
        $this->call([UserSeeder::class, ProductSeeder::class, MenuSeeder::class, OrderSeeder::class]);
    }
}
