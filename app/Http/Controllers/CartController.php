<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;

class CartController extends Controller
{
   
public function store(Request $request, $id)
{
     $quantity = $request->input('quantity',1);

    $product = Product::findOrFail($id);
    if ($product->stock < $quantity) {
        return redirect()->back()->with('error', 'Not enough stock available.');
    }

    // Check if the user is authenticated
    if (!Auth::check()) {
        return redirect()->route('login')->with('error', 'You need to be logged in to add items to the cart.');
    }

    // Add product to cart
   
    $user = Auth::user();
    $cartItem = Cart::where('user_id', $user->id)->where('product_id', $id)->first();

    if ($cartItem) {
        $newQuantity = $cartItem->quantity + $quantity;
        $cartItem->quantity = min($newQuantity, $product->stock);
        $cartItem->save();
    } else {
        Cart::create([
            'user_id' => $user->id,
            'product_id' => $id,
            'quantity' => min($quantity, $product->stock),
        ]);
    }

    return redirect()->route('cart.index')->with('success', 'Product added to cart!');
}

public function update(Request $request, $id)
{
    $request->validate([
        'quantity' => 'required|integer|min:1'
    ]);

    $user = Auth::user();
    $cartItem = Cart::where('user_id', $user->id)
        ->where('product_id', $id)
        ->firstOrFail();
    
    $product = Product::findOrFail($id);
    
    // Validate stock availability
    if ($product->stock < $request->quantity) {
        return redirect()->back()->with('error', 'Not enough stock available. Maximum available: ' . $product->stock);
    }

    $cartItem->quantity = $request->quantity;
    $cartItem->save();

    return redirect()->route('cart.index')->with('success', 'Cart updated successfully.');
}

public function index()
{
    $user = Auth::user();
    $cartItems = Cart::with('product')->where('user_id', $user->id)->get();
    
    // Remove items that are out of stock
    foreach ($cartItems as $item) {
        if ($item->product->stock <= 0) {
            $item->delete();
        } elseif ($item->quantity > $item->product->stock) {
            $item->quantity = $item->product->stock;
            $item->save();
        }
    }
    
    // Refresh cart items after potential changes
    $cartItems = Cart::with('product')->where('user_id', $user->id)->get();
    return view('cart.index', compact('cartItems'));
}

public function remove($id)
{
    $user = Auth::user();
    Cart::where('user_id', $user->id)->where('product_id', $id)->delete();
    return redirect()->route('cart.index')->with('success', 'Product removed from cart.');
}
}
