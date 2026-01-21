@extends('components.default')

@section('title', 'Library Hub | LMIS')

@section('content')
<section class="bg-[#fcfcfd]">
    <div class="min-h-screen pt-24">
        @include('components.admin.topnav')

        <div class="flex flex-col lg:flex-row px-4 lg:px-10 pb-6 gap-6">

            {{-- Sidebar --}}
            <div class="lg:w-2/12 w-full">
                @include('components.admin.sidebar')
            </div>

            {{-- Main Content --}}
            <div class="lg:w-10/12 w-full">

                {{-- 1. HEADER STATS (New Enhancement) --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
                    <div class="bg-white p-5 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col justify-center">
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Total Titles</span>
                        <span class="text-2xl font-black text-gray-800">{{ $books->count() }}</span>
                    </div>
                    <div class="bg-white p-5 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col justify-center">
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Individual Copies</span>
                        <span class="text-2xl font-black text-blue-600">{{ $books->sum(fn($b) => $b->copies->count()) }}</span>
                    </div>
                    <div class="bg-white p-5 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col justify-center">
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400">Authors</span>
                        <span class="text-2xl font-black text-gray-800">{{ $authors->count() }}</span>
                    </div>
                    <div class="bg-blue-600 p-5 rounded-[2rem] shadow-lg shadow-blue-100 flex flex-col justify-center">
                        <span class="text-[10px] font-black uppercase tracking-widest text-blue-100">Active Publishers</span>
                        <span class="text-2xl font-black text-white">{{ $publishers->count() }}</span>
                    </div>
                </div>

                {{-- 2. ENHANCED SEARCH BAR --}}
                <div class="mb-8 relative group">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" id="hubSearch" placeholder="Type to filter results instantly..."
                        class="block w-full pl-14 pr-4 py-5 border-none rounded-3xl bg-white shadow-xl shadow-gray-100/50 focus:ring-4 focus:ring-blue-100 transition-all text-gray-700 font-medium">
                </div>

                <div class="bg-white rounded-[2.5rem] shadow-2xl shadow-gray-200/40 overflow-hidden border border-gray-50">

                    {{-- 3. MODERN TAB NAVIGATION --}}
                    <div class="border-b border-gray-50 bg-white/50 backdrop-blur-md sticky top-0 z-10">
                        <ul class="flex flex-wrap text-xs font-black uppercase tracking-tighter p-3 gap-2" id="hubTab">
                            @php
                                $tabs = [
                                    ['id' => 'books-content', 'label' => 'Inventory', 'active' => true],
                                    ['id' => 'copies-content', 'label' => 'Individual Copies', 'active' => false],
                                    ['id' => 'authors-content', 'label' => 'Authors', 'active' => false],
                                    ['id' => 'categories-content', 'label' => 'Categories', 'active' => false],
                                    ['id' => 'publishers-content', 'label' => 'Publishers', 'active' => false],
                                    ['id' => 'suppliers-content', 'label' => 'Suppliers', 'active' => false],
                                ];
                            @endphp
                            @foreach($tabs as $tab)
                            <li class="flex-1">
                                <button class="w-full py-4 px-2 rounded-2xl transition-all duration-300 {{ $tab['active'] ? 'bg-gray-900 text-white shadow-xl' : 'text-gray-400 hover:bg-gray-100' }}"
                                    data-target="#{{ $tab['id'] }}">
                                    {{ $tab['label'] }}
                                </button>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- 4. TAB CONTENTS --}}
                    <div id="hubTabContent" class="p-4 md:p-8">

                        {{-- BOOKS INVENTORY --}}
                        <div class="tab-pane animate-in fade-in slide-in-from-bottom-4 duration-500" id="books-content">
                            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                                <h2 class="text-3xl font-black text-gray-900">Books</h2>
                                <button data-modal-target="defaultModal" data-modal-toggle="defaultModal"
                                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-8 py-4 rounded-2xl shadow-xl shadow-blue-200 transition-all active:scale-95 flex items-center gap-3">
                                    <span class="text-xl">+</span> Add New Entry
                                </button>
                            </div>

                            <div class="grid grid-cols-1 gap-4">
                                @foreach($books as $book)
                                <div class="group bg-white border border-gray-100 p-6 rounded-[2rem] hover:shadow-xl hover:shadow-gray-100 transition-all search-item">
                                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                                        <div class="flex-1">
                                            <h3 class="text-lg font-black text-gray-800 search-text leading-tight mb-1">{{ $book->title }}</h3>
                                            <div class="flex flex-wrap gap-2 items-center">
                                                <span class="text-xs font-bold text-gray-400 search-text">{{ $book->author?->name ?? 'Unknown Author' }}</span>
                                                <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                                <span class="text-[10px] font-black uppercase text-blue-500 tracking-tighter">{{ $book->category?->name ?? 'General' }}</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-6 w-full md:w-auto justify-between md:justify-end border-t md:border-none pt-4 md:pt-0">
                                            <div class="text-center">
                                                <span class="block text-[10px] font-black text-gray-300 uppercase">Stock</span>
                                                <span class="font-black text-gray-800">{{ $book->copies->count() }}</span>
                                            </div>
                                            <div class="flex gap-2">
                                                <a href="{{ route('books.edit', $book->id) }}" class="p-3 bg-gray-50 text-gray-400 hover:bg-blue-50 hover:text-blue-600 rounded-2xl transition-all">Edit</a>
                                                <button data-id="{{ $book->id }}" class="delete-book-btn p-3 bg-gray-50 text-gray-400 hover:bg-red-50 hover:text-red-500 rounded-2xl transition-all font-bold text-sm">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                    <form id="delete-book-form-{{ $book->id }}" action="{{ route('books.destroy', $book->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- INDIVIDUAL COPIES --}}
                        <div class="hidden tab-pane animate-in fade-in slide-in-from-bottom-4 duration-500" id="copies-content">
                            <div class="flex justify-between items-center mb-8">
                                <h2 class="text-3xl font-black text-gray-900">Copies</h2>
                                <a href="{{ route('book-copies.index') }}" class="text-xs font-black text-blue-600 uppercase tracking-widest hover:underline">Manage All &rarr;</a>
                            </div>
                            <div class="space-y-3">
                                @foreach($books->take(30) as $book)
                                    @foreach($book->copies as $copy)
                                    <div class="p-5 bg-gray-50/50 border border-gray-100 rounded-3xl flex flex-wrap items-center justify-between gap-4 search-item">
                                        <div class="flex items-center gap-4">
                                            <span class="w-12 h-12 flex items-center justify-center bg-white rounded-2xl shadow-sm font-black text-xs text-blue-600 search-text">#{{ $copy->copy_number }}</span>
                                            <span class="font-bold text-gray-800 search-text">{{ $book->title }}</span>
                                        </div>
                                        <div class="flex items-center gap-4">
                                            <span class="text-xs font-bold text-gray-400 search-text">{{ $copy->shelf_location }}</span>
                                            <span class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase tracking-widest {{ $copy->status == 'available' ? 'bg-green-500 text-white' : 'bg-orange-400 text-white' }} search-text">
                                                {{ $copy->status }}
                                            </span>
                                        </div>
                                    </div>
                                    @endforeach
                                @endforeach
                            </div>
                        </div>

                        {{-- AUTHORS (Grid Cards) --}}
                        <div class="hidden tab-pane animate-in fade-in slide-in-from-bottom-4 duration-500" id="authors-content">
                             <div class="flex justify-between items-center mb-8">
                                <h2 class="text-3xl font-black text-gray-900">Authors</h2>
                                <a href="{{ route('authors.index') }}" class="bg-gray-900 text-white px-6 py-3 rounded-2xl text-xs font-bold">Manage List</a>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($authors as $author)
                                <div class="bg-white border border-gray-100 p-6 rounded-[2rem] text-center hover:shadow-xl transition-all search-item group">
                                    <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-4 font-black text-xl text-gray-300 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                        {{ substr($author->name, 0, 1) }}
                                    </div>
                                    <h4 class="font-black text-gray-800 search-text">{{ $author->name }}</h4>
                                    <a href="{{ route('authors.edit', $author->id) }}" class="text-[10px] font-black text-blue-500 uppercase tracking-widest mt-2 inline-block opacity-0 group-hover:opacity-100 transition-all">Edit Details</a>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- SUPPLIERS --}}
                        <div class="hidden tab-pane animate-in fade-in slide-in-from-bottom-4 duration-500" id="suppliers-content">
                            <div class="flex justify-between items-center mb-8">
                                <h2 class="text-3xl font-black text-gray-900">Suppliers</h2>
                                <a href="{{ route('suppliers.index') }}" class="text-xs font-black text-blue-600">Full Directory &rarr;</a>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @foreach($suppliers as $supplier)
                                <div class="p-8 bg-gray-900 rounded-[2.5rem] text-white flex justify-between items-center search-item">
                                    <div>
                                        <h4 class="text-xl font-black search-text leading-none">{{ $supplier->name }}</h4>
                                        <p class="text-blue-400 text-xs font-bold mt-2 search-text">{{ $supplier->contact_person ?? 'Global Provider' }}</p>
                                    </div>
                                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="w-12 h-12 bg-white/10 hover:bg-white/20 rounded-2xl flex items-center justify-center transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Publishers & Categories (Simplified for Hub) --}}
                        <div class="hidden tab-pane animate-in fade-in slide-in-from-bottom-4 duration-500" id="categories-content">
                            {{-- Header with Manage Button --}}
                            <div class="flex justify-between items-center mb-8">
                                <h2 class="text-3xl font-black text-gray-900">Categories</h2>
                                <a href="{{ route('categories.index') }}"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-black uppercase tracking-widest px-6 py-3 rounded-2xl transition-all active:scale-95">
                                Manage All Categories &rarr;
                                </a>
                            </div>

                            {{-- Categories Grid --}}
                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                                @foreach($categories as $category)
                                    <div class="p-4 bg-white border border-gray-100 rounded-2xl text-center shadow-sm search-item hover:border-blue-500 transition-colors group">
                                        <span class="font-black text-[10px] uppercase text-gray-400 group-hover:text-blue-600 search-text tracking-widest transition-colors">
                                            {{ $category->name }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="hidden tab-pane animate-in fade-in slide-in-from-bottom-4 duration-500" id="publishers-content">
                            {{-- Header with Manage Button --}}
                            <div class="flex justify-between items-center mb-8">
                                <h2 class="text-3xl font-black text-gray-900">Publishers</h2>
                                <a href="{{ route('publishers.index') }}"
                                class="bg-gray-100 hover:bg-gray-200 text-gray-600 text-xs font-black uppercase tracking-widest px-6 py-3 rounded-2xl transition-all active:scale-95">
                                Manage All Publishers &rarr;
                                </a>
                            </div>

                            {{-- Publishers Grid --}}
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach($publishers as $pub)
                                <div class="p-6 bg-white border border-gray-100 rounded-3xl flex justify-between items-center search-item hover:shadow-md transition-shadow">
                                    <div class="flex items-center gap-4">
                                        {{-- Decorative Initial Circle --}}
                                        <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center font-black text-sm">
                                            {{ substr($pub->name, 0, 1) }}
                                        </div>
                                        <span class="font-black text-gray-800 search-text">{{ $pub->name }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL (PREMIUM DESIGN) --}}
    <div id="defaultModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/40 backdrop-blur-md">
        <div class="w-full max-w-xl p-4 animate-in zoom-in duration-300">
            <div class="bg-white rounded-[3rem] shadow-2xl p-10 border border-gray-100">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-3xl font-black text-gray-900 tracking-tighter">New Registration</h3>
                    <button data-modal-toggle="defaultModal" class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-900 transition-colors">✕</button>
                </div>
                <form action="{{ route('books.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-4">
                        <input type="text" name="title" class="w-full border-none bg-gray-100/50 rounded-2xl p-5 focus:ring-2 focus:ring-blue-500 font-bold" placeholder="Entry Title" required>
                        <div class="grid grid-cols-2 gap-4">
                            <input type="text" name="isbn" class="border-none bg-gray-100/50 rounded-2xl p-5 focus:ring-2 focus:ring-blue-500 font-bold" placeholder="ISBN (Optional)">
                            <input type="number" name="copies_available" value="1" min="1" class="border-none bg-gray-100/50 rounded-2xl p-5 focus:ring-2 focus:ring-blue-500 font-bold" required>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <select name="author_id" class="border-none bg-gray-100/50 rounded-2xl p-5 font-bold" required>
                                <option value="">Author</option>
                                @foreach($authors as $author) <option value="{{ $author->id }}">{{ $author->name }}</option> @endforeach
                            </select>
                            <select name="category_id" class="border-none bg-gray-100/50 rounded-2xl p-5 font-bold" required>
                                <option value="">Category</option>
                                @foreach($categories as $category) <option value="{{ $category->id }}">{{ $category->name }}</option> @endforeach
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <select name="publisher_id" class="border-none bg-gray-100/50 rounded-2xl p-5 font-bold" required>
                                <option value="">Publisher</option>
                                @foreach($publishers as $pub) <option value="{{ $pub->id }}">{{ $pub->name }}</option> @endforeach
                            </select>
                            <select name="supplier_id" class="border-none bg-gray-100/50 rounded-2xl p-5 font-bold" required>
                                <option value="">Supplier</option>
                                @foreach($suppliers as $sup) <option value="{{ $sup->id }}">{{ $sup->name }}</option> @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white font-black py-5 rounded-[2rem] shadow-2xl shadow-gray-200 transition-all active:scale-95">Register Book</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
@include('components.alerts')
<script>
    // Premium Tab Switching with Animations
    document.querySelectorAll('[data-target]').forEach(tab => {
        tab.addEventListener('click', function() {
            // UI Update
            document.querySelectorAll('[data-target]').forEach(t => {
                t.classList.remove('bg-gray-900', 'text-white', 'shadow-xl');
                t.classList.add('text-gray-400');
            });
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.add('hidden'));

            const target = document.querySelector(this.dataset.target);
            target.classList.remove('hidden');
            this.classList.add('bg-gray-900', 'text-white', 'shadow-xl');
            this.classList.remove('text-gray-400');

            // Reset Search
            document.getElementById('hubSearch').value = '';
            filterItems('');
        });
    });

    // Instant Filter Logic
    const searchInput = document.getElementById('hubSearch');
    searchInput.addEventListener('input', function() {
        filterItems(this.value.toLowerCase());
    });

    function filterItems(query) {
        const activePane = document.querySelector('.tab-pane:not(.hidden)');
        const items = activePane.querySelectorAll('.search-item');
        items.forEach(item => {
            const textElements = item.querySelectorAll('.search-text');
            let match = false;
            textElements.forEach(el => {
                if (el.textContent.toLowerCase().includes(query)) match = true;
            });
            item.style.display = match ? '' : 'none';
        });
    }

    // Delete Confirmation
    document.querySelectorAll('.delete-book-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            Swal.fire({
                title: 'Proceed with Caution',
                text: "Deleting this title will permanently remove all physical copies from the system.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#000',
                confirmButtonText: 'Yes, Delete'
            }).then(res => {
                if (res.isConfirmed) document.getElementById(`delete-book-form-${id}`).submit();
            });
        });
    });
</script>
@endpush
