@extends('components.default')

@section('title', 'Library Hub | LMIS')

@section('content')
<section class="bg-[#fcfcfd] min-h-screen pb-24">
    @include('components.admin.topnav')

    <div class="flex flex-col lg:flex-row px-4 lg:px-10 pt-24 gap-8">

        {{-- 1. STICKY SIDEBAR NAVIGATION --}}
        <div class="lg:w-2/12 w-full">
            <div class="sticky top-28 space-y-6">
                @include('components.admin.sidebar')

                <div class="bg-white p-5 rounded-[2rem] shadow-sm border border-gray-100 hidden lg:block">
                    <p class="text-[10px] font-black uppercase text-gray-400 px-2 mb-4 tracking-widest">Navigation</p>
                    <nav class="flex flex-col gap-2">
                        <a href="#inventory" class="px-4 py-2 text-xs font-bold text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all">📚 Books</a>
                        <a href="#copies" class="px-4 py-2 text-xs font-bold text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all">🆔 Copies</a>
                        <a href="#authors" class="px-4 py-2 text-xs font-bold text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all">✍️ Authors</a>
                        <a href="#suppliers" class="px-4 py-2 text-xs font-bold text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all">🚚 Suppliers</a>
                        <a href="#meta" class="px-4 py-2 text-xs font-bold text-gray-600 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all">🏷️ Categories</a>
                    </nav>
                </div>
            </div>
        </div>

        {{-- 2. MAIN HUB CONTENT --}}
        <div class="lg:w-10/12 w-full space-y-12">

            {{-- HEADER STATS --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-gray-100">
                    <span class="text-[10px] font-black uppercase text-gray-400 block mb-1">Titles</span>
                    <span class="text-3xl font-black text-gray-800">{{ $books->count() }}</span>
                </div>
                <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-gray-100">
                    <span class="text-[10px] font-black uppercase text-gray-400 block mb-1">Total Copies</span>
                    <span class="text-3xl font-black text-blue-600">{{ $books->sum(fn($b) => $b->copies->count()) }}</span>
                </div>
                <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-gray-100">
                    <span class="text-[10px] font-black uppercase text-gray-400 block mb-1">Authors</span>
                    <span class="text-3xl font-black text-gray-800">{{ $authors->count() }}</span>
                </div>
                <div class="bg-blue-600 p-6 rounded-[2.5rem] shadow-lg shadow-blue-100">
                    <span class="text-[10px] font-black uppercase text-blue-100 block mb-1">Active Publishers</span>
                    <span class="text-3xl font-black text-white">{{ $publishers->count() }}</span>
                </div>
            </div>

            {{-- GLOBAL SEARCH --}}
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                    <span class="material-icons-outlined text-gray-400 group-focus-within:text-blue-600 transition-colors">search</span>
                </div>
                <input type="text" id="globalHubSearch" placeholder="Filter through everything instantly..."
                    class="block w-full pl-16 pr-6 py-6 border-none rounded-[2rem] bg-white shadow-xl shadow-gray-200/50 focus:ring-4 focus:ring-blue-100 transition-all text-gray-700 font-bold italic">
            </div>

            {{-- INVENTORY --}}
            <div id="inventory" class="section-container bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-white sticky top-0 z-10">
                    <h2 class="text-2xl font-black text-gray-900 tracking-tighter">Books Inventory</h2>
                    <button data-modal-target="defaultModal" data-modal-toggle="defaultModal"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-black uppercase px-8 py-4 rounded-2xl shadow-lg transition-all active:scale-95">
                        + Register New Book
                    </button>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[600px] overflow-y-auto">
                    @foreach($books as $book)
                    <div class="group bg-gray-50/50 border border-gray-100 p-6 rounded-[2rem] hover:bg-white hover:shadow-xl transition-all search-item">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="text-lg font-black text-gray-800 search-text leading-tight mb-1">{{ $book->title }}</h3>
                                <p class="text-xs font-bold text-gray-400 search-text uppercase">{{ $book->author?->name }} • {{ $book->category?->name }}</p>
                            </div>
                            <span class="text-xl font-black text-gray-800">{{ $book->copies->count() }}<small class="text-[10px] text-gray-300 ml-1">QTY</small></span>
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

            {{-- COPIES --}}
            <div id="copies" class="section-container bg-gray-900 rounded-[3rem] shadow-sm p-8 text-white overflow-hidden">
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-2xl font-black tracking-tighter">Individual Copies</h2>
                    <a href="{{ route('book-copies.index') }}" class="text-[10px] font-black uppercase tracking-widest text-blue-400">View Full List &rarr;</a>
                </div>
                <div class="space-y-3 max-h-[500px] overflow-y-auto pr-2">
                    @foreach($books->take(20) as $book)
                        @foreach($book->copies as $copy)
                        <div class="p-5 bg-white/5 border border-white/10 rounded-3xl flex items-center justify-between gap-4 search-item">
                            <div class="flex items-center gap-4">
                                <span class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-xl font-black text-xs text-blue-400 search-text">#{{ $copy->copy_number }}</span>
                                <div>
                                    <p class="font-bold search-text leading-tight">{{ $book->title }}</p>
                                    <p class="text-[10px] font-bold text-gray-500 search-text uppercase">Loc: {{ $copy->shelf_location }}</p>
                                </div>
                            </div>
                            <span class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase {{ $copy->status == 'available' ? 'bg-green-500' : 'bg-orange-400' }} text-white search-text">
                                {{ $copy->status }}
                            </span>
                        </div>
                        @endforeach
                    @endforeach
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                {{-- AUTHORS --}}
                <div id="authors" class="section-container bg-white rounded-[3rem] shadow-sm border border-gray-100 p-8">
                    <h2 class="text-2xl font-black text-gray-900 mb-8">Authors Directory</h2>
                    <div class="grid grid-cols-2 gap-4 max-h-[400px] overflow-y-auto pr-2">
                        @foreach($authors as $author)
                        <div class="p-4 bg-gray-50 rounded-2xl flex items-center gap-4 search-item group">
                            <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center font-black text-xs text-gray-300 group-hover:bg-blue-600 group-hover:text-white transition-all">
                                {{ substr($author->name, 0, 1) }}
                            </div>
                            <span class="font-bold text-sm text-gray-700 search-text">{{ $author->name }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>

                {{-- SUPPLIERS --}}
                <div id="suppliers" class="section-container bg-white rounded-[3rem] shadow-sm border border-gray-100 p-8">
                    <h2 class="text-2xl font-black text-gray-900 mb-8">Suppliers</h2>
                    <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2">
                        @foreach($suppliers as $supplier)
                        <div class="p-6 bg-gray-50 rounded-[2rem] flex justify-between items-center search-item hover:bg-gray-100 transition-all group">
                            <div>
                                <h4 class="font-black text-gray-800 search-text leading-none">{{ $supplier->name }}</h4>
                                <p class="text-[10px] text-gray-400 font-bold mt-2 search-text uppercase tracking-widest">{{ $supplier->contact_person ?? 'Global Provider' }}</p>
                            </div>
                            <span class="material-icons-outlined text-gray-200 group-hover:text-blue-500 transition-colors">arrow_forward</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- CATEGORIES & PUBLISHERS --}}
            <div id="meta" class="section-container bg-white rounded-[3rem] shadow-sm border border-gray-100 p-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 mb-6 flex items-center gap-2">Categories</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($categories as $category)
                            <span class="px-5 py-2.5 bg-gray-50 border border-gray-100 rounded-2xl text-[10px] font-black text-gray-500 uppercase search-item search-text hover:border-blue-300 transition-all cursor-default">
                                {{ $category->name }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-gray-900 mb-6 flex items-center gap-2">Publishers</h3>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($publishers as $pub)
                            <div class="p-4 bg-gray-50 rounded-2xl flex items-center gap-3 search-item group">
                                <div class="w-8 h-8 rounded-lg bg-white text-blue-600 flex items-center justify-center text-[10px] font-black shadow-sm">
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

{{-- SEARCH-OR-CREATE REGISTRATION MODAL --}}
<div id="defaultModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/40 backdrop-blur-md">
    <div class="w-full max-w-xl p-4 animate-in zoom-in duration-300">
        <div class="bg-white rounded-[3.5rem] shadow-2xl p-10 border border-gray-100">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-3xl font-black text-gray-900 tracking-tighter">New Registration</h3>
                <button data-modal-toggle="defaultModal" class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-900 transition-colors">✕</button>
            </div>

            <form action="{{ route('books.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-4">
                    <input type="text" name="title" class="w-full border-none bg-gray-100/50 rounded-2xl p-5 focus:ring-2 focus:ring-blue-500 font-bold" placeholder="Entry Title" required>

                    <div class="grid grid-cols-2 gap-4">
                        <input type="text" name="isbn" class="border-none bg-gray-100/50 rounded-2xl p-5 font-bold" placeholder="ISBN (Optional)">
                        <input type="number" name="copies_available" value="1" min="1" class="border-none bg-gray-100/50 rounded-2xl p-5 font-bold" required>
                    </div>

                    {{-- COMBO BOXES FOR SEARCH-OR-CREATE --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative combo-box" data-type="author">
                            <input type="text" list="author-list" placeholder="Search/Add Author" class="combo-input w-full border-none bg-gray-100/50 rounded-2xl p-5 font-bold focus:ring-2 focus:ring-blue-500 transition-all">
                            <input type="hidden" name="author_id" class="hidden-id" required>
                            <datalist id="author-list">
                                @foreach($authors as $author) <option data-id="{{ $author->id }}" value="{{ $author->name }}"> @endforeach
                            </datalist>
                            <button type="button" class="quick-add-btn hidden absolute right-3 top-3 bg-blue-600 text-white text-[9px] font-black px-3 py-2 rounded-xl">CREATE</button>
                        </div>

                        <div class="relative combo-box" data-type="category">
                            <input type="text" list="category-list" placeholder="Search/Add Category" class="combo-input w-full border-none bg-gray-100/50 rounded-2xl p-5 font-bold focus:ring-2 focus:ring-blue-500 transition-all">
                            <input type="hidden" name="category_id" class="hidden-id" required>
                            <datalist id="category-list">
                                @foreach($categories as $category) <option data-id="{{ $category->id }}" value="{{ $category->name }}"> @endforeach
                            </datalist>
                            <button type="button" class="quick-add-btn hidden absolute right-3 top-3 bg-blue-600 text-white text-[9px] font-black px-3 py-2 rounded-xl">CREATE</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative combo-box" data-type="publisher">
                            <input type="text" list="publisher-list" placeholder="Search/Add Publisher" class="combo-input w-full border-none bg-gray-100/50 rounded-2xl p-5 font-bold focus:ring-2 focus:ring-blue-500 transition-all">
                            <input type="hidden" name="publisher_id" class="hidden-id" required>
                            <datalist id="publisher-list">
                                @foreach($publishers as $pub) <option data-id="{{ $pub->id }}" value="{{ $pub->name }}"> @endforeach
                            </datalist>
                            <button type="button" class="quick-add-btn hidden absolute right-3 top-3 bg-blue-600 text-white text-[9px] font-black px-3 py-2 rounded-xl">CREATE</button>
                        </div>

                        <div class="relative combo-box" data-type="supplier">
                            <input type="text" list="supplier-list" placeholder="Search/Add Supplier" class="combo-input w-full border-none bg-gray-100/50 rounded-2xl p-5 font-bold focus:ring-2 focus:ring-blue-500 transition-all">
                            <input type="hidden" name="supplier_id" class="hidden-id" required>
                            <datalist id="supplier-list">
                                @foreach($suppliers as $sup) <option data-id="{{ $sup->id }}" value="{{ $sup->name }}"> @endforeach
                            </datalist>
                            <button type="button" class="quick-add-btn hidden absolute right-3 top-3 bg-blue-600 text-white text-[9px] font-black px-3 py-2 rounded-xl">CREATE</button>
                        </div>
                    </div>
                </div>
                <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white font-black py-5 rounded-[2.5rem] shadow-2xl transition-all active:scale-95 uppercase tracking-widest text-sm">Complete Registration</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('components.alerts')
<script>
    // 1. GLOBAL HUB SEARCH
    document.getElementById('globalHubSearch').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.search-item').forEach(item => {
            const match = Array.from(item.querySelectorAll('.search-text')).some(t => t.innerText.toLowerCase().includes(query));
            item.style.display = match ? '' : 'none';
        });
        document.querySelectorAll('.section-container').forEach(section => {
            const hasVisible = Array.from(section.querySelectorAll('.search-item')).some(i => i.style.display !== 'none');
            section.style.opacity = hasVisible ? '1' : '0.4';
        });
    });

    // 2. SEARCH-OR-CREATE AJAX LOGIC
    document.querySelectorAll('.combo-box').forEach(box => {
        const input = box.querySelector('.combo-input');
        const hiddenId = box.querySelector('.hidden-id');
        const btn = box.querySelector('.quick-add-btn');
        const list = box.querySelector('datalist');
        const type = box.dataset.type;

        input.addEventListener('input', function() {
            const val = this.value;
            const options = Array.from(list.options);
            const match = options.find(opt => opt.value === val);

            if (match) {
                hiddenId.value = match.dataset.id;
                btn.classList.add('hidden');
            } else {
                hiddenId.value = "";
                btn.classList.toggle('hidden', val.length === 0);
            }
        });

        btn.addEventListener('click', async function() {
            const nameValue = input.value;
            btn.innerText = "...";

            try {
                const response = await fetch(`/admin/${type}s`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ name: nameValue })
                });

                const data = await response.json();

                if (data.id) {
                    const newOption = document.createElement('option');
                    newOption.value = data.name;
                    newOption.dataset.id = data.id;
                    list.appendChild(newOption);

                    hiddenId.value = data.id;
                    btn.classList.add('hidden');
                    btn.innerText = "CREATE";
                    Swal.fire({ icon: 'success', title: 'Added!', toast: true, position: 'top-end', timer: 2000, showConfirmButton: false });
                }
            } catch (e) {
                btn.innerText = "ERR";
            }
        });
    });

    // 3. DELETE CONFIRMATION
    document.querySelectorAll('.delete-book-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            Swal.fire({
                title: 'Proceed with Caution',
                text: "Removing this title permanently deletes all associated copy records.",
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
