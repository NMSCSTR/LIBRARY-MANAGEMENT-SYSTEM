@extends('components.default')

@section('title', 'My Library Portal | LMIS')

@section('content')
<section class="bg-gray-50/50 min-h-screen pt-24">

    {{-- Top Navigation --}}
    @include('components.borrower.topnav')

    <div class="flex flex-col lg:flex-row px-4 lg:px-10 pb-10 gap-6">

        {{-- Main Content --}}
        <div class="w-full space-y-8 mt-6">

            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h1 class="text-4xl font-black text-gray-900 tracking-tighter">My Library Portal</h1>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest mt-1">
                        Track your reading journey and manage reservations
                    </p>
                </div>
                <div class="bg-indigo-50 px-6 py-3 rounded-2xl border border-indigo-100">
                    <span class="text-[10px] font-black uppercase text-indigo-400 block mb-1">Member Status</span>
                    <span class="text-sm font-black text-indigo-600 uppercase italic">Active Account</span>
                </div>
            </div>

            {{-- Dashboard Summary Cards --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @php
                    $cards = [
                        ['icon'=>'menu_book','label'=>'Currently Borrowed','value'=>$summary['borrowed'] ?? 0,'color'=>'blue'],
                        ['icon'=>'report_problem','label'=>'Overdue Now','value'=>$summary['overdue'] ?? 0,'color'=>'red'],
                        ['icon'=>'library_add','label'=>'Available Titles','value'=>$summary['available'] ?? 0,'color'=>'emerald'],
                        ['icon'=>'event_seat','label'=>'My Reservations','value'=>$summary['reserved'] ?? 0,'color'=>'indigo'],
                    ];
                @endphp

                @foreach($cards as $card)
                <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 p-6 flex flex-col items-center hover:shadow-xl transition-all group">
                    <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <span class="material-icons text-3xl text-gray-300 group-hover:text-gray-900 transition-colors">
                            {{ $card['icon'] }}
                        </span>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">{{ $card['label'] }}</p>
                    <p class="text-3xl font-black text-gray-900">
                        {{ $card['value'] }}
                    </p>
                </div>
                @endforeach
            </div>

            {{-- Search Bar --}}
            <div class="relative group">
                <form method="GET" action="{{ route('borrower.dashboard') }}">
                    <div class="absolute inset-y-0 left-0 pl-5 flex items-center pointer-events-none">
                        <span class="material-icons-outlined text-gray-400 group-focus-within:text-indigo-600 transition-colors">search</span>
                    </div>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Search our vast collection by title, author, or ISBN..."
                        class="block w-full pl-14 pr-32 py-5 border-none rounded-3xl bg-white shadow-xl shadow-gray-200/50 focus:ring-4 focus:ring-indigo-100 text-gray-700 font-bold">
                    <button type="submit"
                        class="absolute right-3 top-2 bottom-2 bg-indigo-600 text-white px-8 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-indigo-700 transition active:scale-95">
                        Find Book
                    </button>
                </form>
            </div>

            {{-- BOOKS EXPLORER --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/40 overflow-hidden border border-gray-100 p-8" x-data="{ openModal: false, modalBookId: null, modalCopyId: null }">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="text-2xl font-black text-gray-800 tracking-tight">
                        {{ $keyword ? 'Search Results' : 'Explore Collection' }}
                        @if($keyword) <span class="text-indigo-600 italic ml-2">“{{ $keyword }}”</span> @endif
                    </h3>
                </div>

                <div class="overflow-x-auto rounded-2xl">
                    <table class="w-full text-sm text-left">
                        <thead class="text-[10px] uppercase bg-gray-50 text-gray-400 font-black tracking-widest border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-5">Title & Author</th>
                                <th class="px-6 py-5">Classification</th>
                                <th class="px-6 py-5 text-center">Availability</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-50">
                            @forelse($books as $book)
                            <tr class="hover:bg-indigo-50/20 transition group">
                                <td class="px-6 py-6">
                                    <p class="font-black text-gray-900 text-base leading-tight">{{ $book->title }}</p>
                                    <p class="text-xs font-bold text-gray-400 mt-1 uppercase">{{ $book->author->name ?? 'Unknown Author' }}</p>
                                </td>
                                <td class="px-6 py-6">
                                    <span class="text-[10px] font-black text-indigo-600 bg-indigo-50 px-3 py-1.5 rounded-xl uppercase">{{ $book->category->name ?? 'General' }}</span>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex justify-center gap-2">
                                        @foreach($book->copies as $copy)
                                        <button
                                            @click="openModal=true; modalBookId={{ $book->id }}; modalCopyId={{ $copy->id }}"
                                            class="flex flex-col items-center justify-center w-24 py-3 rounded-2xl border-2 transition-all active:scale-90
                                                {{ $copy->status === 'available'
                                                    ? 'bg-emerald-50 border-emerald-100 text-emerald-700 hover:bg-emerald-600 hover:text-white hover:border-emerald-600'
                                                    : 'bg-gray-100 border-gray-100 text-gray-400 cursor-not-allowed' }}"
                                            {{ $copy->status !== 'available' ? 'disabled' : '' }}>
                                            <span class="text-[9px] font-black uppercase tracking-tighter">Copy #{{ $copy->copy_number }}</span>
                                            <span class="text-[10px] font-black">{{ $copy->status === 'available' ? 'RESERVE' : 'TAKEN' }}</span>
                                        </button>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="px-4 py-12 text-center text-gray-300 font-bold uppercase tracking-widest">No books match your criteria</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-8">{{ $books->withQueryString()->links() }}</div>

                {{-- RESERVE MODAL --}}
                <div x-show="openModal" x-transition class="fixed inset-0 bg-gray-900/60 backdrop-blur-md flex items-center justify-center z-50 p-4" style="display: none;">
                    <div class="bg-white rounded-[3rem] shadow-2xl w-full max-w-md p-10 relative border border-gray-100">
                        <h3 class="text-3xl font-black text-gray-900 tracking-tighter mb-4">Confirm Reservation</h3>
                        <p class="mb-8 text-sm font-medium text-gray-400 leading-relaxed">
                            Reserving this copy will hold it for you. Please visit the circulation desk within 24 hours to complete the borrowing process.
                        </p>

                        <form method="POST" action="{{ route('borrower.reserve') }}">
                            @csrf
                            <input type="hidden" name="book_id" :value="modalBookId">
                            <input type="hidden" name="copy_id" :value="modalCopyId">

                            <div class="flex gap-3">
                                <button type="button" @click="openModal=false"
                                        class="flex-1 py-4 rounded-2xl bg-gray-100 text-gray-500 font-black text-xs uppercase transition hover:bg-gray-200">
                                    Dismiss
                                </button>
                                <button type="submit"
                                        class="flex-1 py-4 rounded-2xl bg-indigo-600 text-white font-black text-xs uppercase shadow-xl shadow-indigo-100 hover:bg-indigo-700 transition active:scale-95">
                                    Confirm
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- BORROWER RECORDS --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/40 overflow-hidden border border-gray-100 p-8">
                <h3 class="text-2xl font-black text-gray-800 tracking-tight mb-8">My Borrowing & Reservation History</h3>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-[10px] uppercase bg-gray-50 text-gray-400 font-black tracking-widest">
                            <tr>
                                <th class="px-6 py-4">Resource</th>
                                <th class="px-6 py-4 text-center">Reference</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4">Timeline</th>
                                <th class="px-6 py-4 text-right">Action</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-50">
                            @forelse($transactions as $tran)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-6 font-black text-gray-800">{{ $tran->book->title }}</td>
                                <td class="px-6 py-6 text-center">
                                    <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded-md">#{{ $tran->bookCopy->copy_number ?? '-' }}</span>
                                </td>
                                <td class="px-6 py-6">
                                    <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border
                                        {{ $tran->status == 'returned' ? 'bg-emerald-50 text-emerald-700 border-emerald-100' : ($tran->status == 'overdue' ? 'bg-red-50 text-red-600 border-red-200 animate-pulse' : 'bg-blue-50 text-blue-700 border-blue-100') }}">
                                        {{ $tran->status ?? 'Reserved' }}
                                    </span>
                                </td>
                                <td class="px-6 py-6">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-[10px] font-bold text-gray-400">Date: {{ optional($tran->borrow_date ?? $tran->reserved_at)->format('M d, Y') }}</span>
                                        @if($tran->due_date)
                                            <span class="text-[10px] font-black text-orange-500 uppercase italic">Due: {{ $tran->due_date->format('M d, Y') }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-6 text-right">
                                    @if($tran instanceof \App\Models\Reservation)
                                    <form action="{{ route('borrower.cancelReservation', $tran->id) }}" method="POST"
                                          onsubmit="return confirm('Cancel this reservation?');">
                                        @csrf @method('DELETE')
                                        <button class="text-[10px] font-black uppercase tracking-widest text-red-400 hover:text-red-600 transition">
                                            Cancel
                                        </button>
                                    </form>
                                    @else
                                        <span class="text-gray-300">N/A</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-10 text-gray-300 font-bold uppercase tracking-widest">No activity found</td></tr>
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
