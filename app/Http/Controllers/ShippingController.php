<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Shipping;
use App\Models\Order;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
{
    $shippings =Shipping::with('order')->latest()->get();
    return view('admin.shippings.index', compact('shippings'));
}
public function show($id)
{
    $shipping = \App\Models\Shipping::with('order')->findOrFail($id);
    return view('admin.shippings.show', compact('shipping'));
}
 
    public function userupdate(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $data = $request->validate([
            'address' => 'required|string',
            'city' => 'required|string',
            'country' => 'required|string',
            'postal_code' => 'nullable|string',
            'status' => 'required|string',
        ]);
        $shipping = $order->shipping ?? new Shipping(['order_id' => $order->id]);
        $shipping->fill($data);
        $shipping->order_id = $order->id;
        $shipping->save();
 
    }

    public function update(Request $request, $id)
{
    $order = Order::findOrFail($id);
    $request->validate([
        'status' => 'required|string|in:pending,delivered,cancelled',
    ]);
    $order->status = $request->status;
    $order->save();

    return redirect()->route('admin.shipments.show', $order)->with('success', 'Shipment status updated!');
}

}