@extends('components.default')

@section('title', 'Register | Library Management System')

@section('content')
<section class="flex items-center justify-center min-h-screen bg-gray-100">

    <div class="w-full max-w-md p-8 bg-white/70 rounded-xl shadow-lg border border-[#C49A6C]">

        <h2 class="text-2xl font-bold text-center text-[#4C3B2A] mb-6">
            Create Account
        </h2>

        {{-- Display Validation Errors --}}
        @if ($errors->any())
            <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register.submit') }}" class="space-y-4">
            @csrf

            <input
                name="name"
                type="text"
                placeholder="Name"
                value="{{ old('name') }}"
                required
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#C49A6C]"
            >

            <input
                name="email"
                type="email"
                placeholder="Email"
                value="{{ old('email') }}"
                required
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#C49A6C]"
            >

            <input
                name="contact_number"
                type="text"
                placeholder="Contact Number"
                value="{{ old('contact_number') }}"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#C49A6C]"
            >

            <input
                name="address"
                type="text"
                placeholder="Address"
                value="{{ old('address') }}"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#C49A6C]"
            >

            <input
                name="password"
                type="password"
                placeholder="Password"
                required
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#C49A6C]"
            >

            <input
                name="password_confirmation"
                type="password"
                placeholder="Confirm Password"
                required
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-[#C49A6C]"
            >

            <button
                type="submit"
                class="w-full py-3 bg-[#4C3B2A] text-white rounded hover:bg-[#3a2b20] transition"
            >
                Register
            </button>
        </form>

        <p class="mt-4 text-center text-sm text-gray-700">
            Already registered?
            <a href="{{ route('users.login') }}" class="text-[#C49A6C] font-semibold hover:underline">
                Login
            </a>
        </p>
    </div>
</section>
@endsection
