@extends('components.default')

@section('title', 'Library Command Center | LMIS')

@section('content')
<section class="bg-gray-50 min-h-screen pb-20">
    @include('components.admin.topnav')

    <div class="flex flex-col lg:flex-row px-4 lg:px-10 pt-24 gap-8">
        {{-- QUICK JUMP SIDEBAR --}}
        <div class="lg:w-2/12 w-full">
            <div class="sticky top-28 space-y-4">
                @include('components.admin.sidebar')
                <div class="bg-white p-4 rounded-3xl shadow-sm border border-gray-100 hidden lg:block">
                    <p class="text-[10px] font-black uppercase text-gray-400 px-2 mb-3">Sections</p>
                    <nav class="flex flex-col gap-1 text-xs font-bold text-gray-500">
                        <a href="#inventory" class="px-4 py-2 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all">📚 Books</a>
                        <a href="#authors" class="px-4 py-2 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all">✍️ Authors</a>
                        <a href="#suppliers" class="px-4 py-2 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all">🚚 Suppliers</a>
                        <a href="#meta" class="px-4 py-2 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all">🏷️ Meta Data</a>
                    </nav>
                </div>
            </div>
        </div>

        {{-- MAIN CONTENT --}}
        <div class="lg:w-10/12 w-full space-y-12">

            {{-- GLOBAL SEARCH --}}
            <div class="relative group">
                <input type="text" id="globalHubSearch" placeholder="Search titles, authors, categories..."
                    class="w-full pl-16 pr-6 py-6 border-none rounded-[2rem] bg-white shadow-xl shadow-gray-200/50 focus:ring-4 focus:ring-blue-100 transition-all font-bold">
                <span class="material-icons-outlined absolute left-6 top-1/2 -translate-y-1/2 text-gray-300 group-focus-within:text-blue-600 transition-colors">search</span>
            </div>

            {{-- INVENTORY SECTION --}}
            <div id="inventory" class="section-container bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex justify-between items-center bg-white/80 backdrop-blur-md sticky top-0 z-10">
                    <h2 class="text-2xl font-black text-gray-900 tracking-tighter">Inventory Control</h2>
                    <button data-modal-target="defaultModal" data-modal-toggle="defaultModal"
                        class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-black uppercase px-8 py-4 rounded-2xl shadow-lg transition-all active:scale-95">
                        + New Registration
                    </button>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-4 max-h-[700px] overflow-y-auto">
                    @foreach($books as $book)
                    <div class="search-item group p-6 bg-gray-50/50 rounded-[2rem] border border-gray-100 hover:bg-white hover:shadow-xl transition-all">
                        <div class="flex justify-between items-start">
                            <div class="flex-1">
                                <h3 class="text-lg font-black text-gray-800 search-text">{{ $book->title }}</h3>
                                <p class="text-xs font-bold text-gray-400 search-text uppercase tracking-tighter">{{ $book->author?->name }} • {{ $book->category?->name }}</p>
                            </div>
                            <span class="px-4 py-1.5 rounded-xl bg-blue-100 text-blue-700 text-[10px] font-black uppercase">{{ $book->copies->count() }} Copies</span>
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

            {{-- AUTHORS SECTION --}}
            <div id="authors" class="section-container bg-white rounded-[3rem] shadow-sm border border-gray-100 p-8">
                <h2 class="text-xl font-black text-gray-900 mb-8">Authors Directory</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4">
                    @foreach($authors as $author)
                    <div class="search-item p-4 bg-gray-50 rounded-2xl flex flex-col items-center text-center group border border-transparent hover:border-blue-200 transition-all">
                        <div class="w-10 h-10 bg-white rounded-xl mb-3 flex items-center justify-center font-black text-xs text-gray-300 group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                            {{ substr($author->name, 0, 1) }}
                        </div>
                        <span class="font-bold text-xs text-gray-700 search-text truncate w-full">{{ $author->name }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- SUPPLIERS SECTION --}}
            <div id="suppliers" class="section-container bg-gray-900 rounded-[3rem] shadow-sm p-10 text-white">
                <h2 class="text-2xl font-black mb-8">Suppliers</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    @foreach($suppliers as $supplier)
                    <div class="search-item p-6 bg-white/5 border border-white/10 rounded-3xl hover:bg-white/10 transition-all">
                        <h4 class="font-black search-text tracking-tight">{{ $supplier->name }}</h4>
                        <p class="text-[10px] font-bold text-blue-400 mt-1 uppercase search-text">{{ $supplier->contact_person }}</p>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</section>

{{-- ADVANCED SEARCH-OR-CREATE MODAL --}}
<div id="defaultModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-md">
    <div class="w-full max-w-xl p-4 animate-in zoom-in duration-300">
        <div class="bg-white rounded-[3.5rem] shadow-2xl p-10 border border-gray-100">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-3xl font-black text-gray-900 tracking-tighter">Fast Registration</h3>
                <button data-modal-toggle="defaultModal" class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-900 transition-all">✕</button>
            </div>

            <form action="{{ route('books.store') }}" method="POST" class="space-y-5">
                @csrf
                <div class="space-y-4">
                    <input type="text" name="title" class="w-full border-none bg-gray-100/50 rounded-2xl p-5 font-bold" placeholder="Entry Title" required>

                    <div class="grid grid-cols-2 gap-4">
                        <input type="text" name="isbn" class="border-none bg-gray-100/50 rounded-2xl p-5 font-bold" placeholder="ISBN">
                        <input type="number" name="copies_available" value="1" min="1" class="border-none bg-gray-100/50 rounded-2xl p-5 font-bold">
                    </div>

                    {{-- SEARCH-OR-CREATE ROW 1 --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative flex flex-col group combo-box" data-type="author">
                            <input type="text" list="author-list" placeholder="Search/Add Author" class="combo-input border-none bg-gray-100/50 rounded-2xl p-5 font-bold focus:ring-2 focus:ring-blue-500 transition-all">
                            <input type="hidden" name="author_id" class="hidden-id" required>
                            <datalist id="author-list">
                                @foreach($authors as $author) <option data-id="{{ $author->id }}" value="{{ $author->name }}"> @endforeach
                            </datalist>
                            <button type="button" class="quick-add-btn hidden absolute right-2 top-2 bg-blue-600 text-white text-[9px] font-black px-3 py-2 rounded-xl uppercase">Create</button>
                        </div>

                        <div class="relative flex flex-col group combo-box" data-type="category">
                            <input type="text" list="category-list" placeholder="Search/Add Category" class="combo-input border-none bg-gray-100/50 rounded-2xl p-5 font-bold focus:ring-2 focus:ring-blue-500 transition-all">
                            <input type="hidden" name="category_id" class="hidden-id" required>
                            <datalist id="category-list">
                                @foreach($categories as $category) <option data-id="{{ $category->id }}" value="{{ $category->name }}"> @endforeach
                            </datalist>
                            <button type="button" class="quick-add-btn hidden absolute right-2 top-2 bg-blue-600 text-white text-[9px] font-black px-3 py-2 rounded-xl uppercase">Create</button>
                        </div>
                    </div>

                    {{-- SEARCH-OR-CREATE ROW 2 --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="relative flex flex-col group combo-box" data-type="publisher">
                            <input type="text" list="publisher-list" placeholder="Search/Add Publisher" class="combo-input border-none bg-gray-100/50 rounded-2xl p-5 font-bold focus:ring-2 focus:ring-blue-500 transition-all">
                            <input type="hidden" name="publisher_id" class="hidden-id" required>
                            <datalist id="publisher-list">
                                @foreach($publishers as $pub) <option data-id="{{ $pub->id }}" value="{{ $pub->name }}"> @endforeach
                            </datalist>
                            <button type="button" class="quick-add-btn hidden absolute right-2 top-2 bg-blue-600 text-white text-[9px] font-black px-3 py-2 rounded-xl uppercase">Create</button>
                        </div>

                        <div class="relative flex flex-col group combo-box" data-type="supplier">
                            <input type="text" list="supplier-list" placeholder="Search/Add Supplier" class="combo-input border-none bg-gray-100/50 rounded-2xl p-5 font-bold focus:ring-2 focus:ring-blue-500 transition-all">
                            <input type="hidden" name="supplier_id" class="hidden-id" required>
                            <datalist id="supplier-list">
                                @foreach($suppliers as $sup) <option data-id="{{ $sup->id }}" value="{{ $sup->name }}"> @endforeach
                            </datalist>
                            <button type="button" class="quick-add-btn hidden absolute right-2 top-2 bg-blue-600 text-white text-[9px] font-black px-3 py-2 rounded-xl uppercase">Create</button>
                        </div>
                    </div>
                </div>
                <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white font-black py-5 rounded-[2rem] shadow-2xl transition-all active:scale-95 uppercase tracking-widest text-sm">Complete Registration</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
@include('components.alerts')
<script>
    // 1. GLOBAL HUB SEARCH (The existing scrolling filter)
    document.getElementById('globalHubSearch').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.search-item').forEach(item => {
            const match = Array.from(item.querySelectorAll('.search-text')).some(t => t.innerText.toLowerCase().includes(query));
            item.style.display = match ? '' : 'none';
        });
    });

    // 2. ADVANCED SEARCH-OR-CREATE LOGIC
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
                    // Update datalist for future use
                    const newOption = document.createElement('option');
                    newOption.value = data.name;
                    newOption.dataset.id = data.id;
                    list.appendChild(newOption);

                    // Auto-select the newly created item
                    hiddenId.value = data.id;
                    btn.classList.add('hidden');
                    btn.innerText = "Create";

                    Swal.fire({ icon: 'success', title: 'New ' + type + ' added', toast: true, position: 'top-end', timer: 2000, showConfirmButton: false });
                }
            } catch (e) {
                console.error("AJAX Error:", e);
                btn.innerText = "Error";
            }
        });
    });
</script>
@endpush
