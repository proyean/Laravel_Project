<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{ 
    public function index()
    {
        $isAdmin = Auth::check() && Auth::user()->is_admin;
        
        $query = Product::with('category');
        if (!$isAdmin) {
            $query->where('stock', '>', 0);
        }
        $products = $query->latest()->paginate(12);
        
        $categories = Category::withCount('products')->get();

        return view('products.index', compact('products', 'categories', 'isAdmin'));
    }

    public function byCategory($id)
    {
        $isAdmin = Auth::check() && Auth::user()->is_admin;
        $category = Category::findOrFail($id);
        
        $query = Product::where('category_id', $id)->with('category');
        if (!$isAdmin) {
            $query->where('stock', '>', 0);
        }
        $products = $query->latest()->paginate(12);
        
        $categories = Category::withCount('products')->get();

        return view('products.index', compact('products', 'categories', 'category', 'isAdmin'));
    }

    public function show($id)
    {
        $product = Product::with('category')->findOrFail($id);
        $isAdmin = Auth::check() && Auth::user()->is_admin;

        // Redirect non-admin users if product is out of stock
        if (!$isAdmin && $product->stock <= 0) {
            return redirect()->route('products.index')
                ->with('error', 'This product is currently out of stock.');
        }

        $relatedProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->when(!$isAdmin, function($query) {
                return $query->where('stock', '>', 0);
            })
            ->with('category')
            ->latest()
            ->take(4)
            ->get();

        return view('products.show', compact('product', 'relatedProducts', 'isAdmin'));
    }

    public function toggleFeatured($id)
    {
        if (!Auth::check() || !Auth::user()->is_admin) {
            abort(403);
        }

        $product = Product::findOrFail($id);
        $product->featured = !$product->featured;
        $product->save();

        return back()->with('success', 'Product featured status updated');
    }
}
