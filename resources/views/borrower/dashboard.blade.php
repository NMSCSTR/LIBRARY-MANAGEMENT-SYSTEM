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
                        <span class="material-icons text-3xl text-{{ $card['color'] }}-600">{{ $card['icon'] }}</span>
                    </div>
                    <p class="text-sm text-gray-500">{{ $card['label'] }}</p>
                    <p class="text-xl font-semibold text-{{ $card['color'] }}-900">{{ $card['value'] }}</p>
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

            {{-- Books List --}}
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">
                    {{ $keyword ? 'Search Results for' : 'Available Books' }}
                    @if($keyword)
                        <span class="text-indigo-600">“{{ $keyword }}”</span>
                    @endif
                </h3>

                <div class="space-y-4">
                    @forelse($books as $book)
                    <div class="border rounded-xl p-5 hover:shadow-lg transition duration-300 ease-in-out">
                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-lg font-semibold text-indigo-900">{{ $book->title }}</h4>
                                <p class="text-xs text-gray-400">ISBN: {{ $book->isbn }}</p>
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
                            <p class="text-sm font-semibold mb-2">Copies & Shelf Locations : Click to reserve</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($book->copies as $copy)
                                <form action="{{ route('borrower.reserve') }}" method="POST" class="inline-block">
                                    @csrf
                                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                                    <input type="hidden" name="copy_id" value="{{ $copy->id }}">
                                    <button type="submit"
                                        class="text-xs px-3 py-1 rounded-full border
                                        {{ $copy->status === 'available' ? 'bg-green-100 border-green-300 text-green-700 hover:bg-green-200' : 'bg-red-100 border-red-300 text-red-700 cursor-not-allowed' }}"
                                        {{ $copy->status !== 'available' ? 'disabled' : '' }}>
                                        Copy #{{ $copy->copy_number }} · Shelf: <strong>{{ $copy->shelf_location }}</strong> · {{ ucfirst($copy->status) }}
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

                {{-- Pagination --}}
                <div class="mt-6">
                    {{ $books->withQueryString()->links() }}
                </div>
            </div>

            {{-- Borrower Transactions --}}
            <div class="bg-white rounded-2xl shadow-md p-6">
                <h3 class="text-lg font-semibold mb-4">Your Borrowing & Reservation Records</h3>

                <div class="overflow-x-auto rounded-lg">
                    <table class="min-w-full text-sm text-left border-collapse">
                        <thead class="bg-gray-100 sticky top-0">
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
                            <tr class="border-b even:bg-gray-50">
                                <td class="px-4 py-2">{{ $tran->book->title }}</td>
                                <td class="px-4 py-2">{{ $tran->bookCopy->copy_number ?? '-' }}</td>
                                <td class="px-4 py-2 capitalize">
                                    @php
                                        $status = $tran->status ?? ($tran instanceof \App\Models\Reservation ? 'reserved' : '');
                                        $statusClasses = [
                                            'borrowed'=>'text-yellow-600',
                                            'overdue'=>'text-red-600 font-semibold',
                                            'reserved'=>'text-blue-600'
                                        ];
                                    @endphp
                                    <span class="{{ $statusClasses[$status] ?? 'text-gray-600' }}">
                                        {{ $status }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">{{ optional($tran->borrow_date ?? $tran->reserved_at)->format('M d, Y') ?? '-' }}</td>
                                <td class="px-4 py-2">{{ optional($tran->due_date)->format('M d, Y') ?? '-' }}</td>
                                <td class="px-4 py-2">{{ optional($tran->return_date)->format('M d, Y') ?? '-' }}</td>
                                <td class="px-4 py-2">
                                    @if($tran instanceof \App\Models\Reservation)
                                    <form action="{{ route('borrower.cancelReservation', $tran->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to cancel this reservation?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
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
