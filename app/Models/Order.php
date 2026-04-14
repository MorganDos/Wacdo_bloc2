<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['ticket_number', 'status', 'delivery_at'];

    protected function casts(): array
    {
        return [
            'delivery_at' => 'datetime',
        ];
    }

    // Relation vers les produits de la commande via la table pivot order_product.
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity')->withTimestamps();
    }
}
