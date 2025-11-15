@extends('components.default')

@section('title', 'Edit Book Copy | Admin Dashboard | LMIS')

@section('content')
<section>
    <div class="min-h-screen pt-24 px-4 lg:px-10">
        @include('components.admin.topnav')
        <div class="flex flex-col lg:flex-row gap-6">

            <div class="lg:w-2/12 w-full">
                @include('components.admin.sidebar')
            </div>

            <div class="lg:w-10/12 w-full">
                <div class="bg-white rounded-xl shadow-lg p-6">

                    <h2 class="text-xl font-bold mb-6">Edit Book Copy</h2>

                    <form action="{{ route('book-copies.update', $bookCopy->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label for="book_id" class="block mb-1">Book</label>
                            <select name="book_id" id="book_id" class="w-full p-2 border rounded" required>
                                @foreach($books as $book)
                                    <option value="{{ $book->id }}" {{ $bookCopy->book_id == $book->id ? 'selected' : '' }}>
                                        {{ $book->title }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label for="copy_number" class="block mb-1">Copy Number</label>
                            <input type="text" name="copy_number" id="copy_number" class="w-full p-2 border rounded" value="{{ $bookCopy->copy_number }}" required>
                        </div>

                        <div>
                            <label for="status" class="block mb-1">Status</label>
                            <select name="status" id="status" class="w-full p-2 border rounded" required>
                                <option value="available" {{ $bookCopy->status == 'available' ? 'selected' : '' }}>Available</option>
                                <option value="borrowed" {{ $bookCopy->status == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                                <option value="lost" {{ $bookCopy->status == 'lost' ? 'selected' : '' }}>Lost</option>
                            </select>
                        </div>

                        <div>
                            <label for="shelf_location" class="block mb-1">Shelf Location</label>
                            <input type="text" name="shelf_location" id="shelf_location" class="w-full p-2 border rounded" value="{{ $bookCopy->shelf_location }}" required>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">Update</button>
                            <a href="{{ route('book-copies.index') }}" class="px-4 py-2 bg-gray-300 rounded">Cancel</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
