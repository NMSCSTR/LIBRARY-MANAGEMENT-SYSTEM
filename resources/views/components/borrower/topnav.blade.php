<div class="fixed top-0 left-0 right-0 bg-white text-blue-800 px-4 lg:px-10 py-4 z-10 shadow-lg">
    <div class="flex items-center justify-between">

        <!-- Logo + Title -->
        <div class="flex items-center space-x-3">
            <img src="https://clipground.com/images/book-logo-png-14.png" alt="Santo Niño Parish Logo"
                class="h-10 w-10 object-contain" />
            <div class="font-bold text-blue-900 text-xl leading-tight">
                LIBRARY MANAGEMENT INFORMATION SYSTEM
            </div>
        </div>

        <!-- Profile & Logout -->
        <div class="flex items-center space-x-4">

            <!-- Profile Dropdown -->
            <div class="relative">
                <button id="profileBtn" class="flex items-center space-x-2 focus:outline-none">
                    <img src="{{ auth()->user()->avatar ?? 'https://ui-avatars.com/api/?name='.auth()->user()->name }}"
                        alt="Profile" class="h-8 w-8 rounded-full object-cover">
                    <span class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</span>
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>

                <!-- Dropdown Menu -->
                <div id="profileMenu"
                    class="hidden absolute right-0 mt-2 w-48 bg-white border border-gray-200 rounded-lg shadow-lg py-1 z-20">
                    <a href="{{ route('borrower.profile') }}"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profile</a>
                    <form method="POST" action="{{ route('users.logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Logout</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // Toggle profile dropdown
    const profileBtn = document.getElementById('profileBtn');
    const profileMenu = document.getElementById('profileMenu');

    profileBtn.addEventListener('click', () => {
        profileMenu.classList.toggle('hidden');
    });

    // Close dropdown when clicking outside
    window.addEventListener('click', function(e) {
        if (!profileBtn.contains(e.target) && !profileMenu.contains(e.target)) {
            profileMenu.classList.add('hidden');
        }
    });
</script>
