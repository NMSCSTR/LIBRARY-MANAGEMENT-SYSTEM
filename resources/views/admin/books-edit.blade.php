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

                    <form action="{{ route('books.update', $book->id) }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')

                        {{-- Title --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Title</label>
                            <div class="flex items-center bg-gray-50 border border-gray-300 rounded-lg px-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v12m6-6H6" />
                                </svg>
                                <input type="text" name="title" value="{{ $book->title }}"
                                    class="w-full p-2.5 bg-transparent focus:outline-none text-sm" required>
                            </div>
                        </div>

                        {{-- ISBN --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">ISBN</label>
                            <div class="flex items-center bg-gray-50 border border-gray-300 rounded-lg px-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                <input type="text" name="isbn" value="{{ $book->isbn }}"
                                    class="w-full p-2.5 bg-transparent focus:outline-none text-sm" required>
                            </div>
                        </div>

                        {{-- Author --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Author</label>
                            <div class="flex items-center bg-gray-50 border border-gray-300 rounded-lg px-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4.5 20.25a9 9 0 1115 0v.75H4.5v-.75z" />
                                </svg>
                                <select name="author_id" class="w-full p-2.5 bg-transparent focus:outline-none text-sm" required>
                                    <option value="">Select Author</option>
                                    @foreach($authors as $author)
                                        <option value="{{ $author->id }}" {{ $book->author_id == $author->id ? 'selected' : '' }}>
                                            {{ $author->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Category --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Category</label>
                            <div class="flex items-center bg-gray-50 border border-gray-300 rounded-lg px-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                                <select name="category_id" class="w-full p-2.5 bg-transparent focus:outline-none text-sm" required>
                                    <option value="">Select Category</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $book->category_id == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Publisher --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Publisher</label>
                            <div class="flex items-center bg-gray-50 border border-gray-300 rounded-lg px-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 7h18M3 12h18M3 17h18" />
                                </svg>
                                <select name="publisher_id" class="w-full p-2.5 bg-transparent focus:outline-none text-sm" required>
                                    @foreach($publishers as $pub)
                                        <option value="{{ $pub->id }}" {{ $book->publisher_id == $pub->id ? 'selected' : '' }}>
                                            {{ $pub->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Supplier --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Supplier</label>
                            <div class="flex items-center bg-gray-50 border border-gray-300 rounded-lg px-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 10h18M3 14h18" />
                                </svg>
                                <select name="supplier_id" class="w-full p-2.5 bg-transparent focus:outline-none text-sm" required>
                                    @foreach($suppliers as $sup)
                                        <option value="{{ $sup->id }}" {{ $book->supplier_id == $sup->id ? 'selected' : '' }}>
                                            {{ $sup->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Copies --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Available Copies</label>
                            <div class="flex items-center bg-gray-50 border border-gray-300 rounded-lg px-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 4v16m8-8H4" />
                                </svg>
                                <input type="number" name="copies_available" min="0"
                                    value="{{ $book->copies_available }}"
                                    class="w-full p-2.5 bg-transparent focus:outline-none text-sm">
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 shadow-md hover:shadow-lg transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                            Update Book
                        </button>

                    </form>

                </div>

            </div>

        </div>
    </div>
</section>

@endsection
