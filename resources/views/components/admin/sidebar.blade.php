<div class="bg-white rounded-xl shadow-lg mb-6 px-6 py-4" data-aos="slide-right">

    <!-- Dashboard -->
    <a href="{{ route('admin.dashboard') }}"
        class="flex items-center justify-between my-4 {{ request()->routeIs('admin.dashboard') ? 'text-blue-600 font-bold' : 'text-gray-600 hover:text-black' }}">
        <span class="flex items-center">
            <span class="material-icons-outlined pr-2">dashboard</span>
            Dashboard
        </span>
        <span class="material-icons-outlined">keyboard_arrow_right</span>
    </a>

    <!-- Cataloging Section -->
    <p class="text-xs font-semibold text-gray-400 mt-6 mb-2 uppercase">Cataloging</p>

    <a href="{{ route('authors.index') }}"
        class="flex items-center justify-between my-4 {{ request()->routeIs('authors.*') ? 'text-blue-600 font-bold' : 'text-gray-600 hover:text-black' }}">
        <span class="flex items-center">
            <span class="material-icons-outlined pr-2">person</span>
            Authors
        </span>
        <span class="material-icons-outlined">keyboard_arrow_right</span>
    </a>

    <a href="{{ route('publishers.index') }}"
        class="flex items-center justify-between my-4 {{ request()->routeIs('publishers.*') ? 'text-blue-600 font-bold' : 'text-gray-600 hover:text-black' }}">
        <span class="flex items-center">
            <span class="material-icons-outlined pr-2">apartment</span>
            Publishers
        </span>
        <span class="material-icons-outlined">keyboard_arrow_right</span>
    </a>

    <a href="{{ route('categories.index') }}"
        class="flex items-center justify-between my-4 {{ request()->routeIs('categories.*') ? 'text-blue-600 font-bold' : 'text-gray-600 hover:text-black' }}">
        <span class="flex items-center">
            <span class="material-icons-outlined pr-2">category</span>
            Categories
        </span>
        <span class="material-icons-outlined">keyboard_arrow_right</span>
    </a>

    <a href="{{ route('books.index') }}"
        class="flex items-center justify-between my-4 {{ request()->routeIs('books.*') ? 'text-blue-600 font-bold' : 'text-gray-600 hover:text-black' }}">
        <span class="flex items-center">
            <span class="material-icons-outlined pr-2">menu_book</span>
            Books
        </span>
        <span class="material-icons-outlined">keyboard_arrow_right</span>
    </a>

    <a href="{{ route('book-copies.index') }}"
        class="flex items-center justify-between my-4 {{ request()->routeIs('book-copies.*') ? 'text-blue-600 font-bold' : 'text-gray-600 hover:text-black' }}">
        <span class="flex items-center">
            <span class="material-icons-outlined pr-2">library_books</span>
            Book Copies
        </span>
        <span class="material-icons-outlined">keyboard_arrow_right</span>
    </a>

    <!-- Circulation -->
    <p class="text-xs font-semibold text-gray-400 mt-6 mb-2 uppercase">Circulation</p>

    <a href="{{ route('borrows.index') }}"
        class="flex items-center justify-between my-4 {{ request()->routeIs('borrows.*') ? 'text-blue-600 font-bold' : 'text-gray-600 hover:text-black' }}">
        <span class="flex items-center">
            <span class="material-icons-outlined pr-2">assignment_return</span>
            Borrows
        </span>
        <span class="material-icons-outlined">keyboard_arrow_right</span>
    </a>

    <a href="{{ route('reservations.index') }}"
        class="flex items-center justify-between my-4 {{ request()->routeIs('reservations.*') ? 'text-blue-600 font-bold' : 'text-gray-600 hover:text-black' }}">
        <span class="flex items-center">
            <span class="material-icons-outlined pr-2">event_available</span>
            Reservations
        </span>
        <span class="material-icons-outlined">keyboard_arrow_right</span>
    </a>

    <!-- Acquisitions -->
    <p class="text-xs font-semibold text-gray-400 mt-6 mb-2 uppercase">Acquisitions</p>

    <a href="{{ route('suppliers.index') }}"
        class="flex items-center justify-between my-4 {{ request()->routeIs('suppliers.*') ? 'text-blue-600 font-bold' : 'text-gray-600 hover:text-black' }}">
        <span class="flex items-center">
            <span class="material-icons-outlined pr-2">local_shipping</span>
            Suppliers
        </span>
        <span class="material-icons-outlined">keyboard_arrow_right</span>
    </a>

    <a href="{{ route('donations.index') }}"
        class="flex items-center justify-between my-4 {{ request()->routeIs('donations.*') ? 'text-blue-600 font-bold' : 'text-gray-600 hover:text-black' }}">
        <span class="flex items-center">
            <span class="material-icons-outlined pr-2">volunteer_activism</span>
            Donations
        </span>
        <span class="material-icons-outlined">keyboard_arrow_right</span>
    </a>

    <!-- Administration -->
    <p class="text-xs font-semibold text-gray-400 mt-6 mb-2 uppercase">Administration</p>

    <a href="{{ route('users.index') }}"
        class="flex items-center justify-between my-4 {{ request()->routeIs('users.*') ? 'text-blue-600 font-bold' : 'text-gray-600 hover:text-black' }}">
        <span class="flex items-center">
            <span class="material-icons-outlined pr-2">supervisor_account</span>
            Users
        </span>
        <span class="material-icons-outlined">keyboard_arrow_right</span>
    </a>

    <a href="{{ route('roles.index') }}"
        class="flex items-center justify-between my-4 {{ request()->routeIs('roles.*') ? 'text-blue-600 font-bold' : 'text-gray-600 hover:text-black' }}">
        <span class="flex items-center">
            <span class="material-icons-outlined pr-2">badge</span>
            Roles
        </span>
        <span class="material-icons-outlined">keyboard_arrow_right</span>
    </a>

    <a href="{{ route('activity-logs.index') }}"
        class="flex items-center justify-between my-4 {{ request()->routeIs('activity-logs.*') ? 'text-blue-600 font-bold' : 'text-gray-600 hover:text-black' }}">
        <span class="flex items-center">
            <span class="material-icons-outlined pr-2">history</span>
            Activity Log
        </span>
        <span class="material-icons-outlined">keyboard_arrow_right</span>
    </a>

</div>
