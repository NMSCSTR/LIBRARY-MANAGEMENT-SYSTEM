@extends('components.default')

@section('title', 'Register | Santo Niño Parish Church')

@section('content')
<section class="relative flex items-center justify-center min-h-screen bg-gradient-to-br text-[#2c1c0f] overflow-hidden">

    <!-- 🪟 Background Image -->
    <div class="absolute inset-0 bg-[url('https://t4.ftcdn.net/jpg/04/89/89/53/360_F_489895355_Z4Hb6t1lSLT0zo66l5uWDnfmUGwdlkP4.jpg')] bg-cover bg-center opacity-8 mix-blend-overlay z-0"></div>

    <!-- ✨ Veil -->
    <div class="absolute inset-0 bg-white/20 backdrop-blur-sm z-1"></div>

    <!-- 📝 Register Form -->
    <div class="relative z-10 w-full max-w-md mx-auto p-8 bg-white/80 rounded-xl shadow-lg border border-[#d4af37] animate-fade-in">
        <div class="text-center mb-6">
            <img src="https://pbs.twimg.com/profile_images/1018602821086691328/ZB3pi7f9_400x400.jpg"
                 alt="Santo Niño Logo"
                 class="w-24 h-24 mx-auto rounded-full mb-4">
            <h2 class="text-2xl font-bold text-[#6b4226]">Create Account</h2>
            <p class="text-sm text-[#4b3b2a]">Join Santo Niño Parish Church system</p>
        </div>

        <form method="POST" action="{{ route('user.store') }}" class="space-y-4">
            @csrf

            <!-- First Name -->
            <div>
                <label for="first_name" class="block text-sm font-medium text-[#4b3b2a]">Firstname</label>
                <input type="text" id="firstname" name="firstname" required autofocus
                       class="w-full px-4 py-2 mt-1 border rounded-lg bg-white/70 focus:outline-none focus:ring-2 focus:ring-[#d4af37] focus:border-transparent"
                       value="{{ old('firstname') }}">
            </div>

            {{-- Lastname --}}
            <div>
                <label for="last_name" class="block text-sm font-medium text-[#4b3b2a]">Lastname</label>
                <input type="text" id="lastname" name="lastname" required autofocus
                       class="w-full px-4 py-2 mt-1 border rounded-lg bg-white/70 focus:outline-none focus:ring-2 focus:ring-[#d4af37] focus:border-transparent"
                       value="{{ old('lastname') }}">
            </div>

            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-[#4b3b2a]">Email Address</label>
                <input type="email" id="email" name="email" required
                       class="w-full px-4 py-2 mt-1 border rounded-lg bg-white/70 focus:outline-none focus:ring-2 focus:ring-[#d4af37] focus:border-transparent"
                       value="{{ old('email') }}">
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-[#4b3b2a]">Password</label>
                <input type="password" id="password" name="password" required
                       class="w-full px-4 py-2 mt-1 border rounded-lg bg-white/70 focus:outline-none focus:ring-2 focus:ring-[#d4af37] focus:border-transparent">
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-sm font-medium text-[#4b3b2a]">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                       class="w-full px-4 py-2 mt-1 border rounded-lg bg-white/70 focus:outline-none focus:ring-2 focus:ring-[#d4af37] focus:border-transparent">
            </div>

             <!-- Role -->
             <div>
                <label for="role" class="block text-sm font-medium text-[#4b3b2a]">Role</label>
                <select name="role" id="role" class="w-full px-4 py-2 mt-1 border rounded-lg bg-white/70 focus:outline-none focus:ring-2 focus:ring-[#d4af37] focus:border-transparent">
                    <option value="">Select Role</option>
                    <option value="admin">Admin</option>
                    <option value="staff">Staff</option>
                    <option value="member">Member</option>
                </select>
             </div>

            <!-- Register Button -->
            <button type="submit"
                    class="w-full py-3 px-4 bg-gradient-to-r from-[#d4af37] to-[#8b5e3c] text-white font-semibold rounded-lg shadow hover:shadow-xl hover:from-[#c9a137] hover:to-[#77492e] transition-all duration-300">
                Register
            </button>
        </form>

        <!-- Footer -->
        <p class="mt-6 text-center text-sm text-[#4b3b2a]">
            Already have an account?
            <a href="{{ route('login') }}" class="text-[#8b5e3c] font-semibold hover:underline">Login</a>
        </p>
    </div>
</section>

<!-- 🌀 Animation -->
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
