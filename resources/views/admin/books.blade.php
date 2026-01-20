@extends('components.default')

@section('title', 'Library Hub | LMIS')

@section('content')
<section class="bg-[#fcfcfd] min-h-screen pt-24 pb-12">
    @include('components.admin.topnav')

    <div class="flex flex-col lg:flex-row px-4 lg:px-10 gap-8">

        {{-- Sidebar --}}
        <div class="lg:w-2/12 w-full">
            @include('components.admin.sidebar')
        </div>

        {{-- Main Content --}}
        <div class="lg:w-10/12 w-full space-y-8">

            {{-- 1. ANALYTICS COMMAND CENTER --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="bg-white p-7 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col justify-center group hover:border-blue-500 transition-all">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Total Titles</span>
                    <span class="text-3xl font-black text-gray-900 leading-none">{{ $books->count() }}</span>
                </div>
                <div class="bg-white p-7 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col justify-center group hover:border-blue-500 transition-all">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Stock Volume</span>
                    <span class="text-3xl font-black text-blue-600 leading-none">{{ $books->sum(fn($b) => $b->copies->count()) }}</span>
                </div>
                <div class="bg-white p-7 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col justify-center group hover:border-blue-500 transition-all">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Authors</span>
                    <span class="text-3xl font-black text-gray-900 leading-none">{{ $authors->count() }}</span>
                </div>
                <div class="bg-gray-900 p-7 rounded-[2.5rem] shadow-2xl shadow-gray-200 flex flex-col justify-center">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 mb-1">Publishers</span>
                    <span class="text-3xl font-black text-white leading-none">{{ $publishers->count() }}</span>
                </div>
            </div>

            {{-- 2. UNIFIED SEARCH --}}
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                    <span class="material-icons-outlined text-gray-300 group-focus-within:text-blue-600 transition-colors">search</span>
                </div>
                <input type="text" id="hubSearch" placeholder="Filter through titles, authors, or classifications..."
                    class="block w-full pl-16 pr-6 py-6 border-none rounded-[2rem] bg-white shadow-xl shadow-gray-200/50 focus:ring-4 focus:ring-blue-100 transition-all text-gray-700 font-bold placeholder-gray-300">
            </div>

            {{-- 3. REGISTRY HUB --}}
            <div class="bg-white rounded-[3rem] shadow-2xl shadow-gray-200/40 overflow-hidden border border-gray-50">

                {{-- MODERN NAVIGATION --}}
                <div class="border-b border-gray-100 bg-white/80 backdrop-blur-xl sticky top-0 z-10 px-6 py-4">
                    <ul class="flex flex-wrap text-[10px] font-black uppercase tracking-widest gap-3" id="hubTab">
                        @php
                            $tabs = [
                                ['id' => 'books-content', 'label' => 'Inventory', 'active' => true],
                                ['id' => 'copies-content', 'label' => 'Physical Copies', 'active' => false],
                                ['id' => 'authors-content', 'label' => 'Authors', 'active' => false],
                                ['id' => 'categories-content', 'label' => 'Categories', 'active' => false],
                                ['id' => 'publishers-content', 'label' => 'Publishers', 'active' => false],
                                ['id' => 'suppliers-content', 'label' => 'Suppliers', 'active' => false],
                            ];
                        @endphp
                        @foreach($tabs as $tab)
                        <li class="flex-1 min-w-[120px]">
                            <button class="w-full py-4 px-4 rounded-2xl transition-all duration-300 {{ $tab['active'] ? 'bg-gray-900 text-white shadow-lg' : 'text-gray-400 hover:bg-gray-50' }}"
                                data-target="#{{ $tab['id'] }}">
                                {{ $tab['label'] }}
                            </button>
                        </li>
                        @endforeach
                    </ul>
                </div>

                <div id="hubTabContent" class="p-8">

                    {{-- INVENTORY TAB --}}
                    <div class="tab-pane animate-in fade-in slide-in-from-bottom-4 duration-500" id="books-content">
                        <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
                            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Library Inventory</h2>
                            <button data-modal-target="defaultModal" data-modal-toggle="defaultModal"
                                class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-black uppercase tracking-widest px-10 py-5 rounded-2xl shadow-xl shadow-blue-100 transition-all active:scale-95">
                                + Add New Title
                            </button>
                        </div>

                        <div class="grid grid-cols-1 gap-5">
                            @foreach($books as $book)
                            <div class="bg-white border border-gray-100 p-8 rounded-[2.5rem] hover:shadow-2xl hover:shadow-gray-200/50 transition-all search-item group">
                                <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                                    <div class="flex-1 text-center md:text-left">
                                        <h3 class="text-xl font-black text-gray-900 search-text tracking-tight mb-2 leading-none group-hover:text-blue-600 transition-colors">{{ $book->title }}</h3>
                                        <div class="flex flex-wrap justify-center md:justify-start gap-3 items-center">
                                            <span class="text-xs font-bold text-gray-400 search-text">{{ $book->author?->name ?? 'Anonymous' }}</span>
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-200"></span>
                                            <span class="text-[9px] font-black uppercase bg-indigo-50 text-indigo-600 px-3 py-1 rounded-lg tracking-widest">{{ $book->category?->name ?? 'Unclassified' }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-8 w-full md:w-auto justify-center md:justify-end border-t md:border-none pt-6 md:pt-0">
                                        <div class="text-center">
                                            <span class="block text-[9px] font-black text-gray-300 uppercase tracking-widest mb-1">In-Stock</span>
                                            <span class="text-2xl font-black text-gray-900">{{ $book->copies->count() }}</span>
                                        </div>
                                        <div class="flex gap-3">
                                            <a href="{{ route('books.edit', $book->id) }}" class="px-6 py-3 bg-gray-50 text-gray-900 font-black text-[10px] uppercase rounded-xl hover:bg-blue-600 hover:text-white transition-all">Edit</a>
                                            <button data-id="{{ $book->id }}" class="delete-book-btn px-6 py-3 bg-gray-50 text-red-400 font-black text-[10px] uppercase rounded-xl hover:bg-red-600 hover:text-white transition-all">Delete</button>
                                        </div>
                                    </div>
                                </div>
                                <form id="delete-book-form-{{ $book->id }}" action="{{ route('books.destroy', $book->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- COPIES TAB --}}
                    <div class="hidden tab-pane animate-in fade-in slide-in-from-bottom-4 duration-500" id="copies-content">
                        <div class="flex justify-between items-center mb-10">
                            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Physical Inventory</h2>
                            <a href="{{ route('book-copies.index') }}" class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline">Full Registry &rarr;</a>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach($books->take(20) as $book)
                                @foreach($book->copies as $copy)
                                <div class="p-6 bg-gray-50/50 border border-gray-100 rounded-[2rem] flex items-center justify-between group search-item hover:bg-white hover:shadow-xl transition-all">
                                    <div class="flex items-center gap-5">
                                        <span class="w-14 h-14 flex items-center justify-center bg-white rounded-2xl shadow-sm font-black text-sm text-blue-600 border border-gray-100">#{{ $copy->copy_number }}</span>
                                        <div>
                                            <span class="block font-black text-gray-900 search-text leading-tight">{{ $book->title }}</span>
                                            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $copy->shelf_location }}</span>
                                        </div>
                                    </div>
                                    <span class="px-5 py-2 rounded-full text-[9px] font-black uppercase tracking-widest shadow-sm {{ $copy->status == 'available' ? 'bg-emerald-500 text-white' : 'bg-orange-500 text-white' }}">
                                        {{ $copy->status }}
                                    </span>
                                </div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>

                    {{-- AUTHORS TAB --}}
                    <div class="hidden tab-pane animate-in fade-in slide-in-from-bottom-4 duration-500" id="authors-content">
                         <div class="flex justify-between items-center mb-10">
                            <h2 class="text-3xl font-black text-gray-900 tracking-tight">Authors</h2>
                            <a href="{{ route('authors.index') }}" class="bg-gray-900 text-white px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest active:scale-95 transition-all">Manage Directory</a>
                        </div>
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
                            @foreach($authors as $author)
                            <div class="bg-white border border-gray-100 p-8 rounded-[2.5rem] text-center hover:shadow-2xl transition-all search-item group">
                                <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center mx-auto mb-5 font-black text-2xl text-gray-300 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-inner">
                                    {{ substr($author->name, 0, 1) }}
                                </div>
                                <h4 class="font-black text-gray-900 search-text text-sm leading-tight">{{ $author->name }}</h4>
                                <a href="{{ route('authors.edit', $author->id) }}" class="text-[9px] font-black text-blue-500 uppercase tracking-widest mt-4 inline-block opacity-0 group-hover:opacity-100 transition-all underline">Edit Profile</a>
                            </div>
                            @endforeach
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- MODAL (GLASSMORPISM) --}}
    <div id="defaultModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/60 backdrop-blur-xl">
        <div class="w-full max-w-xl p-6 animate-in zoom-in duration-300">
            <div class="bg-white rounded-[4rem] shadow-2xl p-12 border border-white/20">
                <div class="flex justify-between items-center mb-10">
                    <h3 class="text-4xl font-black text-gray-900 tracking-tighter">New Entry</h3>
                    <button data-modal-toggle="defaultModal" class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-900 transition-all">✕</button>
                </div>
                <form action="{{ route('books.store') }}" method="POST" class="space-y-8">
                    @csrf
                    <div class="space-y-6">
                        <div class="group">
                            <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-4 mb-2 block">Full Title</label>
                            <input type="text" name="title" class="w-full border-none bg-gray-100/50 rounded-3xl p-6 focus:ring-4 focus:ring-blue-100 font-bold transition-all" placeholder="Enter Book Title" required>
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <div class="group">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-4 mb-2 block">ISBN Code</label>
                                <input type="text" name="isbn" class="w-full border-none bg-gray-100/50 rounded-3xl p-6 focus:ring-4 focus:ring-blue-100 font-bold transition-all" placeholder="Optional">
                            </div>
                            <div class="group">
                                <label class="text-[10px] font-black uppercase tracking-widest text-gray-400 ml-4 mb-2 block">Stock Volume</label>
                                <input type="number" name="copies_available" value="1" min="1" class="w-full border-none bg-gray-100/50 rounded-3xl p-6 focus:ring-4 focus:ring-blue-100 font-bold transition-all" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-6">
                            <select name="author_id" class="border-none bg-gray-100/50 rounded-3xl p-6 font-bold focus:ring-4 focus:ring-blue-100" required>
                                <option value="">Select Author</option>
                                @foreach($authors as $author) <option value="{{ $author->id }}">{{ $author->name }}</option> @endforeach
                            </select>
                            <select name="category_id" class="border-none bg-gray-100/50 rounded-3xl p-6 font-bold focus:ring-4 focus:ring-blue-100" required>
                                <option value="">Select Category</option>
                                @foreach($categories as $category) <option value="{{ $category->id }}">{{ $category->name }}</option> @endforeach
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-black py-6 rounded-3xl shadow-2xl shadow-blue-200 transition-all active:scale-95 text-xs uppercase tracking-widest">Authorize & Register</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
@include('components.alerts')
<script>
    // Tab Logic
    document.querySelectorAll('[data-target]').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('[data-target]').forEach(t => {
                t.classList.remove('bg-gray-900', 'text-white', 'shadow-lg');
                t.classList.add('text-gray-400');
            });
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.add('hidden'));

            const target = document.querySelector(this.dataset.target);
            target.classList.remove('hidden');
            this.classList.add('bg-gray-900', 'text-white', 'shadow-lg');
            this.classList.remove('text-gray-400');
            document.getElementById('hubSearch').value = '';
            filterItems('');
        });
    });

    // Search Logic
    const searchInput = document.getElementById('hubSearch');
    searchInput.addEventListener('input', function() {
        filterItems(this.value.toLowerCase());
    });

    function filterItems(query) {
        const activePane = document.querySelector('.tab-pane:not(.hidden)');
        const items = activePane.querySelectorAll('.search-item');
        items.forEach(item => {
            const textElements = item.querySelectorAll('.search-text');
            let match = false;
            textElements.forEach(el => {
                if (el.textContent.toLowerCase().includes(query)) match = true;
            });
            item.style.display = match ? '' : 'none';
        });
    }

    // Delete Logic
    document.querySelectorAll('.delete-book-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            Swal.fire({
                title: 'Confirm Deletion',
                text: "This will remove the title and all related stock copies.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#000',
                confirmButtonText: 'Yes, Delete'
            }).then(res => {
                if (res.isConfirmed) document.getElementById(`delete-book-form-${id}`).submit();
            });
        });
    });
</script>
@endpush
