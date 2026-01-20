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
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden border border-gray-100">

                    {{-- Navigation Tabs --}}
                    <div class="border-b border-gray-200 bg-gray-50/50">
                        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="hubTab" role="tablist">
                            <li class="me-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 rounded-t-lg active-tab" id="books-tab" data-target="#books-content" type="button">Books Inventory</button>
                            </li>
                            <li class="me-2" role="presentation">
                                <button class="inline-block p-4 border-b-2 rounded-t-lg border-transparent hover:text-gray-600 hover:border-gray-300" id="copies-tab" data-target="#copies-content" type="button">Individual Copies</button>
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

                        {{-- 1. BOOKS INVENTORY --}}
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
                                            <th class="py-3 px-4 text-gray-500">Title</th>
                                            <th class="py-3 px-4 text-gray-500">Author</th>
                                            <th class="py-3 px-4 text-gray-500">Category</th>
                                            <th class="py-3 px-4 text-gray-500">Total Copies</th>
                                            <th class="py-3 px-4 text-right text-gray-500">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @forelse($books as $book)
                                        <tr class="hover:bg-gray-50 transition">
                                            <td class="py-3 px-4 font-bold text-gray-900">{{ $book->title }}</td>
                                            <td class="py-3 px-4 text-gray-600">{{ $book->author?->name ?? 'N/A' }}</td>
                                            <td class="py-3 px-4">
                                                <span class="bg-blue-100 text-blue-700 px-2.5 py-0.5 rounded text-xs font-semibold">{{ $book->category?->name ?? 'N/A' }}</span>
                                            </td>
                                            <td class="py-3 px-4">
                                                <span class="text-gray-900 font-semibold">{{ $book->copies->count() }}</span>
                                            </td>
                                            <td class="py-3 px-4 flex justify-end gap-2">
                                                <a href="{{ route('books.edit', $book->id) }}" class="text-blue-600 hover:underline text-xs font-bold">Edit</a>
                                                <button data-id="{{ $book->id }}" class="delete-book-btn text-red-600 hover:underline text-xs font-bold">Delete</button>
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

                        {{-- 2. INDIVIDUAL COPIES TAB --}}
                        <div class="p-6 hidden tab-pane" id="copies-content">
                            <h2 class="text-xl font-bold text-gray-800 mb-6">Individual Book Copies Status</h2>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left text-gray-600">
                                    <thead class="text-xs uppercase bg-gray-50 text-gray-700 font-semibold">
                                        <tr>
                                            <th class="py-3 px-4">Book Title</th>
                                            <th class="py-3 px-4">Call Number</th>
                                            <th class="py-3 px-4">Shelf Location</th>
                                            <th class="py-3 px-4">Status</th>
                                            <th class="py-3 px-4 text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach($books as $book)
                                            @foreach($book->copies as $copy)
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="py-3 px-4 text-gray-800">{{ $book->title }}</td>
                                                <td class="py-3 px-4 font-mono text-xs text-gray-500">#{{ $copy->copy_number }}</td>
                                                <td class="py-3 px-4 text-gray-600">{{ $copy->shelf_location }}</td>
                                                <td class="py-3 px-4">
                                                    @php
                                                        $colors = [
                                                            'available' => 'bg-green-100 text-green-700',
                                                            'borrowed' => 'bg-yellow-100 text-yellow-700',
                                                            'damaged' => 'bg-red-100 text-red-700',
                                                            'lost' => 'bg-gray-100 text-gray-800'
                                                        ];
                                                    @endphp
                                                    <span class="px-2 py-1 rounded-full text-[10px] uppercase font-bold {{ $colors[$copy->status] ?? 'bg-blue-100 text-blue-700' }}">
                                                        {{ $copy->status }}
                                                    </span>
                                                </td>
                                                <td class="py-3 px-4 text-right">
                                                    <a href="{{ route('book-copies.edit', $copy->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">Edit</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- 3. AUTHORS TAB --}}
                        <div class="p-6 hidden tab-pane" id="authors-content">
                            <h2 class="text-xl font-bold text-gray-800 mb-6 text-center">Library Authors</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                                @foreach($authors as $author)
                                <div class="p-4 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-md transition text-center">
                                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-full flex items-center justify-center mx-auto mb-3 font-bold">
                                        {{ substr($author->name, 0, 1) }}
                                    </div>
                                    <h4 class="font-bold text-gray-900">{{ $author->name }}</h4>
                                    <a href="{{ route('authors.edit', $author->id) }}" class="mt-2 inline-block text-xs text-blue-600 font-semibold hover:underline">Edit Author</a>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- 4. CATEGORIES TAB --}}
                        <div class="p-6 hidden tab-pane" id="categories-content">
                            <h2 class="text-xl font-bold text-gray-800 mb-6">Book Categories</h2>
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                @foreach($categories as $category)
                                <div class="px-4 py-6 bg-gray-50 border border-gray-100 rounded-2xl text-center group hover:bg-blue-600 transition-all cursor-pointer">
                                    <p class="font-bold text-gray-700 group-hover:text-white">{{ $category->name }}</p>
                                    <a href="{{ route('categories.edit', $category->id) }}" class="text-[10px] text-gray-400 group-hover:text-blue-100 uppercase">Manage</a>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- 5. PUBLISHERS TAB --}}
                        <div class="p-6 hidden tab-pane" id="publishers-content">
                             <h2 class="text-xl font-bold text-gray-800 mb-6">Publishers</h2>
                             {{-- Add your Improved Publishers UI here --}}
                        </div>

                        {{-- 6. SUPPLIERS TAB --}}
                        <div class="p-6 hidden tab-pane" id="suppliers-content">
                             <h2 class="text-xl font-bold text-gray-800 mb-6">Suppliers</h2>
                             {{-- Supplier content here --}}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Add Book Modal --}}
    {{-- (Existing modal code remains exactly the same) --}}
</section>
@endsection

@push('scripts')
<style>
    .active-tab { border-color: #2563eb; color: #2563eb; background-color: white; }
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

            const target = document.querySelector(this.dataset.target);
            target.classList.remove('hidden');
            this.classList.add('active-tab', 'border-blue-600', 'text-blue-600');
            this.classList.remove('border-transparent', 'text-gray-500');
        });
    });

    // Delete confirmation
    document.querySelectorAll('.delete-book-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            Swal.fire({
                title: 'Confirm Deletion?',
                text: "Deleting this book will also remove its associated copies!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then(res => {
                if (res.isConfirmed) document.getElementById(`delete-book-form-${id}`).submit();
            });
        });
    });
</script>
@endpush
