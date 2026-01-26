@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-8">
    <a href="{{ route('orders.show', $order->id) }}" class="inline-flex items-center text-green-600 hover:text-green-700 mb-6">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Order
    </a>

    <div class="bg-white rounded-lg shadow p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Track Order #{{ $order->id }}</h1>
        
        <!-- Status Timeline -->
        <div class="mt-8 space-y-8">
            @php
                $statuses = [
                    'pending' => ['Pending', 'Your order has been received'],
                    'processing' => ['Processing', 'We are preparing your order'],
                    'shipped' => ['Shipped', 'Your order is on its way'],
                    'delivered' => ['Delivered', 'Your order has been delivered'],
                ];
                
                $currentIndex = array_search($order->status, array_keys($statuses));
            @endphp
            
            @foreach($statuses as $key => $status)
            <div class="flex items-start">
                <div class="relative">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center
                        @if($currentIndex >= array_search($key, array_keys($statuses)))
                            bg-green-500 text-white
                        @else
                            bg-gray-200 text-gray-500
                        @endif">
                        @if($currentIndex > array_search($key, array_keys($statuses)))
                            ✓
                        @else
                            {{ $loop->iteration }}
                        @endif
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="font-medium @if($currentIndex >= array_search($key, array_keys($statuses))) text-green-700 @else text-gray-500 @endif">
                        {{ $status[0] }}
                    </h3>
                    <p class="text-sm text-gray-600">{{ $status[1] }}</p>
                    @if($currentIndex == array_search($key, array_keys($statuses)))
                        <p class="text-sm text-green-600 mt-1">Current Status</p>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
