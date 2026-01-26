<!-- resources/views/layouts/nav.blade.php -->

<nav class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 flex justify-between h-16 items-center">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="text-2xl font-bold text-green-600">Yejsira</a>
        
        <!-- Navigation Links -->
        <div class="flex items-center space-x-6 md:flex font-medium mr-2">
            <a href="{{ route('home') }}" 
               class="{{ request()->routeIs('home') ? 'text-green-600' : 'hover:text-green-600' }} transition">
                Home
            </a>
            <a href="{{ route('products') }}" 
               class="{{ request()->routeIs('products') ? 'text-green-600' : 'hover:text-green-600' }} transition">
                Products
            </a>
            
            <!-- Categories Dropdown -->
            <div class="relative group">
                <button class="{{ request()->routeIs('products.byCategory*') ? 'text-green-600' : 'hover:text-green-600' }} transition py-2">
                    Categories
                </button>
                <div class="absolute left-0 w-48 hidden group-hover:block bg-white shadow-lg rounded-md overflow-hidden z-50">
                    @foreach($Categories as $cat)
                    <a href="{{ route('products.byCategory', $cat->id) }}" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 transition-colors">
                        {{ $cat->name }}
                    </a>
                    @endforeach
                </div>
            </div>

            @auth
                @if(Auth::user()->is_admin)
                    <a href="{{ route('admin.dashboard') }}" 
                       class="{{ request()->routeIs('admin.dashboard') ? 'text-green-600' : 'hover:text-green-600' }} transition">
                        Dashboard
                    </a>
                @else
                    <a href="{{ route('cart.index') }}" 
                       class="{{ request()->routeIs('cart.index') ? 'text-green-600' : 'hover:text-green-600' }} transition">
                        Cart
                    </a>
                    <a href="{{ route('orders.history') }}" 
                       class="{{ request()->routeIs('orders.history') ? 'text-green-600' : 'hover:text-green-600' }} transition">
                        My Orders
                    </a>
                @endif
                
                <a href="{{ route('profile.show') }}" 
                   class="{{ request()->routeIs('profile.show') ? 'text-green-600' : 'hover:text-green-600' }} transition">
                    Profile
                </a>

                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="hover:text-red-600 transition">Logout</button>
                </form>
            @else
                <a href="{{ route('login') }}" 
                   class="{{ request()->routeIs('login') ? 'text-green-600' : 'hover:text-green-600' }} transition">
                    Login
                </a>
                <a href="{{ route('register') }}" 
                   class="{{ request()->routeIs('register') ? 'text-green-600' : 'hover:text-green-600' }} transition">
                    Register
                </a>
            @endauth
        </div>
    </div>
</nav>