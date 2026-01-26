@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-12">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
        <!-- Product Image -->
        <div>
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-[400px] object-cover rounded-xl shadow">
        </div>

        <!-- Product Info -->
        <div>
            <h1 class="text-3xl font-bold text-gray-800 mb-4">{{ $product->name }}</h1>

            @if($product->category)
                <div class="mb-2 text-sm text-gray-500">
                    Category: <span class="font-medium">{{ $product->category->name }}</span>
                </div>
            @endif

            <div class="mb-2 text-gray-500">Stock: {{ $product->stock }}</div>
            <p class="text-gray-600 mb-4">{{ $product->description }}</p> 
            <div class="text-indigo-600 text-2xl font-semibold mb-6">
                ${{ number_format($product->price, 2) }}
            </div>

            @auth
                @if(!auth()->user()->is_admin)
                    @if($product->stock > 0)
                        <form action="{{ route('cart.store', $product->id) }}" method="POST" class="inline">
                            @csrf
                            <div class="flex items-center gap-4 space-x-3 mb-4">
                                <label for="quantity" class="font-medium">Quantity:</label>
                                <input
                                    type="number"
                                    id="quantity"
                                    name="quantity"
                                    value="1"
                                    min="1"
                                    max="{{ $product->stock }}"
                                    class="w-20 border rounded px-2 py-1"
                                    required
                                >
                            </div>
                            <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition">
                                Add to Cart
                            </button>
                        </form>
                    @else
                        <span class="inline-block px-4 py-2 bg-red-500 text-white rounded">Out of Stock</span>
                    @endif
                @elseif(auth()->user()->is_admin)
                    <div class="flex space-x-4">
                        <a href="{{ route('admin.products.edit', $product->id) }}" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition">
                            Edit Product
                        </a>
                        <form action="{{ route('products.toggleFeatured', $product->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-6 py-3 bg-yellow-600 text-white font-semibold rounded-xl hover:bg-yellow-700 transition">
                                {{ $product->featured ? 'Unfeature Product' : 'Feature Product' }}
                            </button>
                        </form>
                    </div>
                @endif
            @endauth

           @guest
    <form action="{{ route('login') }}" method="GET">
        <button type="submit" class="px-6 py-3 bg-indigo-600 text-white font-semibold rounded-xl hover:bg-indigo-700 transition mt-4">
            Add to Cart
        </button>
    </form>
@endguest

        </div>
    </div>

    @if($relatedProducts->count())
        <div class="mt-12">
            <h2 class="text-2xl font-bold mb-4">Related Products</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @foreach($relatedProducts as $related)  
                    <a href="{{ route('product.show', $related->id) }}" class="block border rounded-lg overflow-hidden shadow hover:shadow-lg transition-shadow duration-300 bg-white">
                        <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->name }}" class="w-full h-48 object-cover">
                        <div class="p-4">
                            <h2 class="font-semibold text-lg text-gray-900">{{ $related->name }}</h2>
                            <p class="text-green-600 font-semibold mt-1">${{ number_format($related->price, 2) }}</p>
                        </div>
                    </a>
                @endforeach 
            </div>
        </div>
    @endif
</div>
@endsection
