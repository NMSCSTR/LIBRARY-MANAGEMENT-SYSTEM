@extends('components.default')

@section('title', 'Library Hub | LMIS')

@section('content')
<section class="bg-[#fcfcfd] min-h-screen pb-20">
    @include('components.admin.topnav')

    <div class="flex flex-col lg:flex-row px-4 lg:px-10 pt-24 gap-8">

        {{-- 1. STICKY SIDEBAR NAVIGATION --}}
        <div class="lg:w-2/12 w-full">
            <div class="sticky top-28 space-y-6">
                @include('components.admin.sidebar')

                <div class="bg-white p-4 rounded-3xl shadow-sm border border-gray-100 hidden lg:block">
                    <p class="text-[10px] font-black uppercase text-gray-400 px-2 mb-3 tracking-widest">Jump To</p>
                    <nav class="flex flex-col gap-1">
                        <a href="#inventory" class="px-4 py-2 text-xs font-bold text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all">📚 Inventory</a>
                        <a href="#copies" class="px-4 py-2 text-xs font-bold text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all">🆔 Individual Copies</a>
                        <a href="#authors" class="px-4 py-2 text-xs font-bold text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all">✍️ Authors</a>
                        <a href="#suppliers" class="px-4 py-2 text-xs font-bold text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all">🚚 Suppliers</a>
                        <a href="#meta" class="px-4 py-2 text-xs font-bold text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all">🏷️ Meta & Pubs</a>
                    </nav>
                </div>
            </div>
        </div>

        {{-- 2. MAIN CONTENT AREA --}}
        <div class="lg:w-10/12 w-full space-y-10">

            {{-- HEADER STATS --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-gray-100">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1">Titles</span>
                    <span class="text-3xl font-black text-gray-800">{{ $books->count() }}</span>
                </div>
                <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-gray-100">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1">Total Copies</span>
                    <span class="text-3xl font-black text-blue-600">{{ $books->sum(fn($b) => $b->copies->count()) }}</span>
                </div>
                <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-gray-100">
                    <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 block mb-1">Authors</span>
                    <span class="text-3xl font-black text-gray-800">{{ $authors->count() }}</span>
                </div>
                <div class="bg-blue-600 p-6 rounded-[2.5rem] shadow-lg shadow-blue-100">
                    <span class="text-[10px] font-black uppercase tracking-widest text-blue-100 block mb-1">Publishers</span>
                    <span class="text-3xl font-black text-white">{{ $publishers->count() }}</span>
                </div>
            </div>

            {{-- GLOBAL SEARCH --}}
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                    <span class="material-icons-outlined text-gray-400 group-focus-within:text-blue-600 transition-colors">search</span>
                </div>
                <input type="text" id="globalHubSearch" placeholder="Search across the entire hub (Titles, Authors, Suppliers, Statuses)..."
                    class="block w-full pl-16 pr-6 py-6 border-none rounded-[2rem] bg-white shadow-xl shadow-gray-200/50 focus:ring-4 focus:ring-blue-100 transition-all text-gray-700 font-bold italic">
            </div>

            {{-- SECTION: INVENTORY --}}
            <div id="inventory" class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden section-container">
                <div class="p-8 border-b border-gray-50 flex justify-between items-center">
                    <h2 class="text-2xl font-black text-gray-900 tracking-tighter">Books Inventory</h2>
                    <button data-modal-target="defaultModal" data-modal-toggle="defaultModal"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-black uppercase tracking-widest px-8 py-4 rounded-2xl shadow-lg shadow-blue-200 transition-all active:scale-95">
                        + Add New Book
                    </button>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[600px] overflow-y-auto">
                    @foreach($books as $book)
                    <div class="group bg-gray-50/50 border border-gray-100 p-6 rounded-[2rem] hover:bg-white hover:shadow-xl transition-all search-item">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="text-lg font-black text-gray-800 search-text leading-tight mb-1">{{ $book->title }}</h3>
                                <p class="text-xs font-bold text-gray-400 search-text">{{ $book->author?->name }} • {{ $book->category?->name }}</p>
                            </div>
                            <div class="text-right">
                                <span class="block text-[10px] font-black text-gray-300 uppercase leading-none">Stock</span>
                                <span class="font-black text-gray-800 text-xl">{{ $book->copies->count() }}</span>
                            </div>
                        </div>
                        <div class="flex gap-2 mt-4 opacity-0 group-hover:opacity-100 transition-all">
                            <a href="{{ route('books.edit', $book->id) }}" class="px-4 py-2 bg-white text-blue-600 text-[10px] font-black uppercase rounded-xl border border-blue-50">Edit</a>
                            <button data-id="{{ $book->id }}" class="delete-book-btn px-4 py-2 bg-white text-red-500 text-[10px] font-black uppercase rounded-xl border border-red-50">Delete</button>
                        </div>
                        <form id="delete-book-form-{{ $book->id }}" action="{{ route('books.destroy', $book->id) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- SECTION: COPIES --}}
            <div id="copies" class="bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden section-container">
                <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-gray-900 text-white">
                    <h2 class="text-2xl font-black tracking-tighter">Individual Copies</h2>
                    <a href="{{ route('book-copies.index') }}" class="text-[10px] font-black uppercase tracking-widest text-blue-400">View Detailed List &rarr;</a>
                </div>
                <div class="p-8 space-y-3 max-h-[500px] overflow-y-auto bg-gray-50/30">
                    @foreach($books->take(20) as $book)
                        @foreach($book->copies as $copy)
                        <div class="p-5 bg-white border border-gray-100 rounded-3xl flex items-center justify-between gap-4 search-item">
                            <div class="flex items-center gap-4">
                                <span class="w-12 h-12 flex items-center justify-center bg-gray-50 rounded-2xl font-black text-xs text-blue-600 search-text">#{{ $copy->copy_number }}</span>
                                <div>
                                    <p class="font-bold text-gray-800 search-text leading-tight">{{ $book->title }}</p>
                                    <p class="text-[10px] font-black text-gray-400 search-text uppercase">Loc: {{ $copy->shelf_location }}</p>
                                </div>
                            </div>
                            <span class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase {{ $copy->status == 'available' ? 'bg-green-500 text-white' : 'bg-orange-400 text-white' }} search-text">
                                {{ $copy->status }}
                            </span>
                        </div>
                        @endforeach
                    @endforeach
                </div>
            </div>

            {{-- TWO COLUMN ROW: AUTHORS & SUPPLIERS --}}
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                {{-- AUTHORS --}}
                <div id="authors" class="bg-white rounded-[3rem] shadow-sm border border-gray-100 p-8 section-container">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-2xl font-black text-gray-900">Authors</h2>
                        <a href="{{ route('authors.index') }}" class="text-xs font-black text-blue-600 uppercase tracking-widest">Full List</a>
                    </div>
                    <div class="grid grid-cols-2 gap-4 max-h-[400px] overflow-y-auto pr-2">
                        @foreach($authors as $author)
                        <div class="p-4 bg-gray-50 rounded-2xl flex items-center gap-4 search-item group">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center font-black text-xs text-gray-400 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                {{ substr($author->name, 0, 1) }}
                            </div>
                            <span class="font-bold text-sm text-gray-700 search-text">{{ $author->name }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- SUPPLIERS --}}
                <div id="suppliers" class="bg-gray-900 rounded-[3rem] shadow-sm p-8 text-white section-container">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-2xl font-black">Suppliers</h2>
                        <a href="{{ route('suppliers.index') }}" class="text-xs font-black text-blue-400 uppercase tracking-widest">Manage All</a>
                    </div>
                    <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2">
                        @foreach($suppliers as $supplier)
                        <div class="p-6 bg-white/5 border border-white/10 rounded-[2rem] flex justify-between items-center search-item hover:bg-white/10 transition-all">
                            <div>
                                <h4 class="font-black search-text leading-none">{{ $supplier->name }}</h4>
                                <p class="text-[10px] text-gray-500 font-bold mt-1 search-text uppercase tracking-widest">{{ $supplier->contact_person ?? 'Main Contact' }}</p>
                            </div>
                            <span class="material-icons-outlined text-gray-600 text-sm">arrow_forward_ios</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- META SECTION: PUBLISHERS & CATEGORIES --}}
            <div id="meta" class="bg-white rounded-[3rem] shadow-sm border border-gray-100 p-10 section-container">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 mb-6 flex items-center gap-2">
                            <span class="w-2 h-6 bg-blue-600 rounded-full"></span> Categories
                        </h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($categories as $category)
                            <span class="px-5 py-2.5 bg-gray-50 border border-gray-100 rounded-2xl text-[10px] font-black text-gray-500 uppercase search-item search-text hover:border-blue-300 transition-all cursor-default">
                                {{ $category->name }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-gray-900 mb-6 flex items-center gap-2">
                            <span class="w-2 h-6 bg-orange-500 rounded-full"></span> Publishers
                        </h3>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($publishers as $pub)
                            <div class="p-4 bg-gray-50 rounded-2xl flex items-center gap-3 search-item group">
                                <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-[10px] font-black">
                                    {{ substr($pub->name, 0, 1) }}
                                </div>
                                <span class="text-xs font-bold text-gray-600 search-text">{{ $pub->name }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

{{-- PRESERVED REGISTRATION MODAL --}}
<div id="defaultModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/40 backdrop-blur-md">
    <div class="w-full max-w-xl p-4 animate-in zoom-in duration-300">
        <div class="bg-white rounded-[3rem] shadow-2xl p-10 border border-gray-100">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-3xl font-black text-gray-900 tracking-tighter">New Registration</h3>
                <button data-modal-toggle="defaultModal" class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-900 transition-colors">✕</button>
            </div>
            <form action="{{ route('books.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-4">
                    <input type="text" name="title" class="w-full border-none bg-gray-100/50 rounded-2xl p-5 focus:ring-2 focus:ring-blue-500 font-bold" placeholder="Entry Title" required>
                    <div class="grid grid-cols-2 gap-4">
                        <input type="text" name="isbn" class="border-none bg-gray-100/50 rounded-2xl p-5 focus:ring-2 focus:ring-blue-500 font-bold" placeholder="ISBN (Optional)">
                        <input type="number" name="copies_available" value="1" min="1" class="border-none bg-gray-100/50 rounded-2xl p-5 focus:ring-2 focus:ring-blue-500 font-bold" required>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <select name="author_id" class="border-none bg-gray-100/50 rounded-2xl p-5 font-bold appearance-none" required>
                            <option value="">Author</option>
                            @foreach($authors as $author) <option value="{{ $author->id }}">{{ $author->name }}</option> @endforeach
                        </select>
                        <select name="category_id" class="border-none bg-gray-100/50 rounded-2xl p-5 font-bold appearance-none" required>
                            <option value="">Category</option>
                            @foreach($categories as $category) <option value="{{ $category->id }}">{{ $category->name }}</option> @endforeach
                        </select>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <select name="publisher_id" class="border-none bg-gray-100/50 rounded-2xl p-5 font-bold appearance-none" required>
                            <option value="">Publisher</option>
                            @foreach($publishers as $pub) <option value="{{ $pub->id }}">{{ $pub->name }}</option> @endforeach
                        </select>
                        <select name="supplier_id" class="border-none bg-gray-100/50 rounded-2xl p-5 font-bold appearance-none" required>
                            <option value="">Supplier</option>
                            @foreach($suppliers as $sup) <option value="{{ $sup->id }}">{{ $sup->name }}</option> @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white font-black py-5 rounded-[2rem] shadow-2xl shadow-gray-200 transition-all active:scale-95 uppercase tracking-widest text-sm">Register Book</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('components.alerts')
<script>
    // GLOBAL UNIFIED HUB SEARCH
    const searchInput = document.getElementById('globalHubSearch');
    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const items = document.querySelectorAll('.search-item');

        items.forEach(item => {
            const textElements = item.querySelectorAll('.search-text');
            let match = false;
            textElements.forEach(el => {
                if (el.textContent.toLowerCase().includes(query)) match = true;
            });

            if (match) {
                item.style.display = '';
                item.style.opacity = '1';
            } else {
                item.style.display = 'none';
                item.style.opacity = '0';
            }
        });

        // Visually fade empty sections
        document.querySelectorAll('.section-container').forEach(section => {
            const hasVisible = Array.from(section.querySelectorAll('.search-item')).some(i => i.style.display !== 'none');
            section.style.opacity = hasVisible ? '1' : '0.4';
        });
    });

    // Delete Confirmation
    document.querySelectorAll('.delete-book-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            Swal.fire({
                title: 'Proceed with Caution',
                text: "Deleting this title will permanently remove all physical copies from the system.",
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
