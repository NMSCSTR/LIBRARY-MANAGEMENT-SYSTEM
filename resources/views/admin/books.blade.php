@extends('components.default')

@section('title', 'Library Hub | LMIS')

@section('content')
<section class="bg-[#f8f9fa] min-h-screen pb-24">
    @include('components.admin.topnav')

    <div class="flex flex-col lg:flex-row px-4 lg:px-10 pt-24 gap-8">

        {{-- 1. STICKY SIDEBAR NAVIGATION --}}
        <div class="lg:w-2/12 w-full">
            <div class="sticky top-28 space-y-6">
                @include('components.admin.sidebar')

                <div class="bg-white p-5 rounded-[2rem] shadow-sm border border-gray-100 hidden lg:block">
                    <p class="text-[10px] font-black uppercase text-gray-400 px-2 mb-4 tracking-widest">Jump To Section</p>
                    <nav class="flex flex-col gap-2">
                        <a href="#inventory" class="px-4 py-2 text-xs font-bold text-gray-600 hover:bg-blue-600 hover:text-white rounded-xl transition-all">📚 Inventory</a>
                        <a href="#copies" class="px-4 py-2 text-xs font-bold text-gray-600 hover:bg-blue-600 hover:text-white rounded-xl transition-all">🆔 Book Copies</a>
                        <a href="#authors" class="px-4 py-2 text-xs font-bold text-gray-600 hover:bg-blue-600 hover:text-white rounded-xl transition-all">✍️ Authors</a>
                        <a href="#suppliers" class="px-4 py-2 text-xs font-bold text-gray-600 hover:bg-blue-600 hover:text-white rounded-xl transition-all">🚚 Suppliers</a>
                        <a href="#meta" class="px-4 py-2 text-xs font-bold text-gray-600 hover:bg-blue-600 hover:text-white rounded-xl transition-all">🏷️ Categories & Pubs</a>
                    </nav>
                </div>
            </div>
        </div>

        {{-- 2. MAIN CONTENT AREA --}}
        <div class="lg:w-10/12 w-full space-y-12">

            {{-- GLOBAL HUB SEARCH --}}
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                    <span class="material-icons-outlined text-gray-400 group-focus-within:text-blue-600 transition-colors">search</span>
                </div>
                <input type="text" id="globalHubSearch" placeholder="Filter through titles, authors, categories, or status..."
                    class="block w-full pl-16 pr-6 py-6 border-none rounded-[2.5rem] bg-white shadow-xl shadow-gray-200/40 focus:ring-4 focus:ring-blue-100 transition-all text-gray-700 font-bold italic">
            </div>

            {{-- SECTION: INVENTORY --}}
            <div id="inventory" class="section-container bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-white sticky top-0 z-10">
                    <h2 class="text-2xl font-black text-gray-900 tracking-tighter">Books Inventory</h2>
                    <button data-modal-target="registrationModal" data-modal-toggle="registrationModal"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-black uppercase px-8 py-4 rounded-2xl shadow-lg transition-all active:scale-95">
                        + New Registration
                    </button>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[600px] overflow-y-auto">
                    @foreach($books as $book)
                    <div class="search-item group bg-gray-50/50 border border-gray-100 p-6 rounded-[2rem] hover:bg-white hover:shadow-xl transition-all">
                        <div class="flex justify-between items-start">
                            <div>
                                <h3 class="text-lg font-black text-gray-800 search-text leading-tight mb-1">{{ $book->title }}</h3>
                                <p class="text-xs font-bold text-gray-400 search-text uppercase tracking-tight">{{ $book->author?->name }} • {{ $book->category?->name }}</p>
                            </div>
                            <span class="text-xl font-black text-gray-800">{{ $book->copies->count() }}<small class="text-[10px] ml-1">QTY</small></span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- SECTION: BOOK COPIES --}}
            <div id="copies" class="section-container bg-gray-900 rounded-[3rem] shadow-sm p-8 text-white">
                <div class="flex justify-between items-center mb-8 px-4">
                    <h2 class="text-2xl font-black tracking-tighter">Physical Copies</h2>
                    <a href="{{ route('book-copies.index') }}" class="text-[10px] font-black uppercase text-blue-400">Manage Detailed List &rarr;</a>
                </div>
                <div class="space-y-3 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                    @foreach($books->take(15) as $book)
                        @foreach($book->copies as $copy)
                        <div class="p-5 bg-white/5 border border-white/10 rounded-3xl flex items-center justify-between search-item">
                            <div class="flex items-center gap-4">
                                <span class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-xl font-black text-xs text-blue-400 search-text">#{{ $copy->copy_number }}</span>
                                <span class="font-bold search-text">{{ $book->title }}</span>
                            </div>
                            <span class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase {{ $copy->status == 'available' ? 'bg-green-500' : 'bg-orange-500' }} text-white search-text">
                                {{ $copy->status }}
                            </span>
                        </div>
                        @endforeach
                    @endforeach
                </div>
            </div>

            {{-- GRID ROW: AUTHORS & SUPPLIERS --}}
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
                        <div class="p-6 bg-gray-50 rounded-[2rem] flex justify-between items-center search-item hover:bg-gray-100 transition-all">
                            <div>
                                <h4 class="font-black text-gray-800 search-text">{{ $supplier->name }}</h4>
                                <p class="text-[10px] text-gray-400 font-bold mt-1 search-text uppercase tracking-widest">{{ $supplier->contact_person }}</p>
                            </div>
                            <span class="material-icons-outlined text-gray-300">arrow_forward</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- META SECTION --}}
            <div id="meta" class="section-container bg-white rounded-[3rem] shadow-sm border border-gray-100 p-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <div>
                        <h3 class="text-xl font-black text-gray-900 mb-6">Categories</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($categories as $category)
                            <span class="px-5 py-2.5 bg-gray-50 border border-gray-100 rounded-2xl text-[10px] font-black text-gray-500 uppercase search-item search-text">
                                {{ $category->name }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-gray-900 mb-6">Publishers</h3>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($publishers as $pub)
                            <div class="p-4 bg-gray-50 rounded-2xl flex items-center gap-3 search-item group">
                                <div class="w-8 h-8 rounded-lg bg-white text-blue-600 flex items-center justify-center text-[10px] font-black">
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

{{-- REGISTRATION MODAL WITH SEARCH-OR-CREATE --}}
<div id="registrationModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/40 backdrop-blur-md">
    <div class="w-full max-w-xl p-4 animate-in zoom-in duration-300">
        <div class="bg-white rounded-[3.5rem] shadow-2xl p-10 border border-gray-100">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-3xl font-black text-gray-900 tracking-tighter">Fast Registration</h3>
                <button data-modal-toggle="registrationModal" class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-900 transition-colors">✕</button>
            </div>

            <form action="{{ route('books.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-4">
                    <input type="text" name="title" class="w-full border-none bg-gray-100/50 rounded-2xl p-5 font-bold" placeholder="Entry Title" required>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- AUTHOR COMBO --}}
                        <div class="relative combo-box" data-type="author">
                            <input type="text" list="author-list" placeholder="Author" class="combo-input w-full border-none bg-gray-100/50 rounded-2xl p-5 font-bold focus:ring-2 focus:ring-blue-500">
                            <input type="hidden" name="author_id" class="hidden-id" required>
                            <datalist id="author-list">
                                @foreach($authors as $author) <option data-id="{{ $author->id }}" value="{{ $author->name }}"> @endforeach
                            </datalist>
                            <button type="button" class="quick-add-btn hidden absolute right-3 top-3 bg-blue-600 text-white text-[9px] font-black px-3 py-2 rounded-xl">CREATE</button>
                        </div>

                        {{-- CATEGORY COMBO --}}
                        <div class="relative combo-box" data-type="category">
                            <input type="text" list="category-list" placeholder="Category" class="combo-input w-full border-none bg-gray-100/50 rounded-2xl p-5 font-bold focus:ring-2 focus:ring-blue-500">
                            <input type="hidden" name="category_id" class="hidden-id" required>
                            <datalist id="category-list">
                                @foreach($categories as $category) <option data-id="{{ $category->id }}" value="{{ $category->name }}"> @endforeach
                            </datalist>
                            <button type="button" class="quick-add-btn hidden absolute right-3 top-3 bg-blue-600 text-white text-[9px] font-black px-3 py-2 rounded-xl">CREATE</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- PUBLISHER COMBO --}}
                        <div class="relative combo-box" data-type="publisher">
                            <input type="text" list="publisher-list" placeholder="Publisher" class="combo-input w-full border-none bg-gray-100/50 rounded-2xl p-5 font-bold focus:ring-2 focus:ring-blue-500">
                            <input type="hidden" name="publisher_id" class="hidden-id" required>
                            <datalist id="publisher-list">
                                @foreach($publishers as $pub) <option data-id="{{ $pub->id }}" value="{{ $pub->name }}"> @endforeach
                            </datalist>
                            <button type="button" class="quick-add-btn hidden absolute right-3 top-3 bg-blue-600 text-white text-[9px] font-black px-3 py-2 rounded-xl">CREATE</button>
                        </div>

                        {{-- SUPPLIER COMBO --}}
                        <div class="relative combo-box" data-type="supplier">
                            <input type="text" list="supplier-list" placeholder="Supplier" class="combo-input w-full border-none bg-gray-100/50 rounded-2xl p-5 font-bold focus:ring-2 focus:ring-blue-500">
                            <input type="hidden" name="supplier_id" class="hidden-id" required>
                            <datalist id="supplier-list">
                                @foreach($suppliers as $sup) <option data-id="{{ $sup->id }}" value="{{ $sup->name }}"> @endforeach
                            </datalist>
                            <button type="button" class="quick-add-btn hidden absolute right-3 top-3 bg-blue-600 text-white text-[9px] font-black px-3 py-2 rounded-xl">CREATE</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <input type="text" name="isbn" class="border-none bg-gray-100/50 rounded-2xl p-5 font-bold" placeholder="ISBN (Optional)">
                        <input type="number" name="copies_available" value="1" min="1" class="border-none bg-gray-100/50 rounded-2xl p-5 font-bold" required>
                    </div>
                </div>
                <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white font-black py-5 rounded-[2.5rem] shadow-2xl transition-all active:scale-95 uppercase tracking-widest text-sm">Register Book</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // 1. GLOBAL HUB SEARCH
    document.getElementById('globalHubSearch').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.search-item').forEach(item => {
            const text = item.innerText.toLowerCase();
            item.style.display = text.includes(query) ? '' : 'none';
        });
    });

    // 2. SEARCH-OR-CREATE AJAX LOGIC (Irregular Plural Fix Integrated)
    document.querySelectorAll('.combo-box').forEach(box => {
        const input = box.querySelector('.combo-input');
        const hiddenId = box.querySelector('.hidden-id');
        const btn = box.querySelector('.quick-add-btn');
        const list = box.querySelector('datalist');
        const type = box.dataset.type;

        input.addEventListener('input', function() {
            const val = this.value.trim();
            const options = Array.from(list.options);
            const match = options.find(opt => opt.value === val);

            if (match) {
                hiddenId.value = match.getAttribute('data-id');
                btn.classList.add('hidden');
            } else {
                hiddenId.value = "";
                btn.classList.toggle('hidden', val.length === 0);
            }
        });

        btn.addEventListener('click', async function() {
            const nameValue = input.value.trim();
            if (!nameValue) return;

            // FIX: Slug mapping for Laravel Resources
            let slug = type + 's';
            if (type === 'category') slug = 'categories';

            btn.innerText = "...";
            btn.disabled = true;

            try {
                const response = await fetch(`/admin/${slug}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ name: nameValue })
                });

                const data = await response.json();

                if (response.ok && data.id) {
                    const newOpt = document.createElement('option');
                    newOpt.value = data.name;
                    newOpt.setAttribute('data-id', data.id);
                    list.appendChild(newOpt);

                    hiddenId.value = data.id;
                    btn.classList.add('hidden');
                    Swal.fire({ icon: 'success', title: 'New ' + type + ' added', toast: true, position: 'top-end', timer: 2000, showConfirmButton: false });
                } else {
                    const errorMsg = data.errors ? Object.values(data.errors)[0][0] : 'Check input';
                    throw new Error(errorMsg);
                }
            } catch (e) {
                Swal.fire({ icon: 'error', title: 'Error', text: e.message });
                btn.innerText = "CREATE";
            } finally {
                btn.disabled = false;
            }
        });
    });
</script>
@endpush
