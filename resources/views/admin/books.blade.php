@extends('components.default')

@section('title', 'Library Management Hub | LMIS')

@section('content')
<section>
    <div class="min-h-screen pt-24 bg-gray-50">
        @include('components.admin.topnav')

        <div class="flex flex-col lg:flex-row px-4 lg:px-10 pb-6 gap-6">

            {{-- Sidebar --}}
            <div class="lg:w-2/12 w-full">
                @include('components.admin.sidebar')
            </div>

            {{-- Main Content --}}
            <div class="lg:w-10/12 w-full">

                {{-- Global Search Bar --}}
                <div class="mb-6 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" id="hubSearch" placeholder="Search across current tab..."
                        class="block w-full pl-10 pr-3 py-3 border border-gray-200 rounded-2xl leading-5 bg-white shadow-sm placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-all">
                </div>

                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">

                    {{-- Navigation Tabs --}}
                    <div class="border-b border-gray-200 bg-gray-50/50">
                        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="hubTab" role="tablist">
                            <li class="me-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 rounded-t-lg active-tab" data-target="#books-content" type="button">Books Inventory</button>
                            </li>
                            <li class="me-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 rounded-t-lg border-transparent hover:text-gray-600 hover:border-gray-300" data-target="#copies-content" type="button">Individual Copies</button>
                            </li>
                            <li class="me-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 rounded-t-lg border-transparent hover:text-gray-600 hover:border-gray-300" data-target="#authors-content" type="button">Authors</button>
                            </li>
                            <li class="me-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 rounded-t-lg border-transparent hover:text-gray-600 hover:border-gray-300" data-target="#categories-content" type="button">Categories</button>
                            </li>
                            <li class="me-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 rounded-t-lg border-transparent hover:text-gray-600 hover:border-gray-300" data-target="#publishers-content" type="button">Publishers</button>
                            </li>
                            <li class="me-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 rounded-t-lg border-transparent hover:text-gray-600 hover:border-gray-300" data-target="#suppliers-content" type="button">Suppliers</button>
                            </li>
                        </ul>
                    </div>

                    {{-- Tab Contents --}}
                    <div id="hubTabContent">

                        {{-- 1. BOOKS INVENTORY --}}
                        <div class="p-6 tab-pane" id="books-content">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold text-gray-800">Books Inventory</h2>
                                <button data-modal-target="defaultModal" data-modal-toggle="defaultModal"
                                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg shadow transition flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Add Book
                                </button>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-600 search-table">
                                    <thead class="text-xs uppercase bg-gray-50 text-gray-700 font-semibold">
                                        <tr>
                                            <th class="py-3 px-4">Title</th>
                                            <th class="py-3 px-4 text-center">Copies</th>
                                            <th class="py-3 px-4">Author</th>
                                            <th class="py-3 px-4 text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($books as $book)
                                        <tr class="hover:bg-gray-50 transition search-item">
                                            <td class="py-3 px-4 font-bold text-gray-900 search-text">{{ $book->title }}</td>
                                            <td class="py-3 px-4 text-center">
                                                <span class="bg-gray-100 text-gray-800 px-2 py-1 rounded-md font-mono text-xs">{{ $book->copies->count() }}</span>
                                            </td>
                                            <td class="py-3 px-4 search-text">{{ $book->author?->name ?? 'N/A' }}</td>
                                            <td class="py-3 px-4 text-right">
                                                <a href="{{ route('books.edit', $book->id) }}" class="text-blue-600 hover:underline mr-3 text-xs font-bold uppercase">Edit</a>
                                                <button data-id="{{ $book->id }}" class="delete-book-btn text-red-600 hover:underline text-xs font-bold uppercase">Delete</button>
                                                <form id="delete-book-form-{{ $book->id }}" action="{{ route('books.destroy', $book->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- 2. INDIVIDUAL COPIES --}}
                        <div class="p-6 hidden tab-pane" id="copies-content">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold text-gray-800">Individual Copies</h2>
                                <a href="{{ route('book-copies.index') }}" class="text-blue-600 text-xs font-bold uppercase hover:underline">Manage All Copies &rarr;</a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-600 search-table">
                                    <thead class="text-xs uppercase bg-gray-50 text-gray-700 font-semibold">
                                        <tr>
                                            <th class="py-3 px-4">Call #</th>
                                            <th class="py-3 px-4">Book Title</th>
                                            <th class="py-3 px-4">Location</th>
                                            <th class="py-3 px-4">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($books->take(50) as $book)
                                            @foreach($book->copies as $copy)
                                            <tr class="hover:bg-gray-50 transition search-item">
                                                <td class="py-3 px-4 font-mono text-xs search-text">#{{ $copy->copy_number }}</td>
                                                <td class="py-3 px-4 text-gray-800 search-text">{{ $book->title }}</td>
                                                <td class="py-3 px-4 search-text">{{ $copy->shelf_location }}</td>
                                                <td class="py-3 px-4">
                                                    <span class="px-2 py-0.5 rounded-full text-[10px] uppercase font-bold {{ $copy->status == 'available' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }} search-text">
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
                        <div class="p-6 hidden tab-pane" id="authors-content">
                            <h2 class="text-xl font-bold text-gray-800 mb-6">Library Authors</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4" id="authors-grid">
                                @foreach($authors as $author)
                                <div class="p-4 bg-white border border-gray-100 rounded-xl shadow-sm flex items-center justify-between search-item">
                                    <span class="font-bold text-gray-700 search-text">{{ $author->name }}</span>
                                    <a href="{{ route('authors.edit', $author->id) }}" class="p-1 hover:bg-blue-50 text-blue-600 rounded">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- 4. CATEGORIES --}}
                        <div class="p-6 hidden tab-pane" id="categories-content">
                            <h2 class="text-xl font-bold text-gray-800 mb-6">Categories</h2>
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-3">
                                @foreach($categories as $category)
                                <div class="p-3 bg-gray-50 border rounded-lg text-center font-semibold text-gray-600 text-sm search-item">
                                    <span class="search-text">{{ $category->name }}</span>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- 5. PUBLISHERS --}}
                        <div class="p-6 hidden tab-pane" id="publishers-content">
                            <h2 class="text-xl font-bold text-gray-800 mb-6">Publishers</h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach($publishers as $pub)
                                <div class="p-4 border border-gray-200 rounded-xl flex justify-between items-center search-item">
                                    <span class="font-bold text-gray-800 search-text">{{ $pub->name }}</span>
                                    <a href="{{ route('publishers.edit', $pub->id) }}" class="text-blue-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- 6. SUPPLIERS --}}
                        <div class="p-6 hidden tab-pane" id="suppliers-content">
                            <h2 class="text-xl font-bold text-gray-800 mb-6 text-center">Suppliers</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @forelse($suppliers as $supplier)
                                <div class="p-5 bg-white border border-gray-200 rounded-2xl shadow-sm hover:border-blue-300 transition-all flex justify-between items-center search-item">
                                    <div>
                                        <h4 class="font-bold text-gray-900 search-text">{{ $supplier->name }}</h4>
                                        <p class="text-[10px] text-gray-400 uppercase tracking-widest search-text">{{ $supplier->contact_person ?? 'No Contact' }}</p>
                                    </div>
                                    <a href="{{ route('suppliers.edit', $supplier->id) }}" class="p-2 text-blue-600 bg-blue-50 rounded-lg hover:bg-blue-600 hover:text-white transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                </div>
                                @empty
                                <div class="col-span-full py-10 text-center text-gray-400">No suppliers found.</div>
                                @endforelse
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal (Existing logic) --}}
    <div id="defaultModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="w-full max-w-lg p-4 animate-in zoom-in duration-150">
            <div class="bg-white rounded-2xl shadow-2xl p-6">
                <h3 class="text-xl font-bold mb-4 text-gray-800">Add New Book</h3>
                <form action="{{ route('books.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="text" name="title" class="w-full border-gray-200 rounded-xl p-3 focus:ring-blue-500" placeholder="Book Title" required>
                    <div class="grid grid-cols-2 gap-4">
                        <input type="text" name="isbn" class="border-gray-200 rounded-xl p-3 focus:ring-blue-500" placeholder="ISBN">
                        <input type="number" name="copies_available" value="1" min="1" class="border-gray-200 rounded-xl p-3 focus:ring-blue-500" required>
                    </div>
                    <select name="author_id" class="w-full border-gray-200 rounded-xl p-3" required>
                        <option value="">Select Author</option>
                        @foreach($authors as $author) <option value="{{ $author->id }}">{{ $author->name }}</option> @endforeach
                    </select>
                    <select name="category_id" class="w-full border-gray-200 rounded-xl p-3" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category) <option value="{{ $category->id }}">{{ $category->name }}</option> @endforeach
                    </select>
                    <div class="grid grid-cols-2 gap-4">
                        <select name="publisher_id" class="border-gray-200 rounded-xl p-3" required>
                            <option value="">Publisher</option>
                            @foreach($publishers as $pub) <option value="{{ $pub->id }}">{{ $pub->name }}</option> @endforeach
                        </select>
                        <select name="supplier_id" class="border-gray-200 rounded-xl p-3" required>
                            <option value="">Supplier</option>
                            @foreach($suppliers as $sup) <option value="{{ $sup->id }}">{{ $sup->name }}</option> @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg transition">Add Book</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
@include('components.alerts')
<style>
    .active-tab { border-color: #2563eb; color: #2563eb; background-color: #f8fafc; font-weight: 700; }
</style>
<script>
    // Tab Navigation Logic
    document.querySelectorAll('[data-target]').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('[data-target]').forEach(t => {
                t.classList.remove('active-tab', 'border-blue-600', 'text-blue-600');
                t.classList.add('border-transparent', 'text-gray-500');
            });
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.add('hidden'));
            document.querySelector(this.dataset.target).classList.remove('hidden');
            this.classList.add('active-tab', 'border-blue-600', 'text-blue-600');
            this.classList.remove('border-transparent', 'text-gray-500');

            // Clear search when switching tabs
            document.getElementById('hubSearch').value = '';
            filterItems('');
        });
    });

    // Search Filtering Logic
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
                if (el.textContent.toLowerCase().includes(query)) {
                    match = true;
                }
            });

            if (match) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Delete Book confirmation
    document.querySelectorAll('.delete-book-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            Swal.fire({
                title: 'Delete this book?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
            }).then(res => {
                if (res.isConfirmed) document.getElementById(`delete-book-form-${id}`).submit();
            });
        });
    });
</script>
@endpush
