<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shipping;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $cartItems = Cart::with('product')->where('user_id', $user->id)->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        
        $total = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });
        
        // Check if user has saved address
        $hasSavedAddress = $user->address && $user->city && $user->phone;
        
        return view('checkout.index', compact('cartItems', 'total', 'hasSavedAddress'));
    }

    public function process(Request $request)
    {
        $user = Auth::user();
        $cartItems = Cart::with('product')->where('user_id', $user->id)->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        
        // Check if user has saved address and wants to use it
        if ($user->address && $user->city && $user->phone && $request->filled('use_saved_address')) {
            // Use saved address automatically
            $address = $user->address;
            $city = $user->city;
            $country = $user->country ?? 'Ethiopia';
            $phone = $user->phone;
            $postal_code = $user->postal_code;
            
            // Save address update if provided
            if ($request->filled('update_profile')) {
                $user->update([
                    'address' => $request->address ?? $user->address,
                    'city' => $request->city ?? $user->city,
                    'country' => $request->country ?? $user->country,
                    'phone' => $request->phone ?? $user->phone,
                    'postal_code' => $request->postal_code ?? $user->postal_code,
                ]);
            }
        } else {
            // Validate and use new address
            $request->validate([
                'address' => 'required|string|max:255',
                'city' => 'required|string|max:100',
                'phone' => 'required|string|max:20',
                'country' => 'nullable|string|max:100',
            ]);
            
            $address = $request->address;
            $city = $request->city;
            $country = $request->country ?? 'Ethiopia';
            $phone = $request->phone;
            $postal_code = $request->postal_code;
            
            // Save to profile if checkbox is checked
            if ($request->filled('save_to_profile')) {
                $user->update([
                    'address' => $address,
                    'city' => $city,
                    'country' => $country,
                    'phone' => $phone,
                    'postal_code' => $postal_code,
                ]);
            }
        }

        // Calculate total
        $total = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        // Create order
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => $total,
            'status' => 'pending',
        ]);

        // Create order items
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
            
            // Reduce product stock
            $product = $item->product;
            $product->stock -= $item->quantity;
            $product->save();
        }
        
        // Create shipping record
        Shipping::create([
            'order_id' => $order->id,
            'address' => $address,
            'city' => $city,
            'country' => $country,
            'phone' => $phone,
            'postal_code' => $postal_code,
            'status' => 'pending',
        ]);

        // Clear the cart
        Cart::where('user_id', $user->id)->delete();

        return redirect()->route('orders.show', $order->id)
                         ->with('success', 'Order placed successfully!');
    }
    
    public function quickCheckout(Request $request)
    {
        $user = Auth::user();
        $cartItems = Cart::with('product')->where('user_id', $user->id)->get();
        
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        
        // Check if user has saved address
        if (!$user->address || !$user->city || !$user->phone) {
            return redirect()->route('checkout.index')
                ->with('error', 'Please save your address in profile first to use quick checkout.');
        }
        
        // Calculate total
        $total = $cartItems->sum(function($item) {
            return $item->product->price * $item->quantity;
        });

        // Create order
        $order = Order::create([
            'user_id' => $user->id,
            'total_amount' => $total,
            'status' => 'pending',
        ]);

        // Create order items
        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->product->price,
            ]);
            
            // Reduce product stock
            $product = $item->product;
            $product->stock -= $item->quantity;
            $product->save();
        }
        
        // Create shipping record using saved address
        Shipping::create([
            'order_id' => $order->id,
            'address' => $user->address,
            'city' => $user->city,
            'country' => $user->country ?? 'Ethiopia',
            'phone' => $user->phone,
            'postal_code' => $user->postal_code,
            'status' => 'pending',
        ]);

        // Clear the cart
        Cart::where('user_id', $user->id)->delete();

        return redirect()->route('orders.show', $order->id)
                         ->with('success', 'Order placed successfully using your saved address!');
    }
}