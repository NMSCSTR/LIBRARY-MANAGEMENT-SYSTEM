@extends('components.default')

@section('title', 'Library Management Information System')

@section('content')
<section
    class="relative flex items-center justify-center min-h-screen bg-gradient-to-br from-[#f0efe9] to-[#e4dcc7] text-[#2c1c0f] overflow-hidden">

    <!-- 🪟 Background Image (Library / Books) -->
    <div
        class="absolute inset-0 bg-[url('https://images.unsplash.com/photo-1524995997946-a1c2e315a42f')] bg-cover bg-center opacity-60 z-0">
    </div>

    <!-- ✨ Soft Veil -->
    <div class="absolute inset-0 bg-white/40 backdrop-blur-[6px] z-1"></div>

    <!-- 💬 Main Layout -->
    <div class="relative z-10 grid w-full max-w-7xl px-6 py-16 mx-auto gap-12 lg:grid-cols-12 items-center">

        <!-- LEFT SIDE TEXT -->
        <div class="lg:col-span-7 flex flex-col justify-center space-y-8 text-left animate-fade-in">

            <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight leading-tight max-w-2xl text-[#3d2b1a]">
                Welcome to<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#c49a6c] to-[#8b6a4a] drop-shadow-lg">
                    Library Management Information System
                </span>
            </h1>

            <blockquote
                class="text-center text-lg sm:text-xl font-semibold italic text-[#5e4a36] leading-relaxed px-4 py-6 bg-white/60 backdrop-blur-md rounded-xl shadow-inner border-l-4 border-[#c49a6c]">
                "A library is the heart of every educational institution."
                <span class="block mt-2 text-sm font-normal text-[#6b5a40]">— LMIS</span>
            </blockquote>

            <div class="flex flex-wrap gap-4" data-aos="fade-up" data-aos-delay="400">

                <a href="{{ route('login') }}"
                    class="inline-flex items-center justify-center px-6 py-3 text-base font-semibold bg-gradient-to-r from-[#c49a6c] to-[#8b6a4a] text-white rounded-xl shadow-lg hover:from-[#b48c5f] hover:to-[#735739] hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                    Login to LMIS
                    <svg class="w-5 h-5 ml-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd"
                            d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z"
                            clip-rule="evenodd" />
                    </svg>
                </a>

                <a href="{{ route('register') }}"
                    class="inline-flex items-center justify-center px-6 py-3 text-base font-semibold text-[#5a4633] bg-white border border-[#c49a6c] rounded-xl shadow hover:bg-[#f8f3eb] hover:shadow-lg transform hover:scale-105 transition-all duration-300">
                    Create Account
                </a>

            </div>
        </div>

        <!-- RIGHT SIDE LOGO -->
        <div class="lg:col-span-5 flex justify-center items-center animate-fade-in delay-500" data-aos="zoom-in">
            <div
                class="p-5 sm:p-6 bg-white/70 backdrop-blur-lg rounded-3xl shadow-xl border border-[#c49a6c] w-full max-w-xs sm:max-w-sm transition-transform duration-300 hover:scale-105">

                <!-- Replace with your LMIS Logo -->
                <img src="https://cdn-icons-png.flaticon.com/512/2991/2991148.png"
                    alt="LMIS Logo" class="w-full object-contain rounded-2xl shadow-inner">
            </div>
        </div>
    </div>
</section>




</section>

<!-- Animations & Styles -->
<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in { animation: fadeIn 0.4s ease forwards; }
</style>

@endsection

@section('scripts')
{{-- SAME JAVASCRIPT (unchanged) --}}
<link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.css' rel='stylesheet' />
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>

<script>
    AOS.init({ once: true, duration: 1000, easing: 'ease-in-out' });
</script>

<script>
document.addEventListener("DOMContentLoaded", () => {
    const scrollUpBtn = document.getElementById("scrollUpBtn");
    const scrollDownBtn = document.getElementById("scrollDownBtn");

    window.addEventListener("scroll", () => {
        if (window.scrollY > 300) {
            scrollUpBtn.classList.remove("hidden");
            scrollDownBtn.classList.add("hidden");
        } else {
            scrollUpBtn.classList.add("hidden");
            scrollDownBtn.classList.remove("hidden");
        }
    });

    scrollUpBtn.addEventListener("click", () => {
        window.scrollTo({ top: 0, behavior: "smooth" });
    });

    scrollDownBtn.addEventListener("click", (e) => {
        e.preventDefault();
        document.querySelector("#events").scrollIntoView({ behavior: "smooth" });
    });
});
</script>

@endsection
