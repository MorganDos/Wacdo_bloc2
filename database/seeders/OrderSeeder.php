<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Seeder;

class OrderSeeder extends Seeder
{
    /**
     * Ajoute quelques commandes de démonstration.
     */
    public function run(): void
    {
        $products = Product::orderBy('name')->take(3)->get();

        if ($products->count() < 3) {
            return;
        }

        $definitions = [
            [
                'ticket_number' => 'WKD-DEMO01',
                'status' => 'pending',
                'delivery_at' => now()->addMinutes(15),
                'lines' => [
                    $products[0]->id => ['quantity' => 1],
                    $products[1]->id => ['quantity' => 1],
                ],
            ],
            [
                'ticket_number' => 'WKD-DEMO02',
                'status' => 'ready',
                'delivery_at' => now()->addMinutes(30),
                'lines' => [
                    $products[0]->id => ['quantity' => 2],
                    $products[2]->id => ['quantity' => 1],
                ],
            ],
            [
                'ticket_number' => 'WKD-DEMO03',
                'status' => 'delivered',
                'delivery_at' => now()->subMinutes(20),
                'lines' => [
                    $products[1]->id => ['quantity' => 1],
                    $products[2]->id => ['quantity' => 2],
                ],
            ],
        ];

        foreach ($definitions as $definition) {
            $lines = $definition['lines'];
            unset($definition['lines']);

            $order = Order::updateOrCreate(
                ['ticket_number' => $definition['ticket_number']],
                $definition,
            );

            $order->products()->sync($lines);
        }
    }
}
