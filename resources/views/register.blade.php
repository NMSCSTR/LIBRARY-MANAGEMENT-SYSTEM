@extends('components.default')

@section('title', 'Register | Library Management System')

@section('content')
<section class="flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md p-8 bg-white/70 rounded-xl shadow-lg border border-[#C49A6C]">

        <h2 class="text-2xl font-bold text-center text-[#4C3B2A] mb-6">
            Create Account
        </h2>

        <form method="POST" action="{{ route('register.submit') }}" class="space-y-4">
            @csrf

            <input name="name" placeholder="Name" required class="w-full px-4 py-2 border rounded">
            <input name="email" type="email" placeholder="Email" required class="w-full px-4 py-2 border rounded">
            <input name="contact_number" placeholder="Contact Number" class="w-full px-4 py-2 border rounded">
            <input name="address" placeholder="Address" class="w-full px-4 py-2 border rounded">

            <input name="password" type="password" placeholder="Password" required class="w-full px-4 py-2 border rounded">
            <input name="password_confirmation" type="password"
                   placeholder="Confirm Password" required
                   class="w-full px-4 py-2 border rounded">

            <button class="w-full py-3 bg-[#4C3B2A] text-white rounded">
                Register
            </button>
        </form>

        <p class="mt-4 text-center text-sm">
            Already registered?
            <a href="{{ route('users.login') }}" class="text-[#C49A6C] font-semibold">
                Login
            </a>
        </p>
    </div>
</section>
@endsection
