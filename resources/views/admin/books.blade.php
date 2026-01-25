@extends('components.default')

@section('title', 'Library Hub | LMIS')

@section('content')
<section class="bg-[#f8fafc] min-h-screen pb-24">
    @include('components.admin.topnav')

    <div class="flex flex-col lg:flex-row px-4 lg:px-10 pt-24 gap-8">

        {{-- SIDEBAR --}}
        <div class="lg:w-2/12 w-full">
            <div class="sticky top-28 space-y-4">
                @include('components.admin.sidebar')

                <div class="bg-white p-4 rounded-3xl shadow-sm border border-slate-200 hidden lg:block">
                    <p class="text-[10px] font-black uppercase text-slate-400 px-2 mb-3 tracking-widest">Quick Actions</p>
                    <button data-modal-target="registrationModal" data-modal-toggle="registrationModal" class="w-full flex items-center gap-3 px-4 py-3 text-xs font-bold text-blue-600 bg-blue-50 rounded-2xl hover:bg-blue-600 hover:text-white transition-all">
                        <span class="material-icons-outlined text-sm">add</span> New Registration
                    </button>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="lg:w-10/12 w-full space-y-8">

            {{-- MODERN STATS CARDS --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                @php
                    $stats = [
                        ['label' => 'Total Titles', 'count' => $books->count(), 'color' => 'text-slate-800', 'bg' => 'bg-white'],
                        ['label' => 'Physical Copies', 'count' => $books->sum(fn($b) => $b->copies->count()), 'color' => 'text-blue-600', 'bg' => 'bg-white'],
                        ['label' => 'Active Authors', 'count' => $authors->count(), 'color' => 'text-slate-800', 'bg' => 'bg-white'],
                        ['label' => 'Publishers', 'count' => $publishers->count(), 'color' => 'text-white', 'bg' => 'bg-blue-600'],
                    ];
                @endphp

                @foreach($stats as $stat)
                <div class="{{ $stat['bg'] }} p-6 rounded-[2rem] shadow-sm border border-slate-100 flex flex-col justify-between">
                    <span class="text-[10px] font-black uppercase {{ str_contains($stat['color'], 'white') ? 'text-blue-100' : 'text-slate-400' }} tracking-widest">{{ $stat['label'] }}</span>
                    <span class="text-4xl font-black mt-2 {{ $stat['color'] }}">{{ $stat['count'] }}</span>
                </div>
                @endforeach
            </div>

            {{-- SEARCH BAR --}}
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-7 flex items-center pointer-events-none">
                    <span class="material-icons-outlined text-slate-400 group-focus-within:text-blue-600 transition-colors">search</span>
                </div>
                <input type="text" id="globalHubSearch" placeholder="Search titles, authors, or ISBN..."
                    class="block w-full pl-16 pr-8 py-6 border-none rounded-[2.5rem] bg-white shadow-xl shadow-blue-900/5 focus:ring-4 focus:ring-blue-100 transition-all text-lg font-medium text-slate-700 placeholder:text-slate-300">
            </div>

            {{-- MAIN INVENTORY GRID --}}
            <div class="bg-white rounded-[3rem] shadow-sm border border-slate-100 overflow-hidden">
                <div class="p-8 border-b border-slate-50 flex justify-between items-center bg-white/80 backdrop-blur-md sticky top-0 z-10">
                    <div>
                        <h2 class="text-2xl font-black text-slate-900 tracking-tight">Recent Books</h2>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Inventory Management</p>
                    </div>
                    <a href="{{ route('books.index') }}" class="px-6 py-3 bg-slate-900 text-white text-[10px] font-black uppercase rounded-2xl hover:bg-blue-600 transition-all">Full Catalogue &rarr;</a>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 2xl:grid-cols-3 gap-6">
                    @foreach($books->take(12) as $book)
                    <div class="search-item group p-6 bg-slate-50 rounded-[2rem] border border-transparent hover:border-blue-100 hover:bg-white hover:shadow-2xl hover:shadow-blue-900/10 transition-all duration-300">
                        <div class="flex flex-col h-full">
                            <div class="mb-4">
                                <span class="px-3 py-1 bg-white text-blue-600 rounded-full text-[9px] font-black uppercase border border-blue-50">{{ $book->category?->name ?? 'General' }}</span>
                            </div>
                            <h3 class="text-lg font-black text-slate-800 leading-tight search-text mb-1">{{ $book->title }}</h3>
                            <p class="text-sm font-bold text-slate-400 search-text">{{ $book->author?->name }}</p>

                            <div class="mt-auto pt-6 flex items-center justify-between">
                                <div class="flex -space-x-2">
                                    @foreach($book->copies->take(3) as $copy)
                                        <div class="w-8 h-8 rounded-full border-2 border-white bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-600">{{ $copy->copy_number }}</div>
                                    @endforeach
                                    @if($book->copies->count() > 3)
                                        <div class="w-8 h-8 rounded-full border-2 border-white bg-blue-100 flex items-center justify-center text-[10px] font-bold text-blue-600">+{{ $book->copies->count() - 3 }}</div>
                                    @endif
                                </div>
                                <span class="text-[10px] font-black uppercase text-slate-300 tracking-tighter">{{ $book->copies->count() }} Copies Total</span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- DYNAMIC LISTS (MODAL TRIGGERS) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $grids = [
                        ['id' => 'authors', 'title' => 'Authors', 'icon' => 'person', 'data' => $authors],
                        ['id' => 'categories', 'title' => 'Categories', 'icon' => 'category', 'data' => $categories],
                        ['id' => 'suppliers', 'title' => 'Suppliers', 'icon' => 'local_shipping', 'data' => $suppliers],
                        ['id' => 'publishers', 'title' => 'Publishers', 'icon' => 'business', 'data' => $publishers],
                    ];
                @endphp

                @foreach($grids as $grid)
                <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-slate-100 flex flex-col items-center text-center group hover:border-blue-400 transition-all cursor-pointer"
                     onclick="toggleListModal('{{ $grid['id'] }}')">
                    <div class="w-16 h-16 bg-slate-50 rounded-3xl flex items-center justify-center mb-4 group-hover:bg-blue-600 transition-all">
                        <span class="material-icons-outlined text-slate-400 group-hover:text-white transition-all">{{ $grid['icon'] }}</span>
                    </div>
                    <h4 class="font-black text-slate-800 uppercase text-xs tracking-widest">{{ $grid['title'] }}</h4>
                    <p class="text-2xl font-black text-slate-900 mt-1">{{ count($grid['data']) }}</p>
                    <span class="text-[9px] font-black text-blue-500 mt-4 uppercase group-hover:translate-x-1 transition-transform tracking-widest">View List &rarr;</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</section>

{{-- UNIVERSAL LIST MODAL --}}
<div id="listModal" class="hidden fixed inset-0 z-[60] flex items-center justify-center bg-slate-900/60 backdrop-blur-md p-4">
    <div class="bg-white w-full max-w-2xl rounded-[3rem] shadow-2xl overflow-hidden animate-in zoom-in duration-200">
        <div class="p-8 border-b border-slate-50 flex justify-between items-center">
            <h3 id="listModalTitle" class="text-2xl font-black text-slate-900 tracking-tight">List View</h3>
            <button onclick="toggleListModal()" class="w-10 h-10 bg-slate-50 rounded-full flex items-center justify-center text-slate-400 hover:text-slate-900 transition-colors font-bold">✕</button>
        </div>
        <div id="listModalContent" class="p-8 max-h-[60vh] overflow-y-auto space-y-3 custom-scrollbar">
            {{-- Content injected by JS --}}
        </div>
    </div>
</div>

{{-- Pass Data to JavaScript --}}
<script>
    const modalData = {
        authors: @json($authors),
        categories: @json($categories),
        suppliers: @json($suppliers),
        publishers: @json($publishers)
    };

    function toggleListModal(type = null) {
        const modal = document.getElementById('listModal');
        const container = document.getElementById('listModalContent');
        const title = document.getElementById('listModalTitle');

        if (!type) {
            modal.classList.add('hidden');
            return;
        }

        title.innerText = type.charAt(0).toUpperCase() + type.slice(1);
        container.innerHTML = '';

        modalData[type].forEach(item => {
            const div = document.createElement('div');
            div.className = "flex items-center justify-between p-5 bg-slate-50 rounded-2xl group hover:bg-blue-50 transition-all";
            div.innerHTML = `
                <span class="font-black text-slate-700 group-hover:text-blue-700">${item.name}</span>
                <span class="text-[10px] font-black uppercase text-slate-400 bg-white px-3 py-1 rounded-lg">${item.books_count ?? 0} Books</span>
            `;
            container.appendChild(div);
        });

        modal.classList.remove('hidden');
    }
</script>
@endsection
