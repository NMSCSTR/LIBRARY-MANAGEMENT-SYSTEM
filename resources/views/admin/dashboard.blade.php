@extends('components.default')

@section('title', 'Admin Dashboard | LMIS')

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
        <div class="lg:w-10/12 w-full space-y-6">

            {{-- Advanced Search Hub --}}
            <div class="bg-white rounded-[2rem] shadow-sm border border-gray-100 overflow-hidden">
                <form method="GET" action="{{ route('admin.dashboard') }}" id="searchForm">
                    {{-- Primary Search Bar --}}
                    <div class="flex items-center gap-3 px-6 py-4">
                        <span class="material-icons-outlined text-gray-400">search</span>
                        <input
                            type="text"
                            name="search"
                            value="{{ $keyword }}"
                            placeholder="Search titles, ISBN, or authors..."
                            class="w-full border-0 focus:ring-0 text-sm font-medium text-gray-700"
                        >

                        {{-- Toggle Button --}}
                        <button type="button" id="advanceToggle" class="flex items-center gap-2 text-gray-400 hover:text-indigo-600 transition-colors px-3 py-2 rounded-xl hover:bg-indigo-50">
                            <span class="material-icons-outlined text-sm">tune</span>
                            <span class="text-xs font-bold uppercase tracking-wider">Filters</span>
                        </button>

                        <button
                            type="submit"
                            class="bg-indigo-600 text-white px-8 py-3 rounded-2xl text-sm font-bold hover:bg-indigo-700 shadow-lg shadow-indigo-100 transition active:scale-95"
                        >
                            Search
                        </button>
                    </div>

                    {{-- Advanced Filter Panel (Collapsible) --}}
                    <div id="advancePanel" class="hidden border-t border-gray-50 bg-gray-50/50 px-8 py-6 animate-in slide-in-from-top duration-300">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- Category Filter --}}
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Category</label>
                                <select name="category" class="w-full border-gray-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Author Filter --}}
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Author</label>
                                <select name="author" class="w-full border-gray-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                    <option value="">All Authors</option>
                                    @foreach($authors as $author)
                                        <option value="{{ $author->id }}" {{ request('author') == $author->id ? 'selected' : '' }}>{{ $author->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Status Filter --}}
                            <div class="space-y-2">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Availability</label>
                                <div class="flex gap-2">
                                    <select name="status" class="w-full border-gray-200 rounded-xl text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                        <option value="">Any Status</option>
                                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                                        <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                                        <option value="damaged" {{ request('status') == 'damaged' ? 'selected' : '' }}>Damaged</option>
                                    </select>

                                    {{-- Clear Button --}}
                                    <a href="{{ route('admin.dashboard') }}" class="p-3 bg-white border border-gray-200 rounded-xl text-gray-400 hover:text-red-500 transition-colors" title="Clear Filters">
                                        <span class="material-icons-outlined text-sm">filter_alt_off</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Search Results --}}
            @if($keyword || request('category') || request('author') || request('status'))
            <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-black text-gray-800 tracking-tight">
                        Displaying Results
                        @if($keyword) for <span class="text-indigo-600">“{{ $keyword }}”</span> @endif
                    </h3>
                    <span class="text-xs font-bold text-gray-400 bg-gray-50 px-4 py-2 rounded-full border border-gray-100">
                        {{ $books->count() }} matches found
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    @forelse($books as $book)
                    <div class="group border border-gray-50 rounded-[1.5rem] p-6 hover:bg-indigo-50/30 transition-all">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div class="flex-1">
                                <div class="flex items-center gap-3 mb-1">
                                    <h4 class="text-lg font-black text-indigo-950">{{ $book->title }}</h4>
                                    <span class="text-[10px] font-black uppercase px-2 py-0.5 bg-indigo-100 text-indigo-600 rounded">
                                        {{ $book->category->name ?? 'N/A' }}
                                    </span>
                                </div>
                                <p class="text-xs font-bold text-gray-400 tracking-wide uppercase">Author: {{ $book->author->name ?? 'N/A' }} · ISBN: {{ $book->isbn }}</p>
                            </div>

                            <div class="flex -space-x-2">
                                @foreach($book->copies->take(5) as $copy)
                                    <div title="Copy #{{ $copy->copy_number }} ({{ $copy->status }})"
                                         class="w-8 h-8 rounded-full border-2 border-white {{ $copy->status == 'available' ? 'bg-green-400' : 'bg-red-400' }} flex items-center justify-center text-[10px] text-white font-bold">
                                         {{ $loop->iteration }}
                                    </div>
                                @endforeach
                                @if($book->copies->count() > 5)
                                    <div class="w-8 h-8 rounded-full border-2 border-white bg-gray-200 flex items-center justify-center text-[10px] text-gray-600 font-bold">+{{ $book->copies->count() - 5 }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-10">
                        <span class="material-icons-outlined text-5xl text-gray-200 mb-2">find_in_page</span>
                        <p class="text-gray-500 font-medium">No books match your specific criteria.</p>
                    </div>
                    @endforelse
                </div>
            </div>
            @endif

            {{-- Dashboard Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                $cards = [
                    ['label'=>'Total Users','value'=>$totalUsers,'icon'=>'group','color'=>'indigo'],
                    ['label'=>'Total Books','value'=>$totalBooks,'icon'=>'menu_book','color'=>'blue'],
                    ['label'=>'Reservations','value'=>$totalReservations,'icon'=>'event_available','color'=>'emerald'],
                    ['label'=>'Borrows','value'=>$totalBorrows,'icon'=>'assignment_return','color'=>'violet'],
                    ['label'=>'Suppliers','value'=>$totalSuppliers,'icon'=>'storefront','color'=>'rose'],
                ];
                @endphp

                @foreach($cards as $card)
                <div class="bg-white rounded-[2rem] shadow-sm p-8 border border-gray-100 hover:shadow-md transition group">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">{{ $card['label'] }}</p>
                            <h3 class="text-4xl font-black text-gray-900 leading-none">
                                {{ $card['value'] }}
                            </h3>
                        </div>
                        <div class="w-14 h-14 rounded-2xl bg-gray-50 flex items-center justify-center group-hover:bg-{{ $card['color'] }}-50 transition-colors">
                            <span class="material-icons-outlined text-3xl text-gray-300 group-hover:text-{{ $card['color'] }}-500 transition-colors">
                                {{ $card['icon'] }}
                            </span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    const toggleBtn = document.getElementById('advanceToggle');
    const panel = document.getElementById('advancePanel');

    toggleBtn.addEventListener('click', () => {
        panel.classList.toggle('hidden');
        if(!panel.classList.contains('hidden')) {
            toggleBtn.querySelector('.material-icons-outlined').textContent = 'expand_less';
        } else {
            toggleBtn.querySelector('.material-icons-outlined').textContent = 'tune';
        }
    });

    // Keep panel open if filters are active
    window.addEventListener('load', () => {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('category') || urlParams.has('author') || urlParams.has('status')) {
            panel.classList.remove('hidden');
            toggleBtn.querySelector('.material-icons-outlined').textContent = 'expand_less';
        }
    });
</script>
@endpush
