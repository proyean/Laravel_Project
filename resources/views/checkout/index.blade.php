@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Checkout</h1>
            <p class="text-gray-600 mt-2">Complete your order with shipping details</p>
        </div>

        @if(session('error'))
        <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 rounded">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-red-700">{{ session('error') }}</p>
                </div>
            </div>
        </div>
        @endif

        <div class="lg:grid lg:grid-cols-12 lg:gap-8">
            <!-- Shipping Form -->
            <div class="lg:col-span-8">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                            <svg class="w-5 h-5 text-green-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            Shipping Information
                        </h2>
                        <p class="text-gray-600 text-sm mt-1">Enter your delivery details</p>
                    </div>

                    <form action="{{ route('checkout.process') }}" method="POST">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Phone Number -->
                            <div>
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    Phone Number <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" id="phone" name="phone"
                                       value="{{ old('phone', auth()->user()->phone) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                       placeholder="+251 9XX XXX XXX"
                                       required>
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- City -->
                            <div>
                                <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                    City <span class="text-red-500">*</span>
                                </label>
                                <input type="text" id="city" name="city"
                                       value="{{ old('city', auth()->user()->city) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                       placeholder="Addis Ababa"
                                       required>
                                @error('city')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Address -->
                            <div class="md:col-span-2">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                    Delivery Address <span class="text-red-500">*</span>
                                </label>
                                <textarea id="address" name="address" rows="3"
                                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                          placeholder="House No, Street, Area, Landmark"
                                          required>{{ old('address', auth()->user()->address) }}</textarea>
                                <p class="mt-2 text-sm text-gray-500">Please provide complete address for accurate delivery</p>
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Country -->
                            <div>
                                <label for="country" class="block text-sm font-medium text-gray-700 mb-2">
                                    Country
                                </label>
                                <select id="country" name="country"
                                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition">
                                    <option value="Ethiopia" {{ old('country', auth()->user()->country ?? 'Ethiopia') == 'Ethiopia' ? 'selected' : '' }}>
                                        Ethiopia
                                    </option>
                                </select>
                            </div>

                            <!-- Postal Code -->
                            <div>
                                <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                                    Postal Code (Optional)
                                </label>
                                <input type="text" id="postal_code" name="postal_code"
                                       value="{{ old('postal_code', auth()->user()->postal_code) }}"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500 transition"
                                       placeholder="1000">
                            </div>
                        </div>

                        <!-- Save Address Checkbox -->
                        @if(!$hasSavedAddress)
                        <div class="mt-6">
                            <div class="flex items-center">
                                <input id="save_to_profile" name="save_to_profile" type="checkbox" value="1" checked
                                       class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                <label for="save_to_profile" class="ml-2 block text-sm text-gray-700">
                                    Save this address to my profile for future orders
                                </label>
                            </div>
                        </div>
                        @endif

                        <!-- Action Buttons -->
                        <div class="mt-8 pt-6 border-t border-gray-200 flex flex-col sm:flex-row sm:justify-between gap-4">
                            <a href="{{ route('cart.index') }}"
                               class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                Back to Cart
                            </a>
                            <button type="submit"
                                    class="inline-flex items-center justify-center px-8 py-3 border border-transparent rounded-lg text-white bg-green-600 hover:bg-green-700 focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                                Complete Order
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-4">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 sticky top-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Order Summary</h2>

                    <!-- Items List -->
                    <div class="mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-sm font-medium text-gray-700">Items ({{ $cartItems->sum('quantity') }})</h3>
                            <a href="{{ route('cart.index') }}" class="text-sm text-green-600 hover:text-green-700">
                                Edit
                            </a>
                        </div>

                        <div class="space-y-4">
                            @foreach($cartItems as $item)
                            <div class="flex items-start space-x-4">
                                @if($item->product->image)
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('storage/' . $item->product->image) }}"
                                         alt="{{ $item->product->name }}"
                                         class="w-16 h-16 object-cover rounded-lg">
                                </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $item->product->name }}
                                    </p>
                                    <p class="text-sm text-gray-500">
                                        Quantity: {{ $item->quantity }}
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">
                                        {{ number_format($item->product->price * $item->quantity, 2) }} Birr
                                    </p>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Price Breakdown -->
                    <div class="border-t border-b border-gray-200 py-4 space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal</span>
                            <span class="font-medium">{{ number_format($total, 2) }} Birr</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Shipping</span>
                            <span class="font-medium">0.00 Birr</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Tax</span>
                            <span class="font-medium">0.00 Birr</span>
                        </div>
                    </div>

                    <!-- Total -->
                    <div class="mt-6">
                        <div class="flex justify-between items-center">
                            <span class="text-lg font-semibold text-gray-900">Total</span>
                            <span class="text-2xl font-bold text-green-600">
                                {{ number_format($total, 2) }} Birr
                            </span>
                        </div>
                    </div>

                    <!-- Additional Info -->
                    <div class="mt-6 p-4 bg-green-50 rounded-lg">
                        <div class="flex">
                            <svg class="h-5 w-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-green-800">Free Shipping</p>
                                <p class="text-xs text-green-600 mt-1">All orders within Ethiopia enjoy free shipping</p>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Info -->
                    <div class="mt-4 text-center">
                        <p class="text-xs text-gray-500">
                            <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"/>
                            </svg>
                            Secure payment · Cash on delivery
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add some custom styles -->
<style>
    .sticky {
        position: -webkit-sticky;
        position: sticky;
    }
    
    input:focus, textarea:focus, select:focus {
        outline: none;
        ring-width: 2px;
    }
</style>
@endsection