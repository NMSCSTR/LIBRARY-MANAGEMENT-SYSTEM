@extends('components.default')

@section('title', 'Library Management Information System')

@section('content')
<section
    class="relative flex items-center justify-center min-h-screen
               bg-gradient-to-br from-[#F2E9D8] to-[#e8dbc4] text-[#2C1A13] overflow-hidden">

    <!-- Background Image -->
    <div class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f')]
         bg-cover bg-center opacity-30"></div>

    <!-- Soft overlay -->
    <div class="absolute inset-0 bg-[#F2E9D8]/70 backdrop-blur-md"></div>

    <!-- Main content wrapper -->
    <div class="relative z-10 w-full max-w-4xl mx-auto px-6 text-center animate-fade-in space-y-10">

        <!-- Title -->
        <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold leading-tight">
            Welcome to
            <br>
            <span class="text-transparent bg-clip-text
                         bg-gradient-to-r from-[#A67C52] to-[#6C4F3D]">
                Library Management Information System
            </span>
        </h1>

        <!-- Quote box -->
        <blockquote
            class="text-lg sm:text-xl font-medium leading-relaxed inline-block
                       bg-white/70 backdrop-blur-md px-6 py-6 rounded-xl shadow-sm
                       border-t-4 border-[#A67C52]">
            “A library is the heartbeat of every learning community.”
            <span class="block mt-2 text-sm text-[#2C1A13]/70">— LMIS</span>
        </blockquote>

        <!-- Buttons -->
        <div class="flex flex-col sm:flex-row justify-center items-center gap-4 pt-4">

            <!-- Login button -->
            <a href="{{ route('users.login') }}"
                class="inline-flex items-center px-7 py-3.5 text-lg font-semibold text-white
                       bg-gradient-to-r from-[#6C4F3D] to-[#A67C52]
                       rounded-xl shadow-md hover:shadow-lg
                       hover:from-[#5d4436] hover:to-[#936a47]
                       transform hover:scale-[1.05] transition-all duration-300">
                Login to LMIS
                <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd"
                        d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                        clip-rule="evenodd" />
                </svg>
            </a>

            <!-- Register button -->
            <a href="{{ route('register.show') }}"
                class="inline-flex items-center px-7 py-3.5 text-lg font-semibold
                       bg-white text-[#2C1A13] border border-[#A67C52]
                       rounded-xl shadow hover:bg-[#F2E9D8] hover:shadow-md
                       hover:border-[#6C4F3D]
                       transform hover:scale-[1.05] transition-all duration-300">
                Create Account
            </a>

        </div>

    </div>
</section>

<!-- Animations -->
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(14px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn .6s ease forwards; }
</style>

@endsection

@section('scripts')
<script>
    AOS.init({ once: true, duration: 1000, easing: 'ease' });
</script>
@endsection
