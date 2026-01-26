@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <!-- Back Button -->
    <a href="{{ route('orders.history') }}" class="inline-flex items-center text-green-600 hover:text-green-700 mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Orders
    </a>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Order Card -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <!-- Order Header -->
        <div class="bg-green-50 px-6 py-4 border-b">
            <div class="flex justify-between items-center">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Order #{{ $order->id }}</h1>
                    <p class="text-gray-600">{{ $order->created_at->format('F d, Y - h:i A') }}</p>
                </div>
                <div>
                    <span class="px-3 py-1 rounded-full text-sm font-medium 
                        @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                        @elseif($order->status == 'shipped') bg-purple-100 text-purple-800
                        @elseif($order->status == 'delivered') bg-green-100 text-green-800
                        @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Order Items</h2>
            <div class="space-y-4">
                @foreach($order->items as $item)
                <div class="flex justify-between items-center border-b pb-4">
                    <div class="flex items-center">
                        @if($item->product && $item->product->image)
                        <img src="{{ asset('storage/' . $item->product->image) }}" 
                             alt="{{ $item->product->name }}"
                             class="w-16 h-16 object-cover rounded mr-4">
                        @endif
                        <div>
                            <h3 class="font-medium">{{ $item->product->name ?? 'Product' }}</h3>
                            <p class="text-sm text-gray-500">Quantity: {{ $item->quantity }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-medium">{{ number_format($item->price * $item->quantity, 2) }} Birr</p>
                        <p class="text-sm text-gray-500">{{ number_format($item->price, 2) }} Birr each</p>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Order Total -->
            <div class="mt-8">
                <div class="bg-gray-50 rounded-lg p-6">
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Subtotal:</span>
                        <span class="font-medium">{{ number_format($order->total_amount, 2) }} Birr</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Shipping:</span>
                        <span class="font-medium">0.00 Birr</span>
                    </div>
                    <div class="flex justify-between mb-2">
                        <span class="text-gray-600">Tax:</span>
                        <span class="font-medium">0.00 Birr</span>
                    </div>
                    <div class="border-t pt-3 mt-3">
                        <div class="flex justify-between">
                            <span class="text-xl font-bold">Total:</span>
                            <span class="text-xl font-bold text-green-600">{{ number_format($order->total_amount, 2) }} Birr</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-8 flex space-x-4">
                @if($order->status == 'pending')
                <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="inline">
                    @csrf
                    @method('POST')
                    <button type="submit" 
                            onclick="return confirm('Cancel order #{{ $order->id }}?')"
                            class="px-6 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                        Cancel Order
                    </button>
                </form>
                @endif
                <a href="{{ route('orders.history') }}"
                   class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50">
                    Back to Orders
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
