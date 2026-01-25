@extends('components.default')

@section('title', 'Library Hub | LMIS')

@section('content')
<section class="bg-[#fcfcfd] min-h-screen pb-24">
    @include('components.admin.topnav')

    <div class="flex flex-col lg:flex-row px-4 lg:px-10 pt-24 gap-8">

        {{-- STICKY SIDEBAR NAVIGATION --}}
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
                        <a href="#meta" class="px-4 py-2 text-xs font-bold text-gray-600 hover:bg-blue-600 hover:text-white rounded-xl transition-all">🏷️ Categories</a>
                    </nav>
                </div>
            </div>
        </div>

        {{-- MAIN HUB CONTENT --}}
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
                <div class="bg-blue-600 p-6 rounded-[2.5rem] shadow-lg shadow-blue-100 text-white">
                    <span class="text-[10px] font-black uppercase text-blue-100 block mb-1">Publishers</span>
                    <span class="text-3xl font-black">{{ $publishers->count() }}</span>
                </div>
            </div>

            {{-- SEARCH & YEAR FILTER --}}
            <div class="flex flex-col md:flex-row gap-4">
                <div class="relative group flex-grow">
                    <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                        <span class="material-icons-outlined text-gray-400 group-focus-within:text-blue-600 transition-colors">search</span>
                    </div>
                    <input type="text" id="globalHubSearch" placeholder="Search titles, authors, categories..."
                        class="block w-full pl-16 pr-6 py-6 border-none rounded-[2.5rem] bg-white shadow-xl shadow-gray-200/40 focus:ring-4 focus:ring-blue-100 transition-all text-gray-700 font-bold italic">
                </div>
                <div class="relative w-full md:w-48 group">
                    <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none">
                        <span class="material-icons-outlined text-gray-400 group-focus-within:text-blue-600 transition-colors">event</span>
                    </div>
                    <input type="number" id="yearFilter" placeholder="Year"
                        class="block w-full pl-14 pr-6 py-6 border-none rounded-[2.5rem] bg-white shadow-xl shadow-gray-200/40 focus:ring-4 focus:ring-blue-100 transition-all text-gray-700 font-bold">
                </div>
            </div>

{{-- SECTION: INVENTORY --}}
<div id="inventory" class="section-container bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
    <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-white sticky top-0 z-10">
        <div>
            <h2 class="text-2xl font-black text-gray-900 tracking-tighter">Books Inventory</h2>
            <p class="text-[10px] font-black uppercase text-blue-400">Total Entries: {{ $books->count() }}</p>
        </div>
        <button onclick="openBookModal()" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-black uppercase px-8 py-4 rounded-2xl shadow-lg transition-all active:scale-95">
            + New Registration
        </button>
    </div>

    <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-6 max-h-[600px] overflow-y-auto" id="bookGrid">
        @foreach($books as $book)
        <div class="search-item group bg-gray-50/50 border border-gray-100 p-6 rounded-[2.5rem] hover:bg-white hover:shadow-xl transition-all" data-year="{{ $book->year_published }}">
            <div class="flex flex-col h-full">
                {{-- TOP ROW: TITLE & EDIT --}}
                <div class="flex justify-between items-start mb-4">
                    <div class="flex-1">
                        <div class="flex items-center gap-3">
                            <h3 class="text-lg font-black text-gray-800 search-text leading-tight">{{ $book->title }}</h3>
                            <button onclick='openBookModal(@json($book))' class="text-gray-300 hover:text-blue-600 transition-colors">
                                <span class="material-icons-outlined text-sm">edit</span>
                            </button>
                        </div>
                        <p class="text-[10px] font-black text-blue-500 uppercase tracking-widest mt-1">
                            {{ $book->category?->name ?? 'Uncategorized' }}
                        </p>
                    </div>
                    <div class="bg-gray-900 text-white px-4 py-2 rounded-2xl text-center min-w-[60px]">
                        <span class="text-lg font-black block leading-none">{{ $book->copies->count() }}</span>
                        <span class="text-[8px] font-bold uppercase opacity-60">Copies</span>
                    </div>
                </div>

                {{-- MIDDLE ROW: PRIMARY DETAILS --}}
                <div class="space-y-3">
                    <div class="flex items-center gap-2 text-gray-600">
                        <span class="material-icons-outlined text-sm text-gray-400">person</span>
                        <span class="text-xs font-bold search-text">{{ $book->author?->name ?? 'Unknown Author' }}</span>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center gap-2 text-gray-500">
                            <span class="material-icons-outlined text-sm text-gray-400">fingerprint</span>
                            <span class="text-[11px] font-bold">ISBN: {{ $book->isbn ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-500">
                            <span class="material-icons-outlined text-sm text-gray-400">event</span>
                            <span class="text-[11px] font-bold">Year: {{ $book->year_published ?? 'N/A' }}</span>
                        </div>
                    </div>
                </div>

                {{-- BOTTOM ROW: PUBLICATION & LOCATION --}}
                <div class="mt-6 pt-4 border-t border-gray-100 grid grid-cols-1 gap-2">
                    <div class="flex items-center justify-between text-[10px]">
                        <span class="font-black uppercase text-gray-400">Location</span>
                        {{-- Pulling shelf_location from the first copy --}}
                        <span class="font-bold text-red-600 uppercase tracking-tighter">
                            📍 {{ $book->copies->first()?->shelf_location ?? 'No Location Set' }}
                        </span>
                    </div>
                    <div class="flex items-center justify-between text-[10px]">
                        <span class="font-black uppercase text-gray-400">Publisher</span>
                        <span class="font-bold text-gray-700 uppercase">{{ $book->publisher?->name ?? 'N/A' }}</span>
                    </div>
                    <div class="flex items-center justify-between text-[10px]">
                        <span class="font-black uppercase text-gray-400">Supplier</span>
                        <span class="font-bold text-blue-600 uppercase">{{ $book->supplier?->name ?? 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

            {{-- SECTION: COPIES --}}
            <div id="copies" class="section-container bg-gray-900 rounded-[3rem] shadow-sm p-8 text-white overflow-hidden">
                <div class="flex justify-between items-center mb-8 px-4">
                    <h2 class="text-2xl font-black tracking-tighter">Physical Copies</h2>
                    <a href="{{ route('book-copies.index') }}" class="text-[10px] font-black uppercase text-blue-400">Manage Detailed List &rarr;</a>
                </div>
                <div class="space-y-3 max-h-[500px] overflow-y-auto pr-2 custom-scrollbar">
                    @foreach($books->take(20) as $book)
                        @foreach($book->copies as $copy)
                        <div class="p-5 bg-white/5 border border-white/10 rounded-3xl flex items-center justify-between search-item" data-year="{{ $book->year_published }}">
                            <div class="flex items-center gap-4">
                                <span class="w-10 h-10 flex items-center justify-center bg-white/10 rounded-xl font-black text-xs text-blue-400">#{{ $copy->copy_number }}</span>
                                <span class="font-bold search-text">{{ $book->title }}</span>
                            </div>
                            <span class="px-4 py-1.5 rounded-xl text-[9px] font-black uppercase {{ $copy->status == 'available' ? 'bg-green-500' : 'bg-orange-500' }} text-white">
                                {{ $copy->status }}
                            </span>
                        </div>
                        @endforeach
                    @endforeach
                </div>
            </div>

            {{-- GRID: AUTHORS & SUPPLIERS --}}
            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                <div id="authors" class="section-container bg-white rounded-[3rem] shadow-sm border border-gray-100 p-8">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-2xl font-black text-gray-900 tracking-tighter leading-none">Authors</h2>
                        <a href="{{ route('authors.index') }}" class="text-[10px] font-black uppercase text-blue-600">Manage List &rarr;</a>
                    </div>
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

                <div id="suppliers" class="section-container bg-white rounded-[3rem] shadow-sm border border-gray-100 p-8">
                    <div class="flex justify-between items-center mb-8">
                        <h2 class="text-2xl font-black text-gray-900 tracking-tighter leading-none">Suppliers</h2>
                        <a href="{{ route('suppliers.index') }}" class="text-[10px] font-black uppercase text-blue-600">Manage List &rarr;</a>
                    </div>
                    <div class="space-y-3 max-h-[400px] overflow-y-auto pr-2">
                        @foreach($suppliers as $supplier)
                        <div class="p-6 bg-gray-50 rounded-[2rem] flex justify-between items-center search-item hover:bg-gray-100 transition-all group">
                            <div>
                                <h4 class="font-black text-gray-800 search-text">{{ $supplier->name }}</h4>
                                <p class="text-[10px] text-gray-400 font-bold mt-1 search-text uppercase tracking-widest">{{ $supplier->contact_person ?? 'Main Provider' }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- META SECTION: CATEGORIES & PUBLISHERS --}}
            <div id="meta" class="section-container bg-white rounded-[3rem] shadow-sm border border-gray-100 p-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <div>
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-black text-gray-900">Categories</h3>
                            <a href="{{ route('categories.index') }}" class="text-[10px] font-black uppercase text-blue-600">Manage &rarr;</a>
                        </div>
                        <div class="flex flex-wrap gap-2">
                            @foreach($categories as $category)
                            <span class="px-5 py-2.5 bg-gray-50 border border-gray-100 rounded-2xl text-[10px] font-black text-gray-500 uppercase search-item search-text">
                                {{ $category->name }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <div class="flex justify-between items-center mb-6">
                            <h3 class="text-xl font-black text-gray-900">Publishers</h3>
                            <a href="{{ route('publishers.index') }}" class="text-[10px] font-black uppercase text-blue-600">Manage &rarr;</a>
                        </div>
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

{{-- MODAL: SMART FORM (CREATE & EDIT) --}}
<div id="bookModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/40 backdrop-blur-md">
    <div class="w-full max-w-xl p-4 animate-in zoom-in duration-300">
        <div class="bg-white rounded-[3.5rem] shadow-2xl p-10 border border-gray-100">
            <div class="flex justify-between items-center mb-8">
                <h3 id="modalTitle" class="text-3xl font-black text-gray-900 tracking-tighter">New Entry</h3>
                <button onclick="closeBookModal()" class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-900 transition-colors">✕</button>
            </div>

            <form id="bookForm" action="{{ route('books.store') }}" method="POST" class="space-y-6">
                @csrf
                <div id="methodContainer"></div>

                <div class="space-y-4">
                    <input type="text" name="title" id="formTitle" class="w-full border-none bg-gray-100/50 rounded-2xl p-5 font-bold focus:ring-2 focus:ring-blue-500" placeholder="Book Title" required>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- AUTHOR --}}
                        <div class="relative combo-box" data-type="author">
                            <input type="text" list="author-list" id="authorInput" placeholder="Author" class="combo-input w-full border-none bg-gray-100/50 rounded-2xl p-5 font-bold focus:ring-2 focus:ring-blue-500">
                            <input type="hidden" name="author_id" id="authorHidden" class="hidden-id" required>
                            <datalist id="author-list">@foreach($authors as $author) <option data-id="{{ $author->id }}" value="{{ $author->name }}"> @endforeach</datalist>
                            <button type="button" class="quick-add-btn hidden absolute right-3 top-3 bg-blue-600 text-white text-[9px] font-black px-3 py-2 rounded-xl">CREATE</button>
                        </div>
                        {{-- CATEGORY --}}
                        <div class="relative combo-box" data-type="category">
                            <input type="text" list="category-list" id="categoryInput" placeholder="Category" class="combo-input w-full border-none bg-gray-100/50 rounded-2xl p-5 font-bold focus:ring-2 focus:ring-blue-500">
                            <input type="hidden" name="category_id" id="categoryHidden" class="hidden-id" required>
                            <datalist id="category-list">@foreach($categories as $category) <option data-id="{{ $category->id }}" value="{{ $category->name }}"> @endforeach</datalist>
                            <button type="button" class="quick-add-btn hidden absolute right-3 top-3 bg-blue-600 text-white text-[9px] font-black px-3 py-2 rounded-xl">CREATE</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- PUBLISHER --}}
                        <div class="relative combo-box" data-type="publisher">
                            <input type="text" list="publisher-list" id="publisherInput" placeholder="Publisher" class="combo-input w-full border-none bg-gray-100/50 rounded-2xl p-5 font-bold focus:ring-2 focus:ring-blue-500">
                            <input type="hidden" name="publisher_id" id="publisherHidden" class="hidden-id" required>
                            <datalist id="publisher-list">@foreach($publishers as $pub) <option data-id="{{ $pub->id }}" value="{{ $pub->name }}"> @endforeach</datalist>
                            <button type="button" class="quick-add-btn hidden absolute right-3 top-3 bg-blue-600 text-white text-[9px] font-black px-3 py-2 rounded-xl">CREATE</button>
                        </div>
                        {{-- SUPPLIER --}}
                        <div class="relative combo-box" data-type="supplier">
                            <input type="text" list="supplier-list" id="supplierInput" placeholder="Supplier" class="combo-input w-full border-none bg-gray-100/50 rounded-2xl p-5 font-bold focus:ring-2 focus:ring-blue-500">
                            <input type="hidden" name="supplier_id" id="supplierHidden" class="hidden-id" required>
                            <datalist id="supplier-list">@foreach($suppliers as $sup) <option data-id="{{ $sup->id }}" value="{{ $sup->name }}"> @endforeach</datalist>
                            <button type="button" class="quick-add-btn hidden absolute right-3 top-3 bg-blue-600 text-white text-[9px] font-black px-3 py-2 rounded-xl">CREATE</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <input type="number" name="year_published" id="formYear" class="border-none bg-gray-100/50 rounded-2xl p-5 font-bold focus:ring-2 focus:ring-blue-500" placeholder="Year Published (YYYY)">
                        <input type="text" name="place_published" id="formPlace" class="border-none bg-gray-100/50 rounded-2xl p-5 font-bold focus:ring-2 focus:ring-blue-500" placeholder="Place of Publication">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <input type="text" name="isbn" id="formIsbn" class="border-none bg-gray-100/50 rounded-2xl p-5 font-bold focus:ring-2 focus:ring-blue-500" placeholder="ISBN (Optional)">
                        <div id="copiesFieldWrapper">
                            <input type="number" name="copies_available" id="formCopies" value="1" min="1" class="w-full border-none bg-gray-100/50 rounded-2xl p-5 font-bold focus:ring-2 focus:ring-blue-500" required>
                        </div>
                    </div>
                </div>
                <button type="submit" id="formSubmitBtn" class="w-full bg-gray-900 hover:bg-black text-white font-black py-5 rounded-[2.5rem] shadow-2xl transition-all active:scale-95 uppercase tracking-widest text-sm">Register Book</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('components.alerts')
<script>
    // --- FILTER LOGIC ---
    const searchInput = document.getElementById('globalHubSearch');
    const yearInput = document.getElementById('yearFilter');
    const items = document.querySelectorAll('.search-item');

    function applyFilters() {
        const query = searchInput.value.toLowerCase();
        const year = yearInput.value;

        items.forEach(item => {
            const textContent = item.innerText.toLowerCase();
            const itemYear = item.dataset.year || "";
            const matchesSearch = textContent.includes(query);
            const matchesYear = (year === "") || (itemYear === year);
            item.style.display = (matchesSearch && matchesYear) ? '' : 'none';
        });
    }
    searchInput.addEventListener('input', applyFilters);
    yearInput.addEventListener('input', applyFilters);

    // --- MODAL CONTROLLER ---
    function openBookModal(book = null) {
        const modal = document.getElementById('bookModal');
        const form = document.getElementById('bookForm');
        const method = document.getElementById('methodContainer');
        const copiesWrapper = document.getElementById('copiesFieldWrapper');

        modal.classList.remove('hidden');

        if (book) {
            document.getElementById('modalTitle').innerText = "Edit Entry";
            document.getElementById('formSubmitBtn').innerText = "Save Changes";
            form.action = `/admin/books/${book.id}`;
            method.innerHTML = `<input type="hidden" name="_method" value="PUT">`;
            copiesWrapper.classList.add('hidden');

            document.getElementById('formTitle').value = book.title;
            document.getElementById('formIsbn').value = book.isbn || '';
            document.getElementById('formYear').value = book.year_published || '';
            document.getElementById('formPlace').value = book.place_published || '';

            setCombo('author', book.author_id, book.author?.name);
            setCombo('category', book.category_id, book.category?.name);
            setCombo('publisher', book.publisher_id, book.publisher?.name);
            setCombo('supplier', book.supplier_id, book.supplier?.name);
        } else {
            document.getElementById('modalTitle').innerText = "New Entry";
            document.getElementById('formSubmitBtn').innerText = "Register Book";
            form.action = "{{ route('books.store') }}";
            method.innerHTML = "";
            copiesWrapper.classList.remove('hidden');
            form.reset();
            document.querySelectorAll('.hidden-id').forEach(el => el.value = "");
        }
    }

    function closeBookModal() { document.getElementById('bookModal').classList.add('hidden'); }

    function setCombo(type, id, name) {
        const box = document.querySelector(`.combo-box[data-type="${type}"]`);
        box.querySelector('.combo-input').value = name || '';
        box.querySelector('.hidden-id').value = id || '';
    }

    // --- QUICK-ADD LOGIC ---
    document.querySelectorAll('.combo-box').forEach(box => {
        const input = box.querySelector('.combo-input');
        const hiddenId = box.querySelector('.hidden-id');
        const btn = box.querySelector('.quick-add-btn');
        const list = box.querySelector('datalist');
        const type = box.dataset.type;

        input.addEventListener('input', function() {
            const val = this.value.trim();
            const match = Array.from(list.options).find(opt => opt.value === val);
            if (match) {
                hiddenId.value = match.getAttribute('data-id');
                btn.classList.add('hidden');
            } else {
                hiddenId.value = "";
                btn.classList.toggle('hidden', val.length === 0);
            }
        });

        btn.addEventListener('click', async function() {
            const val = input.value.trim();
            let slug = type === 'category' ? 'categories' : type + 's';
            btn.innerText = "..."; btn.disabled = true;

            try {
                const res = await fetch(`/admin/${slug}`, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: JSON.stringify({ name: val })
                });
                const data = await res.json();
                if (res.ok) {
                    const opt = document.createElement('option');
                    opt.value = data.name; opt.setAttribute('data-id', data.id);
                    list.appendChild(opt);
                    hiddenId.value = data.id;
                    btn.classList.add('hidden');
                    Swal.fire({ icon: 'success', title: 'Added!', toast: true, position: 'top-end', timer: 1500, showConfirmButton: false });
                }
            } catch (e) { Swal.fire({ icon: 'error', title: 'Action Failed' }); }
            finally { btn.innerText = "CREATE"; btn.disabled = false; }
        });
    });
</script>
@endpush
