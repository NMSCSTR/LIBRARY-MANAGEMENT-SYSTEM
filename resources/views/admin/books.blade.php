@extends('components.default')

@section('title', 'Books | Admin Dashboard | LMIS')

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
                <div class="bg-white rounded-2xl shadow-lg">

                    {{-- Header --}}
                    <div class="px-6 py-6 border-b border-gray-200">

                        {{-- Breadcrumb --}}
                        <nav class="flex items-center text-gray-600 text-sm space-x-2">
                            <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-800">Admin</a>
                            <span>/</span>
                            <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-800">Dashboard</a>
                            <span>/</span>
                            <span class="font-semibold text-gray-800">Books</span>
                        </nav>

                        {{-- Add Button --}}
                        <div class="flex justify-end mt-4">
                            <button
                                data-modal-target="defaultModal"
                                data-modal-toggle="defaultModal"
                                class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg shadow transition-all duration-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add Book
                            </button>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="overflow-x-auto px-6 pb-6">
                        <table class="w-full text-sm text-left text-gray-600 border-separate border-spacing-y-2">
                            <thead class="text-xs uppercase bg-gray-100 text-gray-700 rounded-lg">
                                <tr>
                                    <th class="py-3 px-4">Title</th>
                                    <th class="py-3 px-4">ISBN</th>
                                    <th class="py-3 px-4">Author</th>
                                    <th class="py-3 px-4">Category</th>
                                    <th class="py-3 px-4">Publisher</th>
                                    <th class="py-3 px-4">Year</th>
                                    <th class="py-3 px-4">Place</th>
                                    <th class="py-3 px-4">Supplier</th>
                                    <th class="py-3 px-4">Copies</th>
                                    <th class="py-3 px-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($books as $book)
                                    <tr class="bg-white hover:bg-gray-50 rounded-lg shadow-sm transition-all">
                                        <td class="py-2 px-4">{{ $book->title }}</td>
                                        <td class="py-2 px-4">{{ $book->isbn }}</td>
                                        <td class="py-2 px-4">{{ $book->author?->name ?? 'N/A' }}</td>
                                        <td class="py-2 px-4">{{ $book->category?->name ?? 'N/A' }}</td>
                                        <td class="py-2 px-4">{{ $book->publisher?->name ?? 'N/A' }}</td>
                                        <td class="py-2 px-4">{{ $book->year_published ?? 'N/A' }}</td>
                                        <td class="py-2 px-4">{{ $book->place_published ?? 'N/A' }}</td>
                                        <td class="py-2 px-4">{{ $book->supplier?->name ?? 'N/A' }}</td>
                                        <td class="py-2 px-4">{{ $book->copies?->count() ?? 0 }}</td>
                                        <td class="flex gap-2 py-2 px-4">
                                            <a href="{{ route('books.edit', $book->id) }}"
                                               class="px-3 py-1 text-xs text-white bg-blue-600 rounded hover:bg-blue-700 transition">
                                                Edit
                                            </a>
                                            <button data-id="{{ $book->id }}"
                                                    class="delete-book-btn px-3 py-1 text-xs text-white bg-red-600 rounded hover:bg-red-700 transition">
                                                Delete
                                            </button>

                                            <form id="delete-book-form-{{ $book->id }}"
                                                  action="{{ route('books.destroy', $book->id) }}"
                                                  method="POST" class="hidden">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-6 text-gray-400">
                                            📚 No books found. Click "Add Book" to create one!
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
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

                    <input type="text" name="title" value="{{ old('title') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                           placeholder="Title" required>

                    <input type="text" name="isbn" value="{{ old('isbn') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                           placeholder="ISBN">

                    <input type="number" name="copies_available" value="{{ old('copies_available', 1) }}"
                           min="1" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                           placeholder="Number of Copies" required>

                    {{-- Selects --}}
                    @foreach([
                        'author_id' => $authors,
                        'category_id' => $categories,
                        'publisher_id' => $publishers,
                        'supplier_id' => $suppliers
                    ] as $field => $items)
                        <select name="{{ $field }}" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none" required>
                            <option value="">Select {{ ucfirst(str_replace('_id','',$field)) }}</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" {{ old($field) == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    @endforeach

                    <input type="text" name="year_published" value="{{ old('year_published') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                           placeholder="Year Published (e.g. 2023)">

                    <input type="text" name="place_published" value="{{ old('place_published') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                           placeholder="Place Published">

                    <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg transition-all">
                        Add Book
                    </button>
                </form>

            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
@include('components.alerts')
<script>
    document.querySelectorAll('.delete-book-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            Swal.fire({
                title: 'Delete this book?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
            }).then(res => {
                if (res.isConfirmed) {
                    document.getElementById(`delete-book-form-${id}`).submit();
                }
            });
        });
    });
</script>
@endpush
