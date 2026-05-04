<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        // On n'affiche que les commandes encore actives pour le back-office.
        $query = Order::whereIn('status', ['pending', 'ready'])
            ->with(['products' => fn ($builder) => $builder->orderBy('name')])
            ->orderBy('delivery_at')
            ->orderBy('created_at');

        $orders = $query->get();

        return view('orders.index', compact('orders'));
    }

    public function create(): View
    {
        $products = $this->orderableProducts();

        return view('orders.create', [
            'order' => null,
            'products' => $products,
            'selectedProductIds' => [],
            'quantitiesByProductId' => [],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        [$data, $pivotData] = $this->validatedPayload($request);

        // La commande et ses lignes doivent être enregistrées ensemble.
        DB::transaction(function () use ($data, $pivotData): void {
            $order = Order::create([
                'status' => 'pending',
                'ticket_number' => $this->generateTicketNumber(),
                'delivery_at' => $data['delivery_at'] ?? null,
            ]);

            $order->products()->sync($pivotData);
        });

        return redirect()->route('orders.index')->with('success', 'Commande ajoutée.');
    }

    public function edit(Order $order): RedirectResponse|View
    {
        if ($order->status !== 'pending') {
            return redirect()->route('orders.index')->with('error', 'Seules les commandes en attente peuvent être modifiées.');
        }

        $order->load('products');
        $products = $this->orderableProducts($order);

        return view('orders.edit', [
            'order' => $order,
            'products' => $products,
            'selectedProductIds' => $order->products->pluck('id')->all(),
            'quantitiesByProductId' => $order->products
                ->mapWithKeys(fn (Product $product): array => [$product->id => $product->pivot->quantity])
                ->all(),
        ]);
    }

    public function update(Request $request, Order $order): RedirectResponse
    {
        if ($order->status !== 'pending') {
            return redirect()->route('orders.index')->with('error', 'Seules les commandes en attente peuvent être modifiées.');
        }

        [$data, $pivotData] = $this->validatedPayload($request, $order);

        DB::transaction(function () use ($order, $data, $pivotData): void {
            $order->update([
                'delivery_at' => $data['delivery_at'] ?? null,
            ]);

            $order->products()->sync($pivotData);
        });

        return redirect()->route('orders.index')->with('success', 'Commande mise à jour.');
    }

    public function ready(Order $order): RedirectResponse
    {
        if ($order->status !== 'pending') {
            return redirect()->route('orders.index')->with('error', 'Seules les commandes en attente peuvent être marquées comme prêtes.');
        }

        $order->status = 'ready';
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Commande marquée comme prête.');
    }

    public function delivered(Order $order): RedirectResponse
    {
        if ($order->status !== 'ready') {
            return redirect()->route('orders.index')->with('error', 'Seules les commandes prêtes peuvent être marquées comme livrées.');
        }

        $order->status = 'delivered';
        $order->save();

        return redirect()->route('orders.index')->with('success', 'Commande marquée comme livrée.');
    }

    private function generateTicketNumber(): string
    {
        do {
            $candidate = 'WKD-' . Str::upper(Str::random(8));
        } while (Order::where('ticket_number', $candidate)->exists());

        return $candidate;
    }

    // Sélectionne les produits autorisés pour la création ou l'édition.
    private function orderableProducts(?Order $order = null)
    {
        $query = Product::query()
            ->orderBy('category')
            ->orderBy('name');

        if ($order === null) {
            return $query->where('availability', 'available')->get();
        }

        $selectedProductIds = $order->products()->pluck('products.id');

        return $query
            ->where(function ($builder) use ($selectedProductIds): void {
                // En édition, les produits déjà choisis restent visibles même s'ils sont devenus indisponibles.
                $builder
                    ->where('availability', 'available')
                    ->orWhereIn('id', $selectedProductIds);
            })
            ->get();
    }

    private function validatedPayload(Request $request, ?Order $order = null): array
    {
        $data = $request->validate([
            'delivery_at' => 'nullable|date',
            'product_ids' => 'required|array|min:1',
            'product_ids.*' => ['required', 'integer', 'distinct', Rule::exists('products', 'id')],
            'quantities' => 'required|array',
            'quantities.*' => 'nullable|integer|min:1',
        ]);

        $allowedProductIds = $this->orderableProducts($order)->pluck('id')->all();
        $invalidProductIds = array_diff($data['product_ids'], $allowedProductIds);

        if ($invalidProductIds !== []) {
            throw ValidationException::withMessages([
                'product_ids' => 'Un ou plusieurs produits sélectionnés ne sont pas disponibles pour cette commande.',
            ]);
        }

        $pivotData = [];
        // Transformation du formulaire en format attendu par sync().
        foreach ($data['product_ids'] as $productId) {
            $quantity = max(1, (int) ($data['quantities'][$productId] ?? 1));
            $pivotData[(int) $productId] = ['quantity' => $quantity];
        }

        return [$data, $pivotData];
    }
}
