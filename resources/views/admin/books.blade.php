@extends('components.default')

@section('title', 'Library Hub | LMIS')

@section('content')
<section class="bg-[#f8fafc] min-h-screen pt-24 pb-12">
    @include('components.admin.topnav')

    <div class="flex flex-col lg:flex-row px-4 lg:px-10 gap-10">

        {{-- Sidebar --}}
        <div class="lg:w-2/12 w-full">
            @include('components.admin.sidebar')
        </div>

        {{-- Main Content --}}
        <div class="lg:w-10/12 w-full space-y-10">

            {{-- 1. ELEGANT HEADER --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                <div>
                    <h1 class="text-5xl font-black text-slate-900 tracking-tighter italic">The Hub.</h1>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-[0.2em] mt-2">Inventory • Archives • Cataloging</p>
                </div>
                <button data-modal-target="defaultModal" data-modal-toggle="defaultModal"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-[10px] font-black uppercase tracking-[0.15em] px-10 py-5 rounded-full shadow-2xl shadow-indigo-100 transition-all active:scale-95">
                    + Register New Resource
                </button>
            </div>

            {{-- 2. FLOATING SEARCH & TABS --}}
            <div class="bg-white rounded-[3rem] shadow-xl shadow-slate-200/60 p-2 border border-slate-100">
                <div class="flex flex-col md:flex-row items-center gap-2">
                    {{-- Tab Navigation --}}
                    <div class="w-full md:w-auto overflow-x-auto">
                        <ul class="flex p-2 gap-1 bg-slate-50 rounded-[2.5rem]" id="hubTab">
                            @php
                                $tabs = [
                                    ['id' => 'books-content', 'label' => 'Titles', 'active' => true],
                                    ['id' => 'copies-content', 'label' => 'Copies', 'active' => false],
                                    ['id' => 'authors-content', 'label' => 'Authors', 'active' => false],
                                    ['id' => 'categories-content', 'label' => 'Tags', 'active' => false],
                                    ['id' => 'publishers-content', 'label' => 'Press', 'active' => false],
                                ];
                            @endphp
                            @foreach($tabs as $tab)
                            <li>
                                <button class="whitespace-nowrap px-8 py-4 rounded-full text-[10px] font-black uppercase tracking-widest transition-all {{ $tab['active'] ? 'bg-white text-indigo-600 shadow-sm' : 'text-slate-400 hover:text-slate-600' }}"
                                    data-target="#{{ $tab['id'] }}">
                                    {{ $tab['label'] }}
                                </button>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    {{-- Inline Search --}}
                    <div class="flex-1 relative w-full px-4">
                        <span class="material-icons absolute left-8 top-1/2 -translate-y-1/2 text-slate-300">search</span>
                        <input type="text" id="hubSearch" placeholder="Quick find..."
                            class="w-full pl-12 pr-4 py-4 border-none bg-transparent focus:ring-0 text-sm font-bold text-slate-700 placeholder-slate-300">
                    </div>
                </div>
            </div>

            {{-- 3. CONTENT AREA --}}
            <div id="hubTabContent">

                {{-- TITLES GRID --}}
                <div class="tab-pane animate-in fade-in duration-700" id="books-content">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                        @foreach($books as $book)
                        <div class="bg-white p-8 rounded-[3rem] border border-slate-100 hover:border-indigo-200 transition-all group search-item flex flex-col justify-between min-h-[200px]">
                            <div>
                                <div class="flex justify-between items-start mb-4">
                                    <span class="text-[9px] font-black bg-slate-100 text-slate-400 px-3 py-1 rounded-full uppercase tracking-widest">{{ $book->category?->name ?? 'Misc' }}</span>
                                    <span class="text-xs font-black text-indigo-500 italic">Qty: {{ $book->copies->count() }}</span>
                                </div>
                                <h3 class="text-xl font-black text-slate-900 search-text tracking-tight group-hover:text-indigo-600 transition-colors leading-tight">{{ $book->title }}</h3>
                                <p class="text-xs font-bold text-slate-400 search-text mt-2 uppercase tracking-wide">{{ $book->author?->name ?? 'Anonymous' }}</p>
                            </div>
                            <div class="flex gap-2 mt-6 opacity-0 group-hover:opacity-100 transition-all translate-y-2 group-hover:translate-y-0">
                                <a href="{{ route('books.edit', $book->id) }}" class="flex-1 text-center py-3 bg-slate-900 text-white text-[9px] font-black uppercase tracking-widest rounded-2xl">Edit</a>
                                <button data-id="{{ $book->id }}" class="delete-book-btn flex-1 py-3 border border-red-100 text-red-500 text-[9px] font-black uppercase tracking-widest rounded-2xl hover:bg-red-50">Remove</button>
                            </div>
                            <form id="delete-book-form-{{ $book->id }}" action="{{ route('books.destroy', $book->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- COPIES LIST (Clean List Style) --}}
                <div class="hidden tab-pane animate-in fade-in duration-700" id="copies-content">
                    <div class="bg-white rounded-[3rem] border border-slate-100 overflow-hidden">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 border-b border-slate-100">
                                <tr>
                                    <th class="px-10 py-6 text-[10px] font-black uppercase text-slate-400">Ref #</th>
                                    <th class="px-10 py-6 text-[10px] font-black uppercase text-slate-400">Book Title</th>
                                    <th class="px-10 py-6 text-center text-[10px] font-black uppercase text-slate-400">Shelf</th>
                                    <th class="px-10 py-6 text-right text-[10px] font-black uppercase text-slate-400">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @foreach($books->take(15) as $book)
                                    @foreach($book->copies as $copy)
                                    <tr class="search-item group hover:bg-slate-50/50 transition-colors">
                                        <td class="px-10 py-6 font-black text-indigo-500 text-xs search-text">#{{ $copy->copy_number }}</td>
                                        <td class="px-10 py-6">
                                            <span class="font-black text-slate-800 text-sm search-text block leading-none">{{ $book->title }}</span>
                                        </td>
                                        <td class="px-10 py-6 text-center text-xs font-bold text-slate-400 search-text uppercase">{{ $copy->shelf_location }}</td>
                                        <td class="px-10 py-6 text-right">
                                            <span class="inline-block w-3 h-3 rounded-full {{ $copy->status == 'available' ? 'bg-emerald-400' : 'bg-orange-400' }} shadow-sm mr-2"></span>
                                            <span class="text-[10px] font-black uppercase text-slate-700 tracking-widest">{{ $copy->status }}</span>
                                        </td>
                                    </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- AUTHORS GRID (Minimal Circles) --}}
                <div class="hidden tab-pane animate-in fade-in duration-700" id="authors-content">
                    <div class="grid grid-cols-3 md:grid-cols-5 xl:grid-cols-6 gap-8">
                        @foreach($authors as $author)
                        <div class="text-center group search-item">
                            <div class="w-24 h-24 mx-auto bg-white border border-slate-100 rounded-full flex items-center justify-center mb-4 group-hover:scale-110 group-hover:border-indigo-600 transition-all shadow-xl shadow-slate-200/50">
                                <span class="text-2xl font-black text-slate-300 group-hover:text-indigo-600 transition-colors">{{ substr($author->name, 0, 1) }}</span>
                            </div>
                            <h4 class="text-xs font-black text-slate-800 search-text uppercase tracking-tighter">{{ $author->name }}</h4>
                            <a href="{{ route('authors.edit', $author->id) }}" class="text-[9px] font-bold text-indigo-400 opacity-0 group-hover:opacity-100 transition-all italic">Edit Profile</a>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- MODAL (EDITORIAL MINIMALISM) --}}
    <div id="defaultModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/40 backdrop-blur-sm">
        <div class="w-full max-w-lg p-6 animate-in slide-in-from-bottom-8 duration-500">
            <div class="bg-white rounded-[3.5rem] p-12 shadow-2xl">
                <div class="mb-10 text-center">
                    <h3 class="text-4xl font-black text-slate-900 tracking-tighter italic">Registry.</h3>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-2">New Book Entry</p>
                </div>
                <form action="{{ route('books.store') }}" method="POST" class="space-y-6">
                    @csrf
                    <input type="text" name="title" class="w-full border-b-2 border-slate-100 focus:border-indigo-600 bg-transparent py-4 font-black text-xl placeholder-slate-200 transition-colors focus:ring-0 outline-none" placeholder="Book Title" required>
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-2">Quantity</label>
                            <input type="number" name="copies_available" value="1" min="1" class="w-full bg-slate-50 rounded-2xl p-4 font-bold border-none focus:ring-2 focus:ring-indigo-100">
                        </div>
                        <div>
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-2">ISBN</label>
                            <input type="text" name="isbn" class="w-full bg-slate-50 rounded-2xl p-4 font-bold border-none focus:ring-2 focus:ring-indigo-100" placeholder="Optional">
                        </div>
                    </div>
                    <div class="space-y-4">
                        <select name="author_id" class="w-full bg-slate-50 rounded-2xl p-4 font-bold border-none focus:ring-2 focus:ring-indigo-100 appearance-none" required>
                            <option value="">Select Author</option>
                            @foreach($authors as $author) <option value="{{ $author->id }}">{{ $author->name }}</option> @endforeach
                        </select>
                        <select name="category_id" class="w-full bg-slate-50 rounded-2xl p-4 font-bold border-none focus:ring-2 focus:ring-indigo-100 appearance-none" required>
                            <option value="">Select Classification</option>
                            @foreach($categories as $category) <option value="{{ $category->id }}">{{ $category->name }}</option> @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full py-6 bg-slate-900 text-white font-black uppercase tracking-[0.2em] text-[10px] rounded-full hover:bg-indigo-600 transition-all active:scale-95 shadow-xl shadow-slate-200">Finalize Registration</button>
                    <button type="button" data-modal-toggle="defaultModal" class="w-full text-center text-[9px] font-black text-slate-300 uppercase tracking-widest">Discard</button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
    // Tab & Search logic exactly as per original requirements
    document.querySelectorAll('[data-target]').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('[data-target]').forEach(t => {
                t.classList.remove('bg-white', 'text-indigo-600', 'shadow-sm');
                t.classList.add('text-slate-400');
            });
            document.querySelectorAll('.tab-pane').forEach(pane => pane.classList.add('hidden'));

            const target = document.querySelector(this.dataset.target);
            target.classList.remove('hidden');
            this.classList.add('bg-white', 'text-indigo-600', 'shadow-sm');
            this.classList.remove('text-slate-400');
            document.getElementById('hubSearch').value = '';
            filterItems('');
        });
    });

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

    document.querySelectorAll('.delete-book-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            Swal.fire({
                title: 'Confirm Deletion',
                text: "All related physical stock will be cleared.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#000',
                confirmButtonText: 'Delete Forever'
            }).then(res => {
                if (res.isConfirmed) document.getElementById(`delete-book-form-${id}`).submit();
            });
        });
    });
</script>
@endpush
