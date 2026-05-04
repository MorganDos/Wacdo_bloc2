<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        $products = Product::orderBy('category')->orderBy('name')->get();

        return view('products.index', compact('products'));
    }

    public function create(): View
    {
        return view('products.create', [
            'product' => new Product([
                'availability' => 'available',
            ]),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|string|max:255',
            'availability' => 'required|in:available,out_of_stock',
        ]);

        Product::create($data);

        return redirect()->route('products.index')->with('success', 'Produit ajouté.');
    }

    public function edit(Product $product): View
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|string|max:255',
            'availability' => 'required|in:available,out_of_stock',
        ]);

        $product->update($data);

        return redirect()->route('products.index')->with('success', 'Produit mis à jour.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        if ($product->orders()->exists() || $product->menus()->exists()) {
            return redirect()
                ->route('products.index')
                ->with('error', 'Ce produit est utilisé dans une commande ou un menu. Passez-le en rupture au lieu de le supprimer.');
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produit supprimé.');
    }
}
