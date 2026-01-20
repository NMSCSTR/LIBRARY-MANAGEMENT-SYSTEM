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

            {{-- Main Content --}}p
            <div class="lg:w-10/12 w-full">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

                    {{-- Navigation Tabs --}}
                    <div class="border-b border-gray-200 bg-gray-50/50">
                        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="hubTab" role="tablist">
                            <li class="me-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 rounded-t-lg active-tab" id="books-tab" data-target="#books-content" type="button">Books Inventory</button>
                            </li>
                            <li class="me-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 rounded-t-lg border-transparent hover:text-gray-600 hover:border-gray-300" id="authors-tab" data-target="#authors-content" type="button">Authors</button>
                            </li>
                            <li class="me-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 rounded-t-lg border-transparent hover:text-gray-600 hover:border-gray-300" id="categories-tab" data-target="#categories-content" type="button">Categories</button>
                            </li>
                            <li class="me-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 rounded-t-lg border-transparent hover:text-gray-600 hover:border-gray-300" id="publishers-tab" data-target="#publishers-content" type="button">Publishers</button>
                            </li>
                            <li class="me-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 rounded-t-lg border-transparent hover:text-gray-600 hover:border-gray-300" id="suppliers-tab" data-target="#suppliers-content" type="button">Suppliers</button>
                            </li>
                        </ul>
                    </div>

                    {{-- Tab Contents --}}
                    <div id="hubTabContent">

                        {{-- 1. BOOKS TAB --}}
                        <div class="p-6 tab-pane" id="books-content">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold text-gray-800">Books Inventory</h2>
                                <button data-modal-target="defaultModal" data-modal-toggle="defaultModal"
                                    class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg shadow transition">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Add Book
                                </button>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-600">
                                    <thead class="text-xs uppercase bg-gray-100 text-gray-700">
                                        <tr>
                                            <th class="py-3 px-4">Title</th>
                                            <th class="py-3 px-4">Author</th>
                                            <th class="py-3 px-4">Category</th>
                                            <th class="py-3 px-4">Copies</th>
                                            <th class="py-3 px-4 text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @forelse($books as $book)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="py-3 px-4 font-medium text-gray-900">{{ $book->title }}</td>
                                            <td class="py-3 px-4">{{ $book->author?->name ?? 'N/A' }}</td>
                                            <td class="py-3 px-4">
                                                <span class="bg-blue-50 text-blue-700 px-2 py-1 rounded-md text-xs">{{ $book->category?->name ?? 'N/A' }}</span>
                                            </td>
                                            <td class="py-3 px-4">{{ $book->copies?->count() ?? 0 }}</td>
                                            <td class="py-3 px-4 flex justify-end gap-2">
                                                <a href="{{ route('books.edit', $book->id) }}" class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-md">Edit</a>
                                                <button data-id="{{ $book->id }}" class="delete-book-btn p-1.5 text-red-600 hover:bg-red-50 rounded-md">Delete</button>
                                                <form id="delete-book-form-{{ $book->id }}" action="{{ route('books.destroy', $book->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="5" class="text-center py-10 text-gray-400">No books found.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- 2. AUTHORS TAB --}}
                        <div class="p-6 hidden tab-pane" id="authors-content">
                            <h2 class="text-xl font-bold text-gray-800 mb-6">Authors Registry</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($authors as $author)
                                <div class="p-4 border border-gray-100 rounded-xl bg-gray-50 flex justify-between items-center">
                                    <div>
                                        <h4 class="font-bold text-gray-800">{{ $author->name }}</h4>
                                        <p class="text-xs text-gray-500">Resource ID: #{{ $author->id }}</p>
                                    </div>
                                    <a href="{{ route('authors.edit', $author->id) }}" class="text-blue-600 hover:underline text-sm font-medium">Manage</a>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- 3. CATEGORIES TAB --}}
                        <div class="p-6 hidden tab-pane" id="categories-content">
                            <h2 class="text-xl font-bold text-gray-800 mb-6">Categories</h2>
                            <div class="flex flex-wrap gap-3">
                                @foreach($categories as $category)
                                <div class="px-4 py-3 bg-white border border-gray-200 rounded-lg shadow-sm flex items-center gap-4">
                                    <span class="font-semibold text-gray-700">{{ $category->name }}</span>
                                    <a href="{{ route('categories.edit', $category->id) }}" class="text-xs text-gray-400 hover:text-blue-600">Edit</a>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- 4. PUBLISHERS TAB --}}
                        <div class="p-6 hidden tab-pane" id="publishers-content">
                            <h2 class="text-xl font-bold text-gray-800 mb-6">Publishers</h2>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="p-3">Name</th>
                                            <th class="p-3">Email</th>
                                            <th class="p-3">Phone</th>
                                            <th class="p-3">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($publishers as $pub)
                                        <tr class="border-b">
                                            <td class="p-3 font-medium">{{ $pub->name }}</td>
                                            <td class="p-3">{{ $pub->email ?? '-' }}</td>
                                            <td class="p-3">{{ $pub->phone ?? '-' }}</td>
                                            <td class="p-3"><a href="{{ route('publishers.edit', $pub->id) }}" class="text-blue-600">Edit</a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- 5. SUPPLIERS TAB --}}
                        <div class="p-6 hidden tab-pane" id="suppliers-content">
                            <h2 class="text-xl font-bold text-gray-800 mb-6">Suppliers</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($suppliers as $sup)
                                <div class="p-4 border rounded-xl flex justify-between items-start">
                                    <div>
                                        <h4 class="font-bold">{{ $sup->name }}</h4>
                                        <p class="text-sm text-gray-500">{{ $sup->contact_person }}</p>
                                    </div>
                                    <a href="{{ route('suppliers.edit', $sup->id) }}" class="bg-gray-100 px-3 py-1 rounded text-xs">Manage</a>
                                </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Book Modal --}}
    <div id="defaultModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="w-full max-w-lg p-4">
            <div class="bg-white rounded-2xl shadow-lg p-6">
                <h3 class="text-xl font-semibold mb-4 text-gray-800">Add New Book</h3>
                <form action="{{ route('books.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <input type="text" name="title" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="Title" required>
                    <input type="text" name="isbn" class="w-full border border-gray-300 rounded-lg px-3 py-2" placeholder="ISBN">
                    <input type="number" name="copies_available" value="1" min="1" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>

                    <select name="author_id" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                        <option value="">Select Author</option>
                        @foreach($authors as $author) <option value="{{ $author->id }}">{{ $author->name }}</option> @endforeach
                    </select>

                    <select name="category_id" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                        <option value="">Select Category</option>
                        @foreach($categories as $category) <option value="{{ $category->id }}">{{ $category->name }}</option> @endforeach
                    </select>

                    <div class="grid grid-cols-2 gap-4">
                        <select name="publisher_id" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                            <option value="">Publisher</option>
                            @foreach($publishers as $pub) <option value="{{ $pub->id }}">{{ $pub->name }}</option> @endforeach
                        </select>
                        <select name="supplier_id" class="w-full border border-gray-300 rounded-lg px-3 py-2" required>
                            <option value="">Supplier</option>
                            @foreach($suppliers as $sup) <option value="{{ $sup->id }}">{{ $sup->name }}</option> @endforeach
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition">Add Book</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
@include('components.alerts')
<style>
    .active-tab { border-color: #2563eb; color: #2563eb; }
</style>
<script>
    // Tab Switching Logic
    document.querySelectorAll('[data-target]').forEach(tab => {
        tab.addEventListener('click', function() {
            // Remove active styles from all tabs
            document.querySelectorAll('[data-target]').forEach(t => {
                t.classList.remove('active-tab', 'border-blue-600', 'text-blue-600');
                t.classList.add('border-transparent', 'text-gray-500');
            });

            // Hide all panes
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.add('hidden'));

            // Show target pane
            const target = document.querySelector(this.dataset.target);
            target.classList.remove('hidden');

            // Add active styles to clicked tab
            this.classList.add('active-tab', 'border-blue-600', 'text-blue-600');
            this.classList.remove('border-transparent', 'text-gray-500');
        });
    });

    // Delete confirmation
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
