@extends('components.default')

@section('title', 'Library Portal | LMIS')

@section('content')
<section class="bg-gray-50/50 min-h-screen pt-24 pb-12">
    @include('components.borrower.topnav')

    <div class="flex flex-col lg:flex-row px-4 lg:px-10 gap-6">

        {{-- Main Content --}}
        <div class="w-full space-y-8 mt-6">

            {{-- Welcome Header --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="space-y-1">
                    <h1 class="text-5xl font-black text-gray-900 tracking-tighter">My Library Portal</h1>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">
                        Manage your loans, track due dates, and explore the catalog.
                    </p>
                </div>
                <div class="flex gap-4">
                    <div class="bg-white px-6 py-4 rounded-[1.5rem] shadow-sm border border-gray-100 text-center">
                        <span class="text-[10px] font-black uppercase text-gray-400 block mb-1">Account Standing</span>
                        <span class="text-xs font-black {{ ($summary['overdue'] ?? 0) > 0 ? 'text-red-500' : 'text-emerald-500' }} uppercase italic">
                            {{ ($summary['overdue'] ?? 0) > 0 ? 'Action Required' : 'Good Standing' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- 1. ANALYTICS CARDS --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @php
                    $cards = [
                        ['icon' => 'auto_stories', 'label' => 'Out Now', 'value' => $summary['borrowed'] ?? 0, 'color' => 'blue'],
                        ['icon' => 'notification_important', 'label' => 'Overdue', 'value' => $summary['overdue'] ?? 0, 'color' => 'red'],
                        ['icon' => 'book', 'label' => 'Available', 'value' => $summary['available'] ?? 0, 'color' => 'emerald'],
                        ['icon' => 'bookmark_added', 'label' => 'Waitlisted', 'value' => $summary['reserved'] ?? 0, 'color' => 'indigo'],
                    ];
                @endphp

                @foreach($cards as $card)
                <div class="bg-white rounded-[2.5rem] shadow-sm border border-gray-100 p-8 flex flex-col items-center hover:shadow-2xl transition-all group overflow-hidden relative">
                    <div class="absolute top-0 right-0 p-4 opacity-[0.03] group-hover:scale-150 transition-transform">
                        <span class="material-icons text-8xl">{{ $card['icon'] }}</span>
                    </div>
                    <div class="w-16 h-16 rounded-2xl bg-gray-50 flex items-center justify-center mb-4 group-hover:bg-gray-900 transition-colors">
                        <span class="material-icons text-3xl text-gray-300 group-hover:text-white">{{ $card['icon'] }}</span>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">{{ $card['label'] }}</p>
                    <p class="text-4xl font-black text-gray-900">{{ $card['value'] }}</p>
                </div>
                @endforeach
            </div>

            {{-- 2. COLLECTION SEARCH --}}
            <div class="bg-white rounded-[3rem] shadow-xl shadow-gray-200/50 overflow-hidden border border-gray-100 p-8" x-data="{ openModal: false, modalBookId: null, modalCopyId: null }">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight">Catalog Explorer</h3>

                    <form method="GET" action="{{ route('borrower.dashboard') }}" class="w-full md:w-1/2 relative group">
                        <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-indigo-600 transition-colors">search</span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Search by title, author, or category..."
                            class="w-full pl-12 pr-4 py-4 bg-gray-50 border-none rounded-2xl font-bold focus:ring-2 focus:ring-indigo-100">
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-[10px] uppercase bg-gray-50/50 text-gray-400 font-black tracking-widest border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-5">Publication Details</th>
                                <th class="px-6 py-5">Classification</th>
                                <th class="px-6 py-5 text-right">Inventory & Reservation</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($books as $book)
                            <tr class="hover:bg-indigo-50/30 transition group">
                                <td class="px-6 py-8">
                                    <p class="font-black text-gray-900 text-lg leading-none">{{ $book->title }}</p>
                                    <p class="text-xs font-bold text-gray-400 mt-2 uppercase tracking-wide">By {{ $book->author->name ?? 'Unknown Author' }}</p>
                                </td>
                                <td class="px-6 py-8">
                                    <span class="text-[10px] font-black text-indigo-500 bg-white border border-indigo-100 px-3 py-1.5 rounded-xl uppercase">{{ $book->category->name ?? 'General' }}</span>
                                </td>
                                <td class="px-6 py-8">
                                    <div class="flex justify-end">
                                        @php
                                            // Find the first copy that is actually available
                                            $availableCopy = $book->copies->where('status', 'available')->first();
                                        @endphp

                                        @if($availableCopy)
                                            {{-- Only one button is shown, regardless of how many copies exist --}}
                                            <button
                                                @click="openModal=true; modalBookId={{ $book->id }}; modalCopyId={{ $availableCopy->id }}"
                                                class="flex flex-col items-center justify-center w-32 py-3 rounded-2xl border-2 transition-all active:scale-90 bg-white border-gray-100 text-gray-800 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 shadow-sm">
                                                <span class="text-[9px] font-black uppercase tracking-tighter">Availability: Yes</span>
                                                <span class="text-[10px] font-black">REQUEST HOLD</span>
                                            </button>
                                        @else
                                            {{-- Show a single disabled button if no copies are available --}}
                                            <button
                                                disabled
                                                class="flex flex-col items-center justify-center w-32 py-3 rounded-2xl border-2 bg-gray-100 border-transparent text-gray-300 cursor-not-allowed opacity-50">
                                                <span class="text-[9px] font-black uppercase tracking-tighter">Out of Stock</span>
                                                <span class="text-[10px] font-black">UNAVAILABLE</span>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="3" class="px-4 py-16 text-center text-gray-300 font-bold uppercase tracking-widest">The library shelf is empty for this search</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-8">{{ $books->withQueryString()->links() }}</div>

                {{-- RESERVE CONFIRMATION MODAL --}}
                <div x-show="openModal" x-transition.opacity class="fixed inset-0 bg-gray-900/80 backdrop-blur-md flex items-center justify-center z-[100] p-4" style="display: none;">
                    <div @click.away="openModal = false" class="bg-white rounded-[3.5rem] shadow-2xl w-full max-w-lg p-12 border border-white/20">
                        <div class="w-20 h-20 bg-indigo-50 rounded-3xl flex items-center justify-center mb-8">
                            <span class="material-icons text-4xl text-indigo-600">bookmark_added</span>
                        </div>
                        <h3 class="text-4xl font-black text-gray-900 tracking-tighter mb-4">Request Hold</h3>
                        <p class="mb-10 text-gray-400 font-medium leading-relaxed">
                            A reservation will hold this copy for <span class="text-gray-900 font-bold">24 hours</span>. Please visit the desk to claim your book.
                        </p>

                        <form method="POST" action="{{ route('borrower.reserve') }}" class="flex gap-4">
                            @csrf
                            <input type="hidden" name="book_id" :value="modalBookId">
                            <input type="hidden" name="copy_id" :value="modalCopyId">

                            <button type="button" @click="openModal=false" class="flex-1 py-5 rounded-2xl bg-gray-100 text-gray-500 font-black uppercase tracking-widest text-xs">Cancel</button>
                            <button type="submit" class="flex-1 py-5 rounded-2xl bg-indigo-600 text-white font-black uppercase tracking-widest text-xs shadow-xl shadow-indigo-200">Confirm Hold</button>
                        </form>
                    </div>
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
                                <th class="px-6 py-4">Borrowing Timeline (3-Day Limit)</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-50">
                            @forelse($transactions as $tran)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="px-6 py-8">
                                    <p class="font-black text-gray-900 leading-tight">{{ $tran->book->title }}</p>
                                    <p class="text-[10px] font-bold text-gray-400 uppercase mt-1">Copy #{{ $tran->bookCopy->copy_number ?? '-' }}</p>
                                </td>
                                <td class="px-6 py-8 text-center">
                                    @php
                                        $statusClass = [
                                            'returned' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            'overdue'  => 'bg-red-50 text-red-600 border-red-200 animate-pulse',
                                            'borrowed' => 'bg-blue-50 text-blue-600 border-blue-100'
                                        ];
                                        $curStatus = $tran->status ?? 'Reserved';
                                    @endphp
                                    <span class="px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $statusClass[$curStatus] ?? 'bg-gray-50 text-gray-400 border-gray-200' }}">
                                        {{ $curStatus }}
                                    </span>
                                </td>
                                <td class="px-6 py-8">
                                    <div class="flex flex-col gap-2">
                                        <div class="flex items-center gap-2">
                                            <div class="w-1.5 h-1.5 rounded-full bg-blue-400"></div>
                                            <span class="text-xs font-bold text-gray-600">Start: {{ optional($tran->borrow_date ?? $tran->reserved_at)->format('M d, Y | g:i A') }}</span>
                                        </div>

                                        @if($tran->due_date)
                                            <div class="flex items-center gap-2">
                                                <div class="w-1.5 h-1.5 rounded-full {{ now('Asia/Manila') > $tran->due_date && $tran->status !== 'returned' ? 'bg-red-500 animate-pulse shadow-[0_0_8px_rgba(239,68,68,0.5)]' : 'bg-orange-400' }}"></div>
                                                <span class="text-xs font-black {{ now('Asia/Manila') > $tran->due_date && $tran->status !== 'returned' ? 'text-red-500' : 'text-orange-500' }} italic">
                                                    Due: {{ $tran->due_date->format('M d, Y | g:i A') }}
                                                </span>
                                            </div>
                                        @endif

                                        @if($tran->return_date)
                                            <div class="flex items-center gap-2 mt-1">
                                                <span class="text-[9px] font-black uppercase px-2 py-0.5 rounded-md {{ $tran->return_date > $tran->due_date ? 'bg-red-50 text-red-400 border border-red-100' : 'bg-emerald-50 text-emerald-500 border border-emerald-100' }}">
                                                    {{ $tran->return_date > $tran->due_date ? '⚠️ Returned Late' : '✓ Returned On Time' }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-8 text-right">
                                    @if($tran instanceof \App\Models\Reservation)
                                    <form action="{{ route('borrower.cancelReservation', $tran->id) }}" method="POST" onsubmit="return confirm('Abort this reservation?');">
                                        @csrf @method('DELETE')
                                        <button class="text-[10px] font-black uppercase tracking-widest text-red-400 hover:text-red-600">Cancel</button>
                                    </form>
                                    @else
                                        <span class="material-icons-outlined text-gray-200">lock_clock</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="text-center py-12 text-gray-300 font-bold uppercase tracking-widest italic">No borrowing history on record</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- Alpine.js for modal logic --}}
<script src="//unpkg.com/alpinejs" defer></script>
@endsection
