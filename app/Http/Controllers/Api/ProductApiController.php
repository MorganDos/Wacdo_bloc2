<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Product::query();

        if ($request->filled('category')) {
            $query->where('category', $request->string('category'));
        }

        if (! $request->boolean('include_unavailable')) {
            $query->where('availability', 'available');
        }

        $products = $query->orderBy('name')->get()->map(function (Product $product): array {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'category' => $product->category,
                'price' => $product->price,
                'image' => $product->image,
                'availability' => $product->availability,
            ];
        })->values();

        return response()->json($products);
    }
}
