<!-- resources/views/cart/index.blade.php -->
@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4">
    <h2 class="text-2xl font-bold text-green-800 mb-6">Your Cart</h2>

    @if (session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(count($cartItems) > 0)
        <div class="space-y-4">
            @php $total = 0; @endphp
            @foreach($cartItems as $cartItem)
                @php $total += $cartItem->product->price * $cartItem->quantity; @endphp
                <div class="bg-white rounded-lg shadow p-4 flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <img src="{{ asset('storage/' . $cartItem->product->image) }}" class="w-24 h-24 object-cover rounded" alt="{{ $cartItem->product->name }}">
                        <div>
                            <h4 class="text-lg font-bold text-gray-800">{{ $cartItem->product->name }}</h4>
                            <p class="text-green-600 font-semibold">{{ $cartItem->product->price }} Birr × {{ $cartItem->quantity }}</p>
                        </div>
                    </div>
                    <form action="{{ route('cart.remove', $cartItem->product_id) }}" method="POST">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-800">Remove</button>
                    </form>
                </div>
            @endforeach

            <div class="text-right text-xl font-bold text-green-700 mt-4">
                Total: {{ $total }} Birr
            </div>

            <div class="text-right mt-6">
                <a href="{{ route('checkout') }}" class="bg-green-600 text-white px-6 py-2 rounded hover:bg-green-700">Proceed to Checkout</a>
            </div>
        </div>
    @else
        <p class="text-gray-600">Your cart is empty.</p>
    @endif
</div>
@endsection