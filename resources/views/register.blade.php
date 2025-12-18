@extends('components.default')

@section('title', 'Register | Library Management System')

@section('content')
<section class="relative flex items-center justify-center min-h-screen overflow-hidden">

    <div class="relative z-10 w-full max-w-md mx-auto p-8
                bg-white/70 rounded-xl shadow-lg border border-[#C49A6C] animate-fade-in">
        <div class="text-center mb-6">
            <h2 class="text-2xl font-bold text-[#4C3B2A]">Create Account</h2>
            <p class="text-sm text-[#4C3B2A]/80">Fill in the details below to register</p>
        </div>

        <form method="POST" action="{{ route('register.submit') }}" class="space-y-4">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-[#4C3B2A]">Name</label>
                <input type="text" id="name" name="name" required class="w-full px-4 py-2 mt-1 border rounded-lg">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-[#4C3B2A]">Email Address</label>
                <input type="email" id="email" name="email" required class="w-full px-4 py-2 mt-1 border rounded-lg">
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-[#4C3B2A]">Password</label>
                <input type="password" id="password" name="password" required class="w-full px-4 py-2 mt-1 border rounded-lg">
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-[#4C3B2A]">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required class="w-full px-4 py-2 mt-1 border rounded-lg">
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-[#4C3B2A]">Role</label>
                <select name="role" id="role" required class="w-full px-4 py-2 mt-1 border rounded-lg">
                    @foreach(\App\Models\Role::all() as $role)
                        <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="contact_number" class="block text-sm font-medium text-[#4C3B2A]">Contact Number</label>
                <input type="text" id="contact_number" name="contact_number" class="w-full px-4 py-2 mt-1 border rounded-lg">
            </div>

            <div>
                <label for="address" class="block text-sm font-medium text-[#4C3B2A]">Address</label>
                <input type="text" id="address" name="address" class="w-full px-4 py-2 mt-1 border rounded-lg">
            </div>

            <button type="submit" class="w-full py-3 px-4 bg-gradient-to-r from-[#8B5E3C] to-[#4C3B2A]
                           text-white font-semibold rounded-lg shadow hover:shadow-xl
                           hover:from-[#7a5032] hover:to-[#3c2f24] transition-all duration-300">
                Register
            </button>
        </form>

        <p class="mt-6 text-center text-sm text-[#4C3B2A]">
            Already have an account?
            <a href="{{ route('users.login') }}" class="text-[#C49A6C] font-semibold hover:underline">Login</a>
        </p>
    </div>
</section>
@endsection
