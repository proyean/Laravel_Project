<x-guest-layout>
    <div class="max-w-md mx-auto mt-10 p-6 bg-white shadow-lg rounded-xl">
        <h2 class="text-2xl font-bold text-center mb-6">Create an Account</h2>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            @if ($errors->any())
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded">
                <ul class="list-disc list-inside text-sm text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <!-- Name -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus
                    class="w-full mt-1 p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-indigo-300 @error('name') border-red-500 @enderror">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                    class="w-full mt-1 p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-indigo-300 @error('email') border-red-500 @enderror">
                @error('email')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                <input id="password" type="password" name="password" required
                    class="w-full mt-1 p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-indigo-300 @error('password') border-red-500 @enderror">
                @error('password')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    class="w-full mt-1 p-2 border border-gray-300 rounded focus:outline-none focus:ring focus:ring-indigo-300">
            </div>

            <button type="submit"
                class="w-full bg-indigo-600 text-white py-2 rounded hover:bg-indigo-700 transition duration-200">
                Register
            </button>
        </form>

        <div class="text-center mt-6">
            <p class="text-sm">Already have an account?
                <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Login</a>
            </p>
        </div>
    </div>
</x-guest-layout>
