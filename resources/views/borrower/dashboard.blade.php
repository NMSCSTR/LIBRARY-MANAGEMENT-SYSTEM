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
                        Manage your loans, track due dates, and explore the catalog.
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

            {{-- 2. CATALOG EXPLORER (SEARCH-ONLY) --}}
            <div class="bg-white rounded-[3rem] shadow-xl shadow-gray-200/50 overflow-hidden border border-gray-100 p-8" x-data="{ openModal: false, modalBookId: null, modalCopyId: null }">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                    <h3 class="text-2xl font-black text-gray-900 tracking-tight">Catalog Explorer</h3>
                    <form method="GET" action="{{ route('borrower.dashboard') }}" class="w-full md:w-1/2 relative group">
                        <span class="material-icons absolute left-4 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-indigo-600 transition-colors">search</span>
                        <input type="text" name="search" value="{{ request('search') }}"
                            placeholder="Enter book title, author, or ISBN..."
                            class="w-full pl-12 pr-4 py-4 bg-gray-50 border-none rounded-2xl font-bold focus:ring-2 focus:ring-indigo-100 transition-all">
                    </form>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="text-[10px] uppercase bg-gray-50/50 text-gray-400 font-black tracking-widest border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-5">Book Details</th>
                                <th class="px-6 py-5">Category</th>
                                <th class="px-6 py-5 text-right">Available Copies</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($books as $book)
                            <tr class="hover:bg-indigo-50/30 transition group">
                                <td class="px-6 py-8">
                                    <p class="font-black text-gray-900 text-lg leading-none">{{ $book->title }}</p>
                                    <p class="text-xs font-bold text-gray-400 mt-2 uppercase">By {{ $book->author->name ?? 'Unknown' }}</p>
                                </td>
                                <td class="px-6 py-8">
                                    <span class="text-[10px] font-black text-indigo-500 bg-white border border-indigo-100 px-3 py-1.5 rounded-xl uppercase">{{ $book->category->name ?? 'General' }}</span>
                                </td>
                                <td class="px-6 py-8">
                                    <div class="flex flex-wrap justify-end gap-2 max-w-[320px] ml-auto overflow-y-auto max-h-[140px] p-1 custom-scrollbar">
                                        @foreach($book->copies as $copy)
                                        <button
                                            @click="openModal=true; modalBookId={{ $book->id }}; modalCopyId={{ $copy->id }}"
                                            class="flex flex-col items-center justify-center min-w-[75px] py-2 rounded-xl border-2 transition-all active:scale-90
                                                {{ $copy->status === 'available'
                                                    ? 'bg-white border-gray-100 text-gray-800 hover:bg-emerald-600 hover:text-white hover:border-emerald-600 shadow-sm'
                                                    : 'bg-gray-100 border-transparent text-gray-300 cursor-not-allowed opacity-50' }}"
                                            {{ $copy->status !== 'available' ? 'disabled' : '' }}>
                                            <span class="text-[8px] font-black">#{{ $copy->copy_number }}</span>
                                            <span class="text-[9px] font-black">SELECT</span>
                                        </button>
                                        @endforeach
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="3" class="px-4 py-24 text-center">
                                    <div class="flex flex-col items-center justify-center space-y-4 opacity-40">
                                        <span class="material-icons text-7xl">{{ request('search') ? 'search_off' : 'travel_explore' }}</span>
                                        <div>
                                            <p class="text-gray-900 font-black uppercase tracking-[0.3em] text-sm">
                                                {{ request('search') ? 'No results found' : 'Search Required' }}
                                            </p>
                                            <p class="text-gray-500 text-xs font-bold mt-1">
                                                {{ request('search') ? 'Try using a different title or ISBN.' : 'Type in the search box to browse our collection.' }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($books->hasPages())
                    <div class="mt-8">{{ $books->links() }}</div>
                @endif

                {{-- MODAL Logic same as before --}}
                <div x-show="openModal" class="fixed inset-0 bg-gray-900/80 backdrop-blur-md flex items-center justify-center z-[100] p-4" style="display: none;">
                    <div @click.away="openModal = false" class="bg-white rounded-[3.5rem] p-12 w-full max-w-lg shadow-2xl">
                         <h3 class="text-4xl font-black text-gray-900 tracking-tighter mb-4">Confirm Hold</h3>
                         <p class="mb-10 text-gray-400 font-medium">Hold this book for 24 hours.</p>
                         <form method="POST" action="{{ route('borrower.reserve') }}" class="flex gap-4">
                            @csrf
                            <input type="hidden" name="book_id" :value="modalBookId">
                            <input type="hidden" name="copy_id" :value="modalCopyId">
                            <button type="button" @click="openModal=false" class="flex-1 py-5 rounded-2xl bg-gray-100 text-gray-500 font-black uppercase text-xs">Cancel</button>
                            <button type="submit" class="flex-1 py-5 rounded-2xl bg-indigo-600 text-white font-black uppercase text-xs">Confirm</button>
                         </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 5px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #f1f5f9; border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #e2e8f0; }
</style>
@endsection
