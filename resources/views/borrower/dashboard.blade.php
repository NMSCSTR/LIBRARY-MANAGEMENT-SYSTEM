@extends('components.default')

@section('title', 'Borrower Dashboard | LMIS')

@section('content')
<section class="bg-gray-50 min-h-screen pt-24">

    {{-- Top Navigation --}}
    @include('components.admin.topnav')

    <div class="flex flex-col lg:flex-row px-4 lg:px-10 pb-10 gap-6">

        {{-- Sidebar --}}
        <div class="lg:w-2/12 w-full">
            @include('components.admin.sidebar')
        </div>

        {{-- Main Content --}}
        <div class="lg:w-10/12 w-full space-y-8">

            {{-- Search Bar --}}
            <form method="GET" action="{{ route('borrower.dashboard') }}">
                <div class="flex items-center gap-2 bg-white rounded-2xl shadow px-4 py-3">
                    <span class="material-icons-outlined text-gray-400">search</span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search books, authors, categories, publishers..."
                        class="w-full border-0 focus:ring-0 text-sm">
                    <button type="submit"
                        class="bg-indigo-600 text-white px-6 py-2 rounded-xl text-sm font-medium hover:bg-indigo-700 transition">
                        Search
                    </button>
                </div>
            </form>

            {{-- Search Results --}}
            @if(request('search'))
            <div class="bg-white rounded-2xl shadow p-6">

                <h3 class="text-lg font-semibold mb-4">
                    Search Results for
                    <span class="text-indigo-600">“{{ request('search') }}”</span>
                </h3>

                <div class="space-y-4">
                    @forelse($books as $book)
                    <div class="border rounded-xl p-5 hover:shadow-md transition">

                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-lg font-semibold text-indigo-900">{{ $book->title }}</h4>
                                <p class="text-xs text-gray-500">ISBN: {{ $book->isbn }}</p>
                            </div>

                            <span class="text-xs bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full">
                                {{ $book->category->name ?? 'N/A' }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-3 text-sm text-gray-700">
                            <p><strong>Author:</strong> {{ $book->author->name ?? 'N/A' }}</p>
                            <p><strong>Publisher:</strong> {{ $book->publisher->name ?? 'N/A' }}</p>
                            <p><strong>Supplier:</strong> {{ $book->supplier->name ?? 'N/A' }}</p>
                        </div>

                        {{-- Copies & Reserve --}}
                        <div class="mt-4">
                            <p class="text-sm font-semibold mb-2">Copies & Shelf Locations</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($book->copies as $copy)
                                <form action="{{ route('reservations.store') }}" method="POST" class="inline-block">
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                                    <input type="hidden" name="copy_id" value="{{ $copy->id }}">

                                    <button type="submit" class="text-xs px-3 py-1 rounded-full border
                                        {{ $copy->status === 'available'
                                            ? 'bg-green-100 border-green-300 text-green-700 hover:bg-green-200'
                                            : 'bg-red-100 border-red-300 text-red-700 cursor-not-allowed' }}" {{
                                        $copy->status !== 'available' ? 'disabled' : '' }}>
                                        Copy #{{ $copy->copy_number }}
                                        · Shelf: <strong>{{ $copy->shelf_location }}</strong>
                                        · {{ ucfirst($copy->status) }}
                                    </button>
                                </form>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm">No books found.</p>
                    @endforelse
                </div>
            </div>
            @endif

            {{-- Borrower Transactions --}}
            <div class="bg-white rounded-2xl shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Your Borrowing & Reservation Records</h3>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm text-left">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-2">Book</th>
                                <th class="px-4 py-2">Copy #</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Borrowed At</th>
                                <th class="px-4 py-2">Due Date</th>
                                <th class="px-4 py-2">Returned At</th>
                                <th class="px-4 py-2">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transactions as $tran)
                            <tr class="border-b">
                                <td class="px-4 py-2">{{ $tran->book->title }}</td>
                                <td class="px-4 py-2">{{ $tran->bookCopy->copy_number ?? '-' }}</td>
                                <td class="px-4 py-2 capitalize">
                                    <span
                                        class="{{ $tran->status === 'borrowed' ? 'text-yellow-600' : ($tran->status === 'overdue' ? 'text-red-600' : 'text-green-600') }}">
                                        {{ $tran->status }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">{{ optional($tran->borrow_date)->format('M d, Y') ?? '-' }}</td>
                                <td class="px-4 py-2">{{ optional($tran->due_date)->format('M d, Y') ?? '-' }}</td>
                                <td class="px-4 py-2">{{ optional($tran->return_date)->format('M d, Y') ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    @if($tran->status === 'borrowed')
                                    <span class="text-gray-500">Active</span>
                                    @else
                                    <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-4 py-2 text-gray-500 text-center">No transactions yet.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</section>
@endsection
