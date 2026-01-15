@extends('components.default')

@section('title', 'Books | Admin Dashboard | LMIS')

@section('content')
<section>
    <div class="min-h-screen pt-24 bg-gray-100">
        @include('components.admin.topnav')

        <div class="flex flex-col lg:flex-row px-4 lg:px-10 pb-6 gap-6">

            {{-- Sidebar --}}
            <div class="lg:w-2/12 w-full">
                @include('components.admin.sidebar')
            </div>

            {{-- Main Content --}}
            <div class="lg:w-10/12 w-full">
                <div class="bg-white rounded-xl shadow-md">

                    {{-- Header --}}
                    <div class="px-6 py-6">

                        {{-- Breadcrumb --}}
                        <nav class="flex px-5 py-3 text-gray-700 border border-gray-200 rounded-lg bg-gray-100">
                            <ol class="inline-flex items-center space-x-2">
                                <li class="text-sm font-medium">Admin</li>
                                <li class="text-gray-400">/</li>
                                <li class="text-sm font-medium">Dashboard</li>
                                <li class="text-gray-400">/</li>
                                <li class="text-sm font-medium text-gray-500">Books</li>
                            </ol>
                        </nav>

                        {{-- Add Button --}}
                        <div class="flex justify-end mt-4">
                            <button
                                data-modal-target="defaultModal"
                                data-modal-toggle="defaultModal"
                                class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg shadow">
                                ➕ Add Book
                            </button>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="relative overflow-x-auto px-6 pb-6">
                        <table class="w-full text-sm text-left text-gray-600">
                            <thead class="text-xs uppercase bg-gray-100 text-gray-700">
                                <tr>
                                    <th>Title</th>
                                    <th>ISBN</th>
                                    <th>Author</th>
                                    <th>Category</th>
                                    <th>Publisher</th>
                                    <th>Year</th>
                                    <th>Place</th>
                                    <th>Supplier</th>
                                    <th>Copies</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($books as $book)
                                    <tr class="border-b">
                                        <td>{{ $book->title }}</td>
                                        <td>{{ $book->isbn }}</td>
                                        <td>{{ $book->author?->name ?? 'N/A' }}</td>
                                        <td>{{ $book->category?->name ?? 'N/A' }}</td>
                                        <td>{{ $book->publisher?->name ?? 'N/A' }}</td>
                                        <td>{{ $book->year_published ?? 'N/A' }}</td>
                                        <td>{{ $book->place_published ?? 'N/A' }}</td>
                                        <td>{{ $book->supplier?->name ?? 'N/A' }}</td>
                                        <td>{{ $book->copies?->count() ?? 0 }}</td>
                                        <td class="flex gap-2">
                                            <a href="{{ route('books.edit', $book->id) }}"
                                                class="px-3 py-1 text-xs text-white bg-blue-600 rounded">
                                                Edit
                                            </a>

                                            <button data-id="{{ $book->id }}"
                                                class="delete-book-btn px-3 py-1 text-xs text-white bg-red-600 rounded">
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
                                        <td colspan="10" class="text-center py-4 text-gray-500">
                                            No books found.
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
    <div id="defaultModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/30">
        <div class="w-full max-w-lg p-4">
            <div class="bg-white rounded-xl shadow p-6">

                <h3 class="text-lg font-semibold mb-4">Add New Book</h3>

                <form action="{{ route('books.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <input type="text" name="title" value="{{ old('title') }}"
                        class="w-full border rounded px-3 py-2" placeholder="Title" required>

                    <input type="text" name="isbn" value="{{ old('isbn') }}"
                        class="w-full border rounded px-3 py-2" placeholder="ISBN">

                    <input type="number" name="copies_available" value="{{ old('copies_available', 1) }}"
                        min="1" class="w-full border rounded px-3 py-2" placeholder="Number of Copies" required>

                    {{-- Selects --}}
                    @foreach([
                        'author_id' => $authors,
                        'category_id' => $categories,
                        'publisher_id' => $publishers,
                        'supplier_id' => $suppliers
                    ] as $field => $items)
                        <select name="{{ $field }}" class="w-full border rounded px-3 py-2" required>
                            <option value="">Select {{ ucfirst(str_replace('_id','',$field)) }}</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}" {{ old($field) == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                            @endforeach
                        </select>
                    @endforeach

                    <input type="text" name="year_published" value="{{ old('year_published') }}"
                        class="w-full border rounded px-3 py-2" placeholder="Year Published (e.g. 2023)">

                    <input type="text" name="place_published" value="{{ old('place_published') }}"
                        class="w-full border rounded px-3 py-2" placeholder="Place Published">

                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">
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
