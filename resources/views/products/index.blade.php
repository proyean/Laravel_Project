@extends('layouts.app')

@section('title', isset($category) ? $category->name : 'All Products')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <!-- Category Navigation -->
    <div class="mb-8 flex flex-wrap gap-2">
        <a href="{{ route('products') }}" 
           class="px-4 py-2 rounded-full {{ !isset($category) ? 'bg-green-600 text-white' : 'bg-gray-100 hover:bg-gray-200' }} transition">
            All Products
        </a>
        @foreach($categories as $cat)
            <a href="{{ route('products.byCategory', $cat->id) }}"
               class="px-4 py-2 rounded-full {{ isset($category) && $category->id == $cat->id ? 'bg-green-600 text-white' : 'bg-gray-100 hover:bg-gray-200' }} transition">
                {{ $cat->name }}
                <span class="text-sm ml-1">({{ $cat->products_count }})</span>
            </a>
        @endforeach
    </div>

    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-900">
            {{ isset($category) ? $category->name : 'All Products' }}
        </h1>
        @if($isAdmin)
            <a href="{{ route('admin.products.create') }}" 
               class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                Add New Product
            </a>
        @endif
    </div>

    <!-- Products Grid -->
    @if($products->count())
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($products as $product)
            <div class="bg-white border rounded-xl overflow-hidden shadow-sm hover:shadow-md transition group">
                <a href="{{ route('product.show', $product->id) }}" class="block relative">
                    <img src="{{ asset('storage/' . $product->image) }}" 
                         alt="{{ $product->name }}" 
                         class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                    @if($product->isNew())
                        <span class="absolute top-2 left-2 bg-green-600 text-white text-xs px-2 py-1 rounded-full">
                            New
                        </span>
                    @endif
                    @if($product->featured)
                        <span class="absolute top-2 right-2 bg-yellow-500 text-white text-xs px-2 py-1 rounded-full">
                            Featured
                        </span>
                    @endif
                </a>
                
                <div class="p-4">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm text-green-600">{{ $product->category->name }}</span>
                        <span class="font-bold text-green-600">${{ number_format($product->price, 2) }}</span>
                    </div>
                    
                    <h2 class="font-semibold text-gray-900 mb-1">{{ $product->name }}</h2>
                    <p class="text-sm text-gray-600 mb-4">{{ Str::limit($product->description, 60) }}</p>
                    
                    <div class="flex items-center justify-between">
                        @if(!$isAdmin)
                            <form action="{{ route('cart.store', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition text-sm">
                                    Add to Cart
                                </button>
                            </form>
                        @else
                            <div class="flex space-x-2">
                                <a href="{{ route('admin.products.edit', $product->id) }}" 
                                   class="text-blue-600 hover:text-blue-800 text-sm">
                                    Edit
                                </a>
                                <form action="{{ route('products.toggleFeatured', $product->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-yellow-600 hover:text-yellow-800 text-sm">
                                        {{ $product->featured ? 'Unfeature' : 'Feature' }}
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $products->links() }}
        </div>
    @else
        <div class="text-center py-12">
            <p class="text-gray-600">No products found in this category.</p>
        </div>
    @endif
</div>
@endsection
