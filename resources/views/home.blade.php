@extends('layouts.app')

@section('content')

<!-- Featured Products Section -->
<section class="py-16">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-8">
            <h2 class="text-4xl font-extrabold text-black mb-2 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-2 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                Featured Products
            </h2>
        </div>
        
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
            @foreach($featuredProducts as $product)
            <div class="group">
                <a href="{{ route('product.show', $product->id) }}" class="block">
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden">
                        <div class="relative aspect-w-4 aspect-h-3">
                            @if($product->image)
                                <img 
                                    src="{{ asset('storage/' . $product->image) }}" 
                                    alt="{{ $product->name }}"
                                    class="w-full h-64 object-cover object-center"
                                    onerror="this.src='{{ asset('images/placeholder.jpg') }}'"
                                >
                            @else
                                <img 
                                    src="{{ asset('images/placeholder.jpg') }}" 
                                    alt="Product placeholder"
                                    class="w-full h-64 object-cover object-center"
                                >
                            @endif
                            
                            <div class="absolute top-4 left-4 flex flex-col gap-2">
                                @if($product->isNew())
                                    <span class="inline-flex items-center px-3 py-1.5 bg-green-600 text-white text-sm font-medium rounded-full">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 100-12 6 6 0 000 12zm.293-9.707a1 1 0 011.414 0l2 2a1 1 0 01-1.414 1.414L11 8.414V12a1 1 0 11-2 0V8.414L7.707 9.707a1 1 0 01-1.414-1.414l2-2z" clip-rule="evenodd"/>
                                        </svg>
                                        New
                                    </span>
                                @endif
                                <span class="inline-flex items-center px-3 py-1.5 bg-yellow-500 text-white text-sm font-medium rounded-full">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                    Featured
                                </span>
                            </div>
                        </div>
                        
                        <div class="p-6 text-center">
                            <div class="text-sm text-green-600 font-medium mb-2">{{ $product->category->name }}</div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2 group-hover:text-green-600 transition-colors">
                                {{ $product->name }}
                            </h3>
                            <p class="text-gray-600 mb-4">{{ Str::limit($product->description, 60) }}</p>
                            <div class="flex items-center justify-between mb-2">
                                <div class="text-2xl font-bold text-green-600">${{ number_format($product->price, 2) }}</div>
                                @if($product->isNew())
                                    <span class="px-3 py-1 bg-green-600 text-white text-sm font-medium rounded-full">
                                        New!
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>



@endsection
