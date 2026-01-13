@extends('components.default')

@section('title', 'Borrower Dashboard | LMIS')

@section('content')
<section class="bg-gray-50 min-h-screen" x-data="{ openModal:false, bookId:null, copyId:null }">

@include('components.borrower.topnav')

<div class="px-4 lg:px-10 py-10 space-y-8">

{{-- SEARCH --}}
<form method="GET" class="flex gap-2 bg-white p-3 rounded-full shadow">
    <input type="text" name="search" value="{{ request('search') }}"
        placeholder="Search books..."
        class="w-full border-0 focus:ring-0 text-sm">
    <button class="bg-indigo-600 text-white px-6 py-2 rounded-full text-sm">
        Search
    </button>
</form>

{{-- PER PAGE --}}
<div class="flex justify-between items-center">
    <h3 class="text-lg font-semibold">Available Books</h3>

    <form method="GET" class="flex items-center gap-2">
        @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
        @endif

        <span class="text-sm">Show</span>
        <select name="per_page" onchange="this.form.submit()"
            class="border rounded px-2 py-1 text-sm">
            @foreach([10,25,50] as $n)
                <option value="{{ $n }}"
                    {{ request('per_page',10)==$n ? 'selected' : '' }}>
                    {{ $n }}
                </option>
            @endforeach
        </select>
        <span class="text-sm">entries</span>
    </form>
</div>

{{-- BOOK TABLE --}}
<div class="bg-white shadow rounded-xl overflow-x-auto">
<table class="min-w-full text-sm">
<thead class="bg-gray-100">
<tr>
    <th class="px-4 py-3 text-left">Title</th>
    <th class="px-4 py-3">Author</th>
    <th class="px-4 py-3">Category</th>
    <th class="px-4 py-3">Publisher</th>
    <th class="px-4 py-3">Reserve</th>
</tr>
</thead>
<tbody>
@forelse($books as $book)
<tr class="border-b hover:bg-gray-50">
<td class="px-4 py-3 font-medium">{{ $book->title }}</td>
<td class="px-4 py-3">{{ $book->author->name ?? 'N/A' }}</td>
<td class="px-4 py-3">{{ $book->category->name ?? 'N/A' }}</td>
<td class="px-4 py-3">{{ $book->publisher->name ?? 'N/A' }}</td>
<td class="px-4 py-3">
    <div class="flex flex-wrap gap-2">
        @foreach($book->copies as $copy)
        <button
            @click="openModal=true; bookId={{ $book->id }}; copyId={{ $copy->id }}"
            class="text-xs px-3 py-1 rounded-full border
            {{ $copy->status=='available'
                ? 'bg-green-100 text-green-700'
                : 'bg-red-100 text-red-700 cursor-not-allowed' }}"
            {{ $copy->status!='available'?'disabled':'' }}>
            Copy #{{ $copy->copy_number }}
        </button>
        @endforeach
    </div>
</td>
</tr>
@empty
<tr>
<td colspan="5" class="text-center py-6 text-gray-500">
    No books found.
</td>
</tr>
@endforelse
</tbody>
</table>
</div>

{{-- PAGINATION --}}
<div class="mt-6 flex justify-center">
    {{ $books->links() }}
</div>

{{-- MODAL --}}
<div x-show="openModal" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
<div class="bg-white p-6 rounded-xl w-96">
    <h3 class="text-lg font-semibold mb-4">Confirm Reservation</h3>
    <form method="POST" action="{{ route('borrower.reserve') }}">
        @csrf
        <input type="hidden" name="book_id" :value="bookId">
        <input type="hidden" name="copy_id" :value="copyId">
        <div class="flex justify-end gap-3">
            <button type="button" @click="openModal=false"
                class="px-4 py-2 border rounded">
                Cancel
            </button>
            <button class="px-4 py-2 bg-indigo-600 text-white rounded">
                Reserve
            </button>
        </div>
    </form>
</div>
</div>

</div>

<script src="//unpkg.com/alpinejs" defer></script>
</section>
@endsection
