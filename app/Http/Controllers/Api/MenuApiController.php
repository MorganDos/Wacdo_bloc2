<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;

class MenuApiController extends Controller
{
    public function index(): JsonResponse
    {
        $menus = Menu::where('is_active', true)
            ->with(['products' => function ($query): void {
                $query->where('availability', 'available')->orderBy('name');
            }])
            ->orderBy('name')
            ->get()
            ->map(function (Menu $menu): array {
                return [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'description' => $menu->description,
                    'price' => $menu->price,
                    'products' => $menu->products->map(function ($product): array {
                        return [
                            'id' => $product->id,
                            'name' => $product->name,
                            'description' => $product->description,
                            'category' => $product->category,
                            'price' => $product->price,
                            'image' => $product->image,
                            'quantity' => $product->pivot->quantity,
                        ];
                    })->values(),
                ];
            })
            ->values();

        return response()->json($menus);
    }
}
