<!-- resources/views/orders/history.blade.php -->
@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-4">
    <h2 class="text-2xl font-bold text-green-800 mb-6">Order History</h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
            {{ session('success') }}
        </div>
    @endif

    @forelse($orders as $order)
        <div class="bg-white rounded-lg shadow p-6 mb-6 hover:shadow-lg transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div>
                    <h3 class="font-bold text-lg">Order #{{ $order->id }}</h3>
                    <p class="text-gray-600">{{ $order->created_at->format('Y-m-d H:i') }}</p>
                    <span class="inline-block mt-2 px-3 py-1 text-sm rounded-full 
                        @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                        @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                        @elseif($order->status == 'shipped') bg-purple-100 text-purple-800
                        @elseif($order->status == 'delivered') bg-green-100 text-green-800
                        @elseif($order->status == 'cancelled') bg-red-100 text-red-800
                        @endif">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
                <div class="text-right">
                    <p class="text-xl font-bold text-green-700">
                        {{ number_format($order->total_amount, 2) }} Birr
                    </p>
                </div>
            </div>
            
            <ul class="mb-4">
                @foreach($order->items as $item)
                    <li class="flex justify-between py-2 border-b">
                        <span>{{ $item->product->name }} × {{ $item->quantity }}</span>
                        <span>{{ number_format($item->price * $item->quantity, 2) }} Birr</span>
                    </li>
                @endforeach
            </ul>
            
            <div class="flex justify-between items-center mt-4">
                <div>
                    @if($order->status == 'pending')
                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST" class="inline">
                        @csrf
                        @method('POST')
                        <button type="submit" 
                                onclick="return confirm('Cancel order #{{ $order->id }}?')"
                                class="text-red-600 hover:text-red-800 text-sm">
                            Cancel Order
                        </button>
                    </form>
                    @endif
                </div>
                <div class="space-x-3">
                    <a href="{{ route('orders.show', $order->id) }}" 
                       class="text-green-600 hover:text-green-800 font-medium">
                        View Details
                    </a>
                    <a href="{{ route('orders.track', $order->id) }}" 
                       class="text-blue-600 hover:text-blue-800 font-medium">
                        Track Order
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-12">
            <p class="text-gray-600 text-lg mb-4">You have no orders yet.</p>
            <a href="{{ route('products') }}" class="text-green-600 hover:text-green-800 font-medium">
                Start Shopping →
            </a>
        </div>
    @endforelse
</div>
@endsection