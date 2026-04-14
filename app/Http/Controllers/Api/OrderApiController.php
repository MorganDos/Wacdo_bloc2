<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class OrderApiController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'delivery_at' => 'nullable|date',
            'products' => 'required|array|min:1',
            'products.*.product_id' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('products', 'id')->where(fn($query) => $query->where('availability', 'available')),
            ],
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        $order = DB::transaction(function () use ($data): Order {
            $order = Order::create([
                'status' => 'pending',
                'ticket_number' => $this->generateTicketNumber(),
                'delivery_at' => $data['delivery_at'] ?? null,
            ]);

            foreach ($data['products'] as $row) {
                $order->products()->attach($row['product_id'], ['quantity' => $row['quantity']]);
            }

            return $order->load('products');
        });

        return response()->json([
            'success' => true,
            'order_id' => $order->id,
            'ticket_number' => $order->ticket_number ?? null,
            'status' => $order->status,
        ], 201);
    }

    private function generateTicketNumber(): string
    {
        do {
            $candidate = 'WKD-' . Str::upper(Str::random(8));
        } while (Order::where('ticket_number', $candidate)->exists());

        return $candidate;
    }
}
