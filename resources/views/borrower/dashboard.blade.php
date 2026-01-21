@extends('components.default')

@section('title', 'Library Portal | LMIS')

@section('content')
<section class="bg-gray-50/50 min-h-screen pt-24 pb-12">
    @include('components.borrower.topnav')

    <div class="flex flex-col lg:flex-row px-4 lg:px-10 gap-6">
        <div class="w-full space-y-8 mt-6">

            {{-- Welcome Header --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="space-y-1">
                    <h1 class="text-5xl font-black text-gray-900 tracking-tighter">My Library Portal</h1>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">
                        Browse the library collection using the instant search below.
                    </p>
                </div>
            </div>

            {{-- 1. ANALYTICS CARDS --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @php
                    $cards = [
                        ['icon' => 'auto_stories', 'label' => 'Out Now', 'value' => $summary['borrowed'] ?? 0],
                        ['icon' => 'notification_important', 'label' => 'Overdue', 'value' => $summary['overdue'] ?? 0],
                        ['icon' => 'book', 'label' => 'Available', 'value' => $summary['available'] ?? 0],
                        ['icon' => 'bookmark_added', 'label' => 'Waitlisted', 'value' => $summary['reserved'] ?? 0],
                    ];
                @endphp
                @foreach($cards as $card)
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 flex flex-col items-center group relative overflow-hidden">
                    <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4 group-hover:bg-gray-900 transition-colors">
                        <span class="material-icons text-3xl text-gray-300 group-hover:text-white">{{ $card['icon'] }}</span>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">{{ $card['label'] }}</p>
                    <p class="text-4xl font-black text-gray-900">{{ $card['value'] }}</p>
                </div>
                @endforeach
            </div>

            {{-- 2. COLLECTION SEARCH (ADVANCED) --}}
            <div class="bg-white rounded-[3rem] shadow-xl shadow-gray-200/50 overflow-hidden border border-gray-100 p-8" x-data="{ openModal: false, modalBookId: null, modalCopyId: null }">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight">Catalog Explorer</h3>

                    {{-- Updated Instant Search Input --}}
                    <div class="w-full md:w-1/2 relative group">
                        <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-indigo-600 transition-colors">search</span>
                        <input type="text" id="borrowerSearch"
                            placeholder="Search by title, author, or category..."
                            class="w-full pl-12 pr-4 py-4 bg-gray-50 border-none rounded-2xl font-bold focus:ring-2 focus:ring-indigo-100 transition-all">
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left" id="booksTable">
                        <thead class="text-[10px] uppercase bg-gray-50/50 text-gray-400 font-black tracking-widest border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-5">Publication</th>
                                <th class="px-6 py-5">Category</th>
                                <th class="px-6 py-5 text-right">Copies & Reservation</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($books as $book)
                            <tr class="hover:bg-indigo-50/30 transition group search-item">
                                <td class="px-6 py-8">
                                    <p class="font-black text-gray-900 text-lg leading-none search-text">{{ $book->title }}</p>
                                    <p class="text-xs font-bold text-gray-400 mt-2 uppercase tracking-wide">
                                        By <span class="search-text">{{ $book->author->name ?? 'Unknown' }}</span>
                                    </p>
                                </td>
                                <td class="px-6 py-8">
                                    <span class="text-[10px] font-black text-indigo-500 bg-white border border-indigo-100 px-3 py-1.5 rounded-xl uppercase search-text">
                                        {{ $book->category->name ?? 'General' }}
                                    </span>
                                </td>
                                <td class="px-6 py-8">
                                    <div class="flex flex-wrap justify-end gap-2 max-w-[320px] ml-auto overflow-y-auto max-h-[140px] p-2 custom-scrollbar">
                                        @foreach($book->copies as $copy)
                                        <button
                                            @click="openModal=true; modalBookId={{ $book->id }}; modalCopyId={{ $copy->id }}"
                                            class="flex flex-col items-center justify-center min-w-[70px] py-2 rounded-xl border-2 transition-all active:scale-90
                                                {{ $copy->status === 'available'
                                                    ? 'bg-white border-gray-100 text-gray-800 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 shadow-sm'
                                                    : 'bg-gray-100 border-transparent text-gray-300 cursor-not-allowed opacity-50' }}"
                                            {{ $copy->status !== 'available' ? 'disabled' : '' }}>
                                            <span class="text-[8px] font-black uppercase">#{{ $copy->copy_number }}</span>
                                            <span class="text-[9px] font-black">HOLD</span>
                                        </button>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr id="noResults"><td colspan="3" class="px-4 py-24 text-center text-gray-400 font-bold uppercase italic">No books in collection</td></tr>
                            @endforelse

                            {{-- Dynamic JS No Results Placeholder --}}
                            <tr id="jsNoResults" class="hidden">
                                <td colspan="3" class="px-4 py-24 text-center">
                                    <span class="material-icons text-4xl text-gray-200">search_off</span>
                                    <p class="text-gray-400 font-bold uppercase mt-2">No matching books found</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 3. PERSONAL TRANSACTION REGISTRY --}}
            <div class="bg-white rounded-[3rem] shadow-xl shadow-gray-200/40 overflow-hidden border border-gray-100 p-8">
                <h3 class="text-2xl font-black text-gray-800 tracking-tight mb-8">My Transaction Timeline</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-[10px] uppercase bg-gray-50 text-gray-400 font-black tracking-widest">
                            <tr>
                                <th class="px-6 py-4">Loan Item</th>
                                <th class="px-6 py-4 text-center">Status</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($transactions as $tran)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-8">
                                    <p class="font-black text-gray-900 leading-tight">{{ $tran->book->title ?? 'N/A' }}</p>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase mt-1">Copy #{{ $tran->bookCopy->copy_number ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-8 text-center">
                                    <span class="px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $tran->status === 'returned' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-blue-50 text-blue-600 border-blue-100' }}">
                                        {{ $tran->status ?? 'Reserved' }}
                                    </span>
                                </td>
                                <td class="px-6 py-8 text-right">
                                    @if($tran instanceof \App\Models\Reservation)
                                    <form action="{{ route('borrower.cancelReservation', $tran->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button class="text-[10px] font-black uppercase tracking-widest text-red-400 hover:text-red-600">Cancel</button>
                                    </form>
                                    @else
                                    <span class="material-icons-outlined text-gray-200">lock_clock</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="text-center py-12 text-gray-300 font-bold uppercase italic">No history found</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- RESERVATION MODAL --}}
    <div x-show="openModal" x-transition.opacity class="fixed inset-0 bg-gray-900/80 backdrop-blur-md flex items-center justify-center z-[100] p-4" style="display: none;">
        <div @click.away="openModal = false" class="bg-white rounded-[3.5rem] shadow-2xl w-full max-w-lg p-12 border border-white/20">
            <h3 class="text-4xl font-black text-gray-900 tracking-tighter mb-4">Confirm Hold</h3>
            <p class="mb-10 text-gray-400 font-medium leading-relaxed">Confirm your reservation for this book copy.</p>
            <form method="POST" action="{{ route('borrower.reserve') }}" class="flex gap-4">
                @csrf
                <input type="hidden" name="book_id" :value="modalBookId">
                <input type="hidden" name="copy_id" :value="modalCopyId">
                <button type="button" @click="openModal=false" class="flex-1 py-5 rounded-2xl bg-gray-100 text-gray-500 font-black uppercase tracking-widest text-xs">Cancel</button>
                <button type="submit" class="flex-1 py-5 rounded-2xl bg-indigo-600 text-white font-black uppercase tracking-widest text-xs">Confirm</button>
            </form>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('borrowerSearch');
        const jsNoResults = document.getElementById('jsNoResults');

        if (searchInput) {
            searchInput.addEventListener('input', function() {
                const query = this.value.toLowerCase();
                const items = document.querySelectorAll('.search-item');
                let visibleCount = 0;

                items.forEach(item => {
                    const textElements = item.querySelectorAll('.search-text');
                    let match = false;

                    textElements.forEach(el => {
                        if (el.textContent.toLowerCase().includes(query)) {
                            match = true;
                        }
                    });

                    if (match) {
                        item.style.display = '';
                        visibleCount++;
                    } else {
                        item.style.display = 'none';
                    }
                });

                // Handle no results state
                if (visibleCount === 0 && query !== '') {
                    jsNoResults.classList.remove('hidden');
                } else {
                    jsNoResults.classList.add('hidden');
                }
            });
        }
    });
</script>

<script src="//unpkg.com/alpinejs" defer></script>
@endsection
