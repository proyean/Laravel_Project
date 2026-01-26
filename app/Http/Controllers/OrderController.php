<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{ 
    public function placeOrder(Request $request)
    {
        $cartItems = Cart::with('product')->where('user_id', auth()->id())->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        // Debug cart items
        Log::info('Cart Items:', $cartItems->toArray());

        // Validate stock availability before proceeding
        foreach ($cartItems as $item) {
            $product = $item->product;
            Log::info("Checking stock for product {$product->name}: Current stock: {$product->stock}, Requested: {$item->quantity}");
            
            if (!$product || $product->stock < $item->quantity) {
                return redirect()->route('cart.index')
                    ->with('error', "Not enough stock available for {$product->name}. Available: {$product->stock}");
            }
        }

        try {
            DB::beginTransaction();

            $order = new Order();
            $order->user_id = auth()->id();
            $order->status = 'pending';
            $order->total_amount = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });
            $order->save();

            Log::info("Order created with ID: {$order->id}");

            foreach ($cartItems as $item) {
                // Create order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price' => $item->product->price,
                ]);

                // Reduce stock using the new method
                $product = Product::lockForUpdate()->find($item->product_id);
                if (!$product->decreaseStock($item->quantity)) {
                    throw new \Exception("Failed to decrease stock for product {$product->name}");
                }
            }

            // Clear cart
            Cart::where('user_id', auth()->id())->delete();

            DB::commit();
            Log::info("Order completed successfully");

            return redirect()->route('orders.history')->with('success', 'Order placed successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Order failed: " . $e->getMessage());
            return redirect()->route('cart.index')
                ->with('error', 'There was an error processing your order. Please try again.');
        }
    }

    public function cancelOrder($id)
    {
        $order = Order::with('items.product')->find($id);

        if (!$order || $order->user_id !== auth()->id()) {
            return redirect()->route('orders.history')->with('error', 'Order not found.');
        }

        // Only allow cancellation of pending orders
        if ($order->status !== 'pending') {
            return redirect()->route('orders.history')
                ->with('error', 'Only pending orders can be cancelled.');
        }

        try {
            DB::beginTransaction();

            // Restore stock using the new method
            foreach ($order->items as $item) {
                $product = Product::lockForUpdate()->find($item->product_id);
                if (!$product->increaseStock($item->quantity)) {
                    throw new \Exception("Failed to restore stock for product {$product->name}");
                }
            }

            $order->delete();

            DB::commit();

            return redirect()->route('orders.history')
                ->with('success', 'Order canceled successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Order cancellation failed: " . $e->getMessage());
            return redirect()->route('orders.history')
                ->with('error', 'There was an error canceling your order. Please try again.');
        }
    }

    public function show($id)
    {
        $order = Order::with('items.product')->find($id);

        if (!$order || $order->user_id !== auth()->id()) {
            return redirect()->route('orders.history')->with('error', 'Order not found.');
        }

        return view('orders.show', compact('order'));
    }

    public function trackOrder($id)
    {
        $order = Order::find($id);

        if (!$order || $order->user_id !== auth()->id()) {
            return redirect()->route('orders.history')->with('error', 'Order not found.');
        }

        return view('orders.track', compact('order'));
    }

    public function history()
    {
        $orders = Order::with('items.product')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.history', compact('orders'));
    }
}
