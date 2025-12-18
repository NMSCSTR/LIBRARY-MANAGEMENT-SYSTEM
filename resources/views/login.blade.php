@extends('components.default')

@section('title', 'Login | Library Management Information System')

@section('content')
<section class="relative flex items-center justify-center min-h-screen overflow-hidden">

    <!-- Login Form -->
    <div class="relative z-10 w-full max-w-md mx-auto p-8
                bg-white/70 rounded-xl shadow-lg border border-[#C49A6C] animate-fade-in">
        <div class="text-center mb-6">
            <img src="https://images.unsplash.com/photo-1512820790803-83ca734da794?auto=format&fit=crop&w=400&q=80"
                alt="Book Icon"
                class="w-24 h-24 mx-auto mb-4 rounded-lg shadow-md border-2 border-[#8B5E3C] object-cover">

            <h2 class="text-2xl font-bold text-[#4C3B2A]">Welcome Back</h2>
            <p class="text-sm text-[#4C3B2A]/80">Please login to your account</p>
        </div>

        <form method="POST" action="{{ route('users.login.submit') }}" class="space-y-4">
            @csrf

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-[#4C3B2A]">Email Address</label>
                <input type="email" id="email" name="email" required autofocus class="w-full px-4 py-2 mt-1 border rounded-lg bg-white/70
                              focus:outline-none focus:ring-2 focus:ring-[#C49A6C] focus:border-transparent">
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-[#4C3B2A]">Password</label>
                <input type="password" id="password" name="password" required class="w-full px-4 py-2 mt-1 border rounded-lg bg-white/70
                              focus:outline-none focus:ring-2 focus:ring-[#C49A6C] focus:border-transparent">
            </div>

            <!-- Remember Me -->
            <div class="flex items-center justify-between text-sm text-[#4C3B2A]">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="remember" class="form-checkbox text-[#C49A6C]">
                    <span class="ml-2">Remember me</span>
                </label>
            </div>

            <!-- Login Button -->
            <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-[#8B5E3C] to-[#4C3B2A]
                           text-white font-semibold rounded-lg shadow hover:shadow-xl
                           hover:from-[#7a5032] hover:to-[#3c2f24] transition-all duration-300">
                Login
            </button>
        </form>

        <!-- Footer -->
        <p class="mt-6 text-center text-sm text-[#4C3B2A]">
            Don't have an account?
            <a href="{{ url('register') }}" class="text-[#C49A6C] font-semibold hover:underline">Register</a>
        </p>
    </div>
</section>

<!-- Fade Animation -->
<style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(15px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.9s ease forwards;
        opacity: 0;
    }
</style>
@endsection

@push('scripts')
@include('components.alerts')
@endpush
