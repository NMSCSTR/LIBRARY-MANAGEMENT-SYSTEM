@extends('components.default')

@section('title', 'Borrower Dashboard | LMIS')

@section('content')
<section class="bg-gray-50 min-h-screen">

    {{-- Top Navigation --}}
    @include('components.borrower.topnav')

    <div class="flex flex-col lg:flex-row px-4 lg:px-10 pb-10 gap-6 pt-10 mt-10">

        {{-- Main Content --}}
        <div class="w-full space-y-8 mt-10">

            {{-- Header --}}
            <div class="text-center lg:text-left">
                <h1 class="text-3xl font-bold text-indigo-900">Borrower Dashboard</h1>
                <p class="text-gray-500 mt-2 text-sm sm:text-base">
                    Keep track of your borrowed books, reservations, and explore the library collection.
                </p>
            </div>

            {{-- Dashboard Summary Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5">
                @php
                    $cards = [
                        ['icon'=>'menu_book','label'=>'Total Books Borrowed','value'=>$summary['borrowed'] ?? 0,'color'=>'indigo'],
                        ['icon'=>'access_time','label'=>'Overdue Books','value'=>$summary['overdue'] ?? 0,'color'=>'red'],
                        ['icon'=>'check_circle','label'=>'Available Books','value'=>$summary['available'] ?? 0,'color'=>'green'],
                        ['icon'=>'schedule','label'=>'Reservations','value'=>$summary['reserved'] ?? 0,'color'=>'indigo'],
                    ];
                @endphp

                @foreach($cards as $card)
                <div class="bg-white rounded-2xl shadow-md p-6 flex flex-col items-center hover:shadow-lg transform hover:scale-105 transition">
                    <div class="p-3 rounded-full bg-{{ $card['color'] }}-100 mb-3">
                        <span class="material-icons text-3xl text-{{ $card['color'] }}-600">
                            {{ $card['icon'] }}
                        </span>
                    </div>
                    <p class="text-sm text-gray-500">{{ $card['label'] }}</p>
                    <p class="text-xl font-semibold text-{{ $card['color'] }}-900">
                        {{ $card['value'] }}
                    </p>
                </div>
                @endforeach
            </div>

            {{-- Search Bar --}}
            <form method="GET" action="{{ route('borrower.dashboard') }}">
                <div class="flex items-center gap-2 bg-white rounded-full shadow-md px-4 py-3">
                    <span class="material-icons-outlined text-gray-400">search</span>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search books, authors, categories, publishers..."
                        class="w-full border-0 focus:ring-0 text-sm placeholder-gray-400 rounded-full">
                    <button type="submit"
                        class="bg-indigo-600 text-white px-6 py-2 rounded-full text-sm font-medium hover:bg-indigo-700 transition">
                        Search
                    </button>
                </div>
            </form>

            {{-- BOOKS TABLE WITH MODAL --}}
            <div class="bg-white rounded-2xl shadow-md p-6" x-data="{ openModal: false, modalBookId: null, modalCopyId: null }">
                <h3 class="text-lg font-semibold mb-4">
                    {{ $keyword ? 'Search Results for' : 'Available Books' }}
                    @if($keyword)
                        <span class="text-indigo-600">“{{ $keyword }}”</span>
                    @endif
                </h3>

                <div class="overflow-x-auto rounded-lg">
                    <table class="min-w-full text-sm border-collapse">
                        <thead class="bg-gray-100 sticky top-0">
                            <tr>
                                <th class="px-4 py-3 text-left">Title</th>
                                <th class="px-4 py-3">ISBN</th>
                                <th class="px-4 py-3">Author</th>
                                <th class="px-4 py-3">Category</th>
                                <th class="px-4 py-3">Publisher</th>
                                <th class="px-4 py-3">Copies & Reserve</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($books as $book)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-3 font-medium text-indigo-900">
                                    {{ $book->title }}
                                </td>

                                <td class="px-4 py-3 text-gray-600">{{ $book->isbn }}</td>
                                <td class="px-4 py-3">{{ $book->author->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3">{{ $book->category->name ?? 'N/A' }}</td>
                                <td class="px-4 py-3">{{ $book->publisher->name ?? 'N/A' }}</td>

                                <td class="px-4 py-3">
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($book->copies as $copy)
                                        <button
                                            @click="openModal=true; modalBookId={{ $book->id }}; modalCopyId={{ $copy->id }}"
                                            class="text-xs px-3 py-1 rounded-full border
                                                {{ $copy->status === 'available'
                                                    ? 'bg-green-100 border-green-300 text-green-700 hover:bg-green-200'
                                                    : 'bg-red-100 border-red-300 text-red-700 cursor-not-allowed' }}"
                                            {{ $copy->status !== 'available' ? 'disabled' : '' }}>
                                            Copy #{{ $copy->copy_number }} · {{ ucfirst($copy->status) }}
                                        </button>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-gray-500">
                                    No books found.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $books->withQueryString()->links() }}
                </div>

                {{-- RESERVE MODAL --}}
                <div
                    x-show="openModal"
                    x-transition
                    class="fixed inset-0 bg-black bg-opacity-40 flex items-center justify-center z-50"
                    style="display: none;"
                >
                    <div class="bg-white rounded-xl shadow-lg w-96 p-6 relative">
                        <h3 class="text-lg font-semibold mb-4">Confirm Reservation</h3>
                        <p class="mb-4 text-sm text-gray-600">
                            Are you sure you want to reserve this copy?
                        </p>

                        <form method="POST" action="{{ route('borrower.reserve') }}">
                            @csrf
                            <input type="hidden" name="book_id" :value="modalBookId">
                            <input type="hidden" name="copy_id" :value="modalCopyId">

                            <div class="flex justify-end gap-3">
                                <button type="button"
                                        @click="openModal=false"
                                        class="px-4 py-2 rounded-full border border-gray-300 text-gray-700 hover:bg-gray-100">
                                    Cancel
                                </button>
                                <button type="submit"
                                        class="px-4 py-2 rounded-full bg-indigo-600 text-white hover:bg-indigo-700">
                                    Reserve
                                </button>
                            </div>
                        </form>

                        <button @click="openModal=false"
                                class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                            <span class="material-icons">close</span>
                        </button>
                    </div>
                </div>

            </div>

            {{-- Borrower Transactions --}}
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">
                    Your Borrowing & Reservation Records
                </h3>

                <div class="overflow-x-auto rounded-lg">
                    <table class="min-w-full text-sm border-collapse">
                        <thead class="bg-gray-100 sticky top-0">
                            <tr>
                                <th class="px-4 py-2">Book</th>
                                <th class="px-4 py-2">Copy #</th>
                                <th class="px-4 py-2">Status</th>
                                <th class="px-4 py-2">Borrowed / Reserved</th>
                                <th class="px-4 py-2">Due Date</th>
                                <th class="px-4 py-2">Returned</th>
                                <th class="px-4 py-2">Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($transactions as $tran)
                            <tr class="border-b even:bg-gray-50">
                                <td class="px-4 py-2">{{ $tran->book->title }}</td>
                                <td class="px-4 py-2">{{ $tran->bookCopy->copy_number ?? '-' }}</td>
                                <td class="px-4 py-2 capitalize">{{ $tran->status ?? 'reserved' }}</td>
                                <td class="px-4 py-2">{{ optional($tran->borrow_date ?? $tran->reserved_at)->format('M d, Y') ?? '-' }}</td>
                                <td class="px-4 py-2">{{ optional($tran->due_date)->format('M d, Y') ?? '-' }}</td>
                                <td class="px-4 py-2">{{ optional($tran->return_date)->format('M d, Y') ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    @if($tran instanceof \App\Models\Reservation)
                                    <form action="{{ route('borrower.cancelReservation', $tran->id) }}" method="POST"
                                          onsubmit="return confirm('Cancel this reservation?');">
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            class="text-xs px-3 py-1 rounded-full bg-red-100 border border-red-300 text-red-700 hover:bg-red-200">
                                            Cancel
                                        </button>
                                    </form>
                                    @else
                                        <span class="text-gray-400 text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4 text-gray-500">
                                    No transactions yet.
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- Alpine.js for modal --}}
<script src="//unpkg.com/alpinejs" defer></script>
@endsection
