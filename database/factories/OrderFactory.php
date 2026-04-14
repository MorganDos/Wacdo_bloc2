<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Définit les valeurs par défaut du modèle.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'ticket_number' => strtoupper($this->faker->bothify('WKD-####??')),
            'status' => $this->faker->randomElement(['pending', 'ready', 'delivered']),
            'delivery_at' => $this->faker->dateTimeBetween('now', '+2 hours'),
        ];
    }
}
