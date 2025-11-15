@extends('components.default')

@section('title', 'Edit Book | Admin Dashboard | LMIS')

@section('content')

<section>
    <div class="min-h-screen pt-24">
        @include('components.admin.topnav')

        <div class="flex flex-col lg:flex-row px-4 lg:px-10 pb-4 gap-6">

            {{-- Sidebar --}}
            <div class="lg:w-2/12 w-full">
                @include('components.admin.sidebar')
            </div>

            {{-- Main Content --}}
            <div class="lg:w-10/12 w-full">

                <div class="bg-white rounded-xl shadow-lg px-6 py-6">

                    <h2 class="text-2xl font-semibold text-gray-800 mb-5">Edit Book</h2>

                    <form action="{{ route('books.update', $book->id) }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        {{-- Title --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Title</label>
                            <input type="text" name="title" value="{{ $book->title }}"
                                class="w-full p-2.5 border rounded-lg bg-gray-50" required>
                        </div>

                        {{-- ISBN --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">ISBN</label>
                            <input type="text" name="isbn" value="{{ $book->isbn }}"
                                class="w-full p-2.5 border rounded-lg bg-gray-50" required>
                        </div>

                        {{-- Author --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Author</label>
                            <select name="author_id" class="w-full p-2.5 border rounded-lg bg-gray-50" required>
                                <option value="">Select Author</option>
                                @foreach($authors as $author)
                                    <option value="{{ $author->id }}" {{ $book->author_id == $author->id ? 'selected' : '' }}>
                                        {{ $author->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Category --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Category</label>
                            <select name="category_id" class="w-full p-2.5 border rounded-lg bg-gray-50" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}" {{ $book->category_id == $cat->id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Publisher --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Publisher</label>
                            <select name="publisher_id" class="w-full p-2.5 border rounded-lg bg-gray-50" required>
                                @foreach($publishers as $pub)
                                    <option value="{{ $pub->id }}" {{ $book->publisher_id == $pub->id ? 'selected' : '' }}>
                                        {{ $pub->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Supplier --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Supplier</label>
                            <select name="supplier_id" class="w-full p-2.5 border rounded-lg bg-gray-50" required>
                                @foreach($suppliers as $sup)
                                    <option value="{{ $sup->id }}" {{ $book->supplier_id == $sup->id ? 'selected' : '' }}>
                                        {{ $sup->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Copies --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Available Copies</label>
                            <input type="number" name="copies_available" min="0"
                                value="{{ $book->copies_available }}"
                                class="w-full p-2.5 border rounded-lg bg-gray-50">
                        </div>

                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg shadow">
                            Update Book
                        </button>

                    </form>

                </div>

            </div>

        </div>
    </div>
</section>

@endsection
