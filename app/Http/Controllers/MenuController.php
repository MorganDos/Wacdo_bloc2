<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MenuController extends Controller
{
    public function index(): View
    {
        $menus = Menu::with(['products' => fn ($builder) => $builder->orderBy('name')])
            ->orderBy('name')
            ->get();

        return view('menus.index', compact('menus'));
    }

    public function create(): View
    {
        $products = Product::orderBy('category')->orderBy('name')->get();

        return view('menus.create', [
            'menu' => new Menu(['is_active' => true]),
            'products' => $products,
            'selectedProductIds' => [],
            'quantitiesByProductId' => [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        [$data, $pivotData] = $this->validatedPayload($request);
        $isActive = $request->boolean('is_active');

        // On crée le menu et sa composition dans la même transaction.
        DB::transaction(function () use ($data, $pivotData, $isActive): void {
            $menu = Menu::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'price' => $data['price'],
                'is_active' => $isActive,
            ]);

            $menu->products()->sync($pivotData);
        });

        return redirect()->route('menus.index')->with('success', 'Menu ajouté.');
    }

    public function edit(Menu $menu): View
    {
        $menu->load(['products' => fn ($builder) => $builder->orderBy('name')]);

        return view('menus.edit', [
            'menu' => $menu,
            'products' => Product::orderBy('category')->orderBy('name')->get(),
            'selectedProductIds' => $menu->products->pluck('id')->all(),
            'quantitiesByProductId' => $menu->products
                ->mapWithKeys(fn (Product $product): array => [$product->id => $product->pivot->quantity])
                ->all(),
        ]);
    }

    public function update(Request $request, Menu $menu): RedirectResponse
    {
        [$data, $pivotData] = $this->validatedPayload($request);
        $isActive = $request->boolean('is_active');

        DB::transaction(function () use ($menu, $data, $pivotData, $isActive): void {
            $menu->update([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
                'price' => $data['price'],
                'is_active' => $isActive,
            ]);

            $menu->products()->sync($pivotData);
        });

        return redirect()->route('menus.index')->with('success', 'Menu mis à jour.');
    }

    public function destroy(Menu $menu): RedirectResponse
    {
        $menu->delete();

        return redirect()->route('menus.index')->with('success', 'Menu supprimé.');
    }

    private function validatedPayload(Request $request): array
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_active' => 'nullable|boolean',
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => ['required', 'integer', 'distinct', Rule::exists('products', 'id')],
            'quantities' => 'required|array',
            'quantities.*' => 'nullable|integer|min:1',
        ]);

        // Prépare les lignes de la table pivot menu_product.
        $pivotData = [];
        foreach ($data['product_ids'] as $productId) {
            $pivotData[(int) $productId] = [
                'quantity' => max(1, (int) ($data['quantities'][$productId] ?? 1)),
            ];
        }

        return [$data, $pivotData];
    }
}
