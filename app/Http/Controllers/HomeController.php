<?php

namespace App\Http\Controllers;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
     public function index()
    {
        // Get featured products with their categories
        $featuredProducts = Product::with('category')
            ->where('featured', true)
            ->where('stock', '>', 0)
            ->latest()
            ->take(6)
            ->get();

        // Get products by category
        $categories = Category::with(['products' => function($query) {
            $query->latest()->take(4);
        }])->get();

        return view('home', compact('featuredProducts', 'categories'));
    }
}
