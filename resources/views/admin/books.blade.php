@extends('components.default')

@section('title', 'Library Management Hub | LMIS')

@section('content')
<section>
    <div class="min-h-screen pt-24 bg-gray-50/50">
        @include('components.admin.topnav')

        <div class="flex flex-col lg:flex-row px-4 lg:px-10 pb-6 gap-6">

            {{-- Sidebar --}}
            <div class="lg:w-2/12 w-full">
                @include('components.admin.sidebar')
            </div>

            {{-- Main Content --}}
            <div class="lg:w-10/12 w-full">

                {{-- Unified Search Hub --}}
                <div class="mb-6 relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" id="hubSearch" placeholder="Search across current tab..."
                        class="block w-full pl-12 pr-4 py-4 border-none rounded-2xl bg-white shadow-sm ring-1 ring-gray-200 focus:ring-2 focus:ring-blue-500 transition-all text-gray-700">
                </div>

                <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 overflow-hidden border border-gray-100">

                    {{-- Navigation Tabs --}}
                    <div class="border-b border-gray-100 bg-white">
                        <ul class="flex flex-wrap text-sm font-bold text-center p-2" id="hubTab" role="tablist">
                            @php
                                $tabs = [
                                    ['id' => 'books-content', 'label' => 'Books Inventory', 'active' => true],
                                    ['id' => 'copies-content', 'label' => 'Individual Copies', 'active' => false],
                                    ['id' => 'authors-content', 'label' => 'Authors', 'active' => false],
                                    ['id' => 'categories-content', 'label' => 'Categories', 'active' => false],
                                    ['id' => 'publishers-content', 'label' => 'Publishers', 'active' => false],
                                    ['id' => 'suppliers-content', 'label' => 'Suppliers', 'active' => false],
                                ];
                            @endphp
                            @foreach($tabs as $tab)
                            <li class="flex-1">
                                <button class="w-full py-3 px-4 rounded-xl transition-all duration-200 {{ $tab['active'] ? 'active-tab bg-blue-50 text-blue-600' : 'text-gray-400 hover:bg-gray-50 hover:text-gray-600' }}"
                                    data-target="#{{ $tab['id'] }}" type="button">
                                    {{ $tab['label'] }}
                                </button>
                            </li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Tab Contents --}}
                    <div id="hubTabContent">

                        {{-- 1. BOOKS INVENTORY --}}
                        <div class="p-8 tab-pane" id="books-content">
                            <div class="flex justify-between items-center mb-8">
                                <h2 class="text-2xl font-extrabold text-gray-800">Books Inventory</h2>
                                <button data-modal-target="defaultModal" data-modal-toggle="defaultModal"
                                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-bold px-6 py-3 rounded-xl shadow-lg shadow-blue-200 transition flex items-center gap-2 active:scale-95">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Add New Book
                                </button>
                            </div>
                            <div class="overflow-x-auto rounded-2xl border border-gray-50">
                                <table class="w-full text-sm text-left text-gray-600 search-table">
                                    <thead class="text-xs uppercase bg-gray-50/50 text-gray-400">
                                        <tr>
                                            <th class="py-4 px-6">Book Title</th>
                                            <th class="py-4 px-6 text-center">Copies</th>
                                            <th class="py-4 px-6">Primary Author</th>
                                            <th class="py-4 px-6 text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @foreach($books as $book)
                                        <tr class="hover:bg-blue-50/20 transition search-item group">
                                            <td class="py-4 px-6 font-bold text-gray-900 search-text">{{ $book->title }}</td>
                                            <td class="py-4 px-6 text-center">
                                                <span class="bg-white border border-gray-100 text-gray-800 px-3 py-1 rounded-full font-bold text-xs shadow-sm">{{ $book->copies->count() }}</span>
                                            </td>
                                            <td class="py-4 px-6 search-text italic">{{ $book->author?->name ?? 'Unassigned' }}</td>
                                            <td class="py-4 px-6 text-right">
                                                <div class="flex justify-end gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <a href="{{ route('books.edit', $book->id) }}" class="text-blue-600 font-bold hover:text-blue-800">Edit</a>
                                                    <button data-id="{{ $book->id }}" class="delete-book-btn text-red-500 font-bold hover:text-red-700">Delete</button>
                                                </div>
                                                <form id="delete-book-form-{{ $book->id }}" action="{{ route('books.destroy', $book->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- 2. INDIVIDUAL COPIES --}}
                        <div class="p-8 hidden tab-pane" id="copies-content">
                            <div class="flex justify-between items-center mb-8">
                                <h2 class="text-2xl font-extrabold text-gray-800">Physical Copies</h2>
                                <a href="{{ route('book-copies.index') }}" class="bg-gray-100 text-gray-600 px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-gray-200 transition">Manage All Copies</a>
                            </div>
                            <div class="overflow-x-auto rounded-2xl border border-gray-50">
                                <table class="w-full text-sm text-left text-gray-600 search-table">
                                    <thead class="text-xs uppercase bg-gray-50/50 text-gray-400">
                                        <tr>
                                            <th class="py-4 px-6">Call Number</th>
                                            <th class="py-4 px-6">Book Title</th>
                                            <th class="py-4 px-6">Location</th>
                                            <th class="py-4 px-6">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @foreach($books->take(50) as $book)
                                            @foreach($book->copies as $copy)
                                            <tr class="hover:bg-gray-50 transition search-item">
                                                <td class="py-4 px-6 font-mono text-xs font-bold text-blue-600 search-text">#{{ $copy->copy_number }}</td>
                                                <td class="py-4 px-6 text-gray-800 search-text">{{ $book->title }}</td>
                                                <td class="py-4 px-6 search-text font-medium text-gray-500">{{ $copy->shelf_location }}</td>
                                                <td class="py-4 px-6">
                                                    @php $statusClass = $copy->status == 'available' ? 'bg-green-100 text-green-700' : 'bg-orange-100 text-orange-700'; @endphp
                                                    <span class="px-3 py-1 rounded-full text-[10px] uppercase font-black {{ $statusClass }} search-text">
                                                        {{ $copy->status }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- 3. AUTHORS --}}
                        <div class="p-8 hidden tab-pane" id="authors-content">
                            <div class="flex justify-between items-center mb-8">
                                <h2 class="text-2xl font-extrabold text-gray-800">Authors</h2>
                                <a href="{{ route('authors.index') }}" class="bg-gray-100 text-gray-600 px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-gray-200 transition">Manage Authors List</a>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                                @foreach($authors as $author)
                                <div class="p-6 bg-gray-50 rounded-3xl border border-transparent hover:border-blue-200 hover:bg-white transition-all search-item group">
                                    <div class="flex items-center justify-between">
                                        <span class="font-bold text-gray-800 search-text">{{ $author->name }}</span>
                                        <a href="{{ route('authors.edit', $author->id) }}" class="p-2 bg-white text-gray-400 hover:text-blue-600 rounded-xl shadow-sm opacity-0 group-hover:opacity-100 transition-all">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- 4. CATEGORIES --}}
                        <div class="p-8 hidden tab-pane" id="categories-content">
                            <div class="flex justify-between items-center mb-8">
                                <h2 class="text-2xl font-extrabold text-gray-800">Genre Categories</h2>
                                <a href="{{ route('categories.index') }}" class="bg-gray-100 text-gray-600 px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-gray-200 transition">Edit Categories</a>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                                @foreach($categories as $category)
                                <div class="p-5 bg-white border border-gray-100 rounded-2xl text-center shadow-sm search-item">
                                    <span class="font-black text-gray-600 text-xs uppercase tracking-tighter search-text">{{ $category->name }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- 5. PUBLISHERS --}}
                        <div class="p-8 hidden tab-pane" id="publishers-content">
                            <div class="flex justify-between items-center mb-8">
                                <h2 class="text-2xl font-extrabold text-gray-800">Publishers</h2>
                                <a href="{{ route('publishers.index') }}" class="bg-gray-100 text-gray-600 px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-gray-200 transition">Manage All</a>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @foreach($publishers as $pub)
                                <div class="p-6 bg-white border border-gray-100 rounded-3xl shadow-sm flex items-center justify-between search-item group hover:shadow-lg transition-all">
                                    <div>
                                        <span class="font-extrabold text-gray-900 search-text block">{{ $pub->name }}</span>
                                        <span class="text-[10px] text-gray-400 font-bold uppercase">{{ $pub->email ?? 'No Email' }}</span>
                                    </div>
                                    <a href="{{ route('publishers.edit', $pub->id) }}" class="p-3 bg-blue-50 text-blue-600 rounded-2xl opacity-0 group-hover:opacity-100 transition-all">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- 6. SUPPLIERS --}}
                        <div class="p-8 hidden tab-pane" id="suppliers-content">
                            <div class="flex justify-between items-center mb-8">
                                <h2 class="text-2xl font-extrabold text-gray-800">Suppliers</h2>
                                <a href="{{ route('suppliers.index') }}" class="bg-blue-600 text-white px-5 py-2.5 rounded-xl text-xs font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-100">
                                    Manage All Suppliers
                                </a>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                                @forelse($suppliers as $supplier)
                                <div class="p-6 bg-gray-50 border border-transparent hover:border-blue-100 hover:bg-white rounded-3xl transition-all flex justify-between items-center search-item group">
                                    <div>
                                        <h4 class="font-black text-gray-900 search-text">{{ $supplier->name }}</h4>
                                        <p class="text-[10px] text-gray-400 font-bold uppercase search-text">{{ $supplier->contact_person ?? 'No Representative' }}</p>
                                    </div>
                                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="p-3 bg-white text-gray-300 hover:text-blue-600 rounded-2xl shadow-sm transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                </div>
                                @empty
                                <div class="col-span-full py-10 text-center text-gray-300 font-bold">No suppliers registered.</div>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal (Retained logic, improved UI) --}}
    <div id="defaultModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm">
        <div class="w-full max-w-lg p-4">
            <div class="bg-white rounded-[2rem] shadow-2xl p-8 border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-black text-gray-800">Add New Entry</h3>
                    <button data-modal-toggle="defaultModal" class="text-gray-400 hover:text-gray-600">✕</button>
                </div>
                <form action="{{ route('books.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="text" name="title" class="w-full border-none bg-gray-50 rounded-2xl p-4 focus:ring-2 focus:ring-blue-500 font-medium" placeholder="Full Book Title" required>
                    <div class="grid grid-cols-2 gap-4">
                        <input type="text" name="isbn" class="border-none bg-gray-50 rounded-2xl p-4 focus:ring-2 focus:ring-blue-500 font-medium" placeholder="ISBN Code">
                        <input type="number" name="copies_available" value="1" min="1" class="border-none bg-gray-50 rounded-2xl p-4 focus:ring-2 focus:ring-blue-500 font-medium" required>
                    </div>
                    <select name="author_id" class="w-full border-none bg-gray-50 rounded-2xl p-4 font-medium" required>
                        <option value="">Choose Author</option>
                        @foreach($authors as $author) <option value="{{ $author->id }}">{{ $author->name }}</option> @endforeach
                    </select>
                    <select name="category_id" class="w-full border-none bg-gray-50 rounded-2xl p-4 font-medium" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category) <option value="{{ $category->id }}">{{ $category->name }}</option> @endforeach
                    </select>
                    <div class="grid grid-cols-2 gap-4">
                        <select name="publisher_id" class="border-none bg-gray-50 rounded-2xl p-4 font-medium" required>
                            <option value="">Publisher</option>
                            @foreach($publishers as $pub) <option value="{{ $pub->id }}">{{ $pub->name }}</option> @endforeach
                        </select>
                        <select name="supplier_id" class="border-none bg-gray-50 rounded-2xl p-4 font-medium" required>
                            <option value="">Supplier</option>
                            @foreach($suppliers as $sup) <option value="{{ $sup->id }}">{{ $sup->name }}</option> @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-4 rounded-2xl shadow-xl shadow-blue-100 transition-all active:scale-95">Complete Registration</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
@include('components.alerts')
<script>
    // Enhanced Tab Navigation
    document.querySelectorAll('[data-target]').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('[data-target]').forEach(t => {
                t.classList.remove('active-tab', 'bg-blue-50', 'text-blue-600');
                t.classList.add('text-gray-400');
            });
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.add('hidden'));

            const target = document.querySelector(this.dataset.target);
            target.classList.remove('hidden');
            this.classList.add('active-tab', 'bg-blue-50', 'text-blue-600');
            this.classList.remove('text-gray-400');

            document.getElementById('hubSearch').value = '';
            filterItems('');
        });
    });

    // Search Logic
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

    // Deletion confirmation
    document.querySelectorAll('.delete-book-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            Swal.fire({
                title: 'Confirm Deletion?',
                text: "All associated physical copies will also be removed.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                confirmButtonText: 'Yes, Delete'
            }).then(res => {
                if (res.isConfirmed) document.getElementById(`delete-book-form-${id}`).submit();
            });
        });
    });
</script>
@endpush
