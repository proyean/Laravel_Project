<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Product extends Model
{ 
    protected $fillable = ['name', 'description', 'price', 'image', 'stock', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function isNew()
    {
        return $this->created_at >= now()->subDays(7); // New if added in the last 7 days
    }

    /**
     * Safely decrease the product stock
     */
    public function decreaseStock($quantity)
    {
        if ($this->stock < $quantity) {
            Log::error("Attempted to decrease stock below 0 for product {$this->name}");
            return false;
        }

        $oldStock = $this->stock;
        $this->stock -= $quantity;
        $this->save();

        Log::info("Stock decreased for product {$this->name} from {$oldStock} to {$this->stock}");
        return true;
    }

    /**
     * Safely increase the product stock
     */
    public function increaseStock($quantity)
    {
        $oldStock = $this->stock;
        $this->stock += $quantity;
        $this->save();

        Log::info("Stock increased for product {$this->name} from {$oldStock} to {$this->stock}");
        return true;
    }
}
