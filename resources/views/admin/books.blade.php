@extends('components.default')

@section('title', 'Library Hub | LMIS')

@section('content')
<section class="bg-[#fcfcfd] min-h-screen pb-24">
    @include('components.admin.topnav')

    <div class="flex flex-col lg:flex-row px-4 lg:px-10 pt-24 gap-8">
        {{-- Sidebar Navigation --}}
        <div class="lg:w-2/12 w-full">
            <div class="sticky top-28 space-y-4">
                @include('components.admin.sidebar')
                <div class="bg-white p-5 rounded-[2rem] shadow-sm border border-gray-100 hidden lg:block">
                    <p class="text-[10px] font-black uppercase text-gray-400 px-2 mb-3 tracking-widest">Jump To</p>
                    <nav class="flex flex-col gap-1 text-xs font-bold text-gray-500">
                        <a href="#inventory" class="px-4 py-2 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all">📚 Books</a>
                        <a href="#authors" class="px-4 py-2 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all">✍️ Authors</a>
                        <a href="#suppliers" class="px-4 py-2 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all">🚚 Suppliers</a>
                        <a href="#meta" class="px-4 py-2 hover:bg-blue-50 hover:text-blue-600 rounded-xl transition-all">🏷️ Meta & Pubs</a>
                    </nav>
                </div>
            </div>
        </div>

        {{-- Main Hub Content --}}
        <div class="lg:w-10/12 w-full space-y-12">
            {{-- Global Hub Search --}}
            <div class="relative group">
                <input type="text" id="globalHubSearch" placeholder="Type to search titles, authors, or categories..."
                    class="block w-full pl-6 pr-6 py-6 border-none rounded-[2rem] bg-white shadow-xl shadow-gray-200/50 focus:ring-4 focus:ring-blue-100 transition-all text-gray-700 font-bold italic">
            </div>

            {{-- 1. Inventory Section --}}
            <div id="inventory" class="section-container bg-white rounded-[3rem] shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-8 border-b border-gray-50 flex justify-between items-center sticky top-0 bg-white z-10">
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
                                <h3 class="text-lg font-black text-gray-800 search-text leading-tight">{{ $book->title }}</h3>
                                <p class="text-xs font-bold text-gray-400 search-text uppercase">{{ $book->author?->name }} • {{ $book->category?->name }}</p>
                            </div>
                            <span class="font-black text-gray-800 text-xl">{{ $book->copies->count() }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- 2. Authors Section --}}
            <div id="authors" class="section-container bg-white rounded-[3rem] shadow-sm border border-gray-100 p-8">
                <h2 class="text-xl font-black text-gray-900 mb-8">Authors Directory</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($authors as $author)
                    <div class="search-item p-4 bg-gray-50 rounded-2xl text-center group border border-transparent hover:border-blue-200 transition-all">
                        <span class="font-bold text-sm text-gray-700 search-text block">{{ $author->name }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Additional sections for Suppliers, Publishers follow the same pattern... --}}
        </div>
    </div>
</section>

{{-- SEARCH-OR-CREATE REGISTRATION MODAL --}}
<div id="registrationModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/40 backdrop-blur-md">
    <div class="w-full max-w-xl p-4 animate-in zoom-in duration-300">
        <div class="bg-white rounded-[3.5rem] shadow-2xl p-10 border border-gray-100">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-3xl font-black text-gray-900 tracking-tighter">Fast Entry</h3>
                <button data-modal-toggle="registrationModal" class="w-10 h-10 bg-gray-50 rounded-full flex items-center justify-center text-gray-400 hover:text-gray-900 transition-colors">✕</button>
            </div>

            <form action="{{ route('books.store') }}" method="POST" class="space-y-6" id="mainRegistrationForm">
                @csrf
                <div class="space-y-4">
                    <input type="text" name="title" class="w-full border-none bg-gray-100/50 rounded-2xl p-5 font-bold" placeholder="Book Title" required>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Author Combo --}}
                        <div class="relative combo-box" data-type="author">
                            <input type="text" list="author-list" placeholder="Author" class="combo-input w-full border-none bg-gray-100/50 rounded-2xl p-5 font-bold focus:ring-2 focus:ring-blue-500">
                            <input type="hidden" name="author_id" class="hidden-id" required>
                            <datalist id="author-list">
                                @foreach($authors as $author) <option data-id="{{ $author->id }}" value="{{ $author->name }}"> @endforeach
                            </datalist>
                            <button type="button" class="quick-add-btn hidden absolute right-3 top-4 bg-blue-600 text-white text-[9px] font-black px-3 py-2 rounded-xl">CREATE</button>
                        </div>

                        {{-- Category Combo --}}
                        <div class="relative combo-box" data-type="category">
                            <input type="text" list="category-list" placeholder="Category" class="combo-input w-full border-none bg-gray-100/50 rounded-2xl p-5 font-bold focus:ring-2 focus:ring-blue-500">
                            <input type="hidden" name="category_id" class="hidden-id" required>
                            <datalist id="category-list">
                                @foreach($categories as $category) <option data-id="{{ $category->id }}" value="{{ $category->name }}"> @endforeach
                            </datalist>
                            <button type="button" class="quick-add-btn hidden absolute right-3 top-4 bg-blue-600 text-white text-[9px] font-black px-3 py-2 rounded-xl">CREATE</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        {{-- Publisher Combo --}}
                        <div class="relative combo-box" data-type="publisher">
                            <input type="text" list="publisher-list" placeholder="Publisher" class="combo-input w-full border-none bg-gray-100/50 rounded-2xl p-5 font-bold focus:ring-2 focus:ring-blue-500">
                            <input type="hidden" name="publisher_id" class="hidden-id" required>
                            <datalist id="publisher-list">
                                @foreach($publishers as $pub) <option data-id="{{ $pub->id }}" value="{{ $pub->name }}"> @endforeach
                            </datalist>
                            <button type="button" class="quick-add-btn hidden absolute right-3 top-4 bg-blue-600 text-white text-[9px] font-black px-3 py-2 rounded-xl">CREATE</button>
                        </div>

                        {{-- Supplier Combo --}}
                        <div class="relative combo-box" data-type="supplier">
                            <input type="text" list="supplier-list" placeholder="Supplier" class="combo-input w-full border-none bg-gray-100/50 rounded-2xl p-5 font-bold focus:ring-2 focus:ring-blue-500">
                            <input type="hidden" name="supplier_id" class="hidden-id" required>
                            <datalist id="supplier-list">
                                @foreach($suppliers as $sup) <option data-id="{{ $sup->id }}" value="{{ $sup->name }}"> @endforeach
                            </datalist>
                            <button type="button" class="quick-add-btn hidden absolute right-3 top-4 bg-blue-600 text-white text-[9px] font-black px-3 py-2 rounded-xl">CREATE</button>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <input type="text" name="isbn" class="border-none bg-gray-100/50 rounded-2xl p-5 font-bold" placeholder="ISBN (Optional)">
                        <input type="number" name="copies_available" value="1" min="1" class="border-none bg-gray-100/50 rounded-2xl p-5 font-bold" required>
                    </div>
                </div>
                <button type="submit" class="w-full bg-gray-900 hover:bg-black text-white font-black py-5 rounded-[2.5rem] shadow-2xl transition-all active:scale-95 uppercase tracking-widest text-sm">Complete Registration</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // 1. GLOBAL HUB SEARCH (Live Filtering)
    document.getElementById('globalHubSearch').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.search-item').forEach(item => {
            const text = item.innerText.toLowerCase();
            item.style.display = text.includes(query) ? '' : 'none';
        });

        // Visual indicator for empty sections
        document.querySelectorAll('.section-container').forEach(section => {
            const hasVisible = Array.from(section.querySelectorAll('.search-item')).some(i => i.style.display !== 'none');
            section.style.opacity = hasVisible ? '1' : '0.4';
        });
    });

    // 2. ADVANCED SEARCH-OR-CREATE AJAX LOGIC
    document.querySelectorAll('.combo-box').forEach(box => {
        const input = box.querySelector('.combo-input');
        const hiddenId = box.querySelector('.hidden-id');
        const btn = box.querySelector('.quick-add-btn');
        const list = box.querySelector('datalist');
        const type = box.dataset.type;

        // Logic to detect if typed text matches an existing item
        input.addEventListener('input', function() {
            const val = this.value.trim();
            const options = Array.from(list.options);
            const match = options.find(opt => opt.value === val);

            if (match) {
                hiddenId.value = match.getAttribute('data-id');
                btn.classList.add('hidden');
            } else {
                hiddenId.value = ""; // Clear ID so validation fails if they don't click CREATE
                btn.classList.toggle('hidden', val.length === 0);
            }
        });

        // The AJAX "Quick Add" Execution
        btn.addEventListener('click', async function() {
            const nameValue = input.value.trim();
            if (!nameValue) return;

            // UI State: Loading
            btn.innerText = "...";
            btn.disabled = true;

            try {
                const response = await fetch(`/admin/${type}s`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json', // Critical for receiving JSON errors
                        'X-Requested-With': 'XMLHttpRequest', // Critical for $request->ajax()
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ name: nameValue })
                });

                const data = await response.json();

                if (response.status === 201 || response.status === 200) {
                    // Success: Update the local datalist
                    const newOption = document.createElement('option');
                    newOption.value = data.name;
                    newOption.setAttribute('data-id', data.id);
                    list.appendChild(newOption);

                    // Auto-select the new item
                    hiddenId.value = data.id;
                    btn.classList.add('hidden');

                    Swal.fire({
                        icon: 'success',
                        title: `${type.charAt(0).toUpperCase() + type.slice(1)} Saved`,
                        toast: true,
                        position: 'top-end',
                        timer: 2500,
                        showConfirmButton: false
                    });
                } else {
                    // Handle Validation Errors (e.g., name already exists)
                    const errorMsg = data.errors ? Object.values(data.errors)[0][0] : data.message;
                    throw new Error(errorMsg || 'Failed to save');
                }
            } catch (e) {
                Swal.fire({ icon: 'error', title: 'Action Failed', text: e.message });
                btn.innerText = "RETRY";
            } finally {
                btn.disabled = false;
                if (btn.innerText === "...") btn.innerText = "CREATE";
            }
        });
    });

    // 3. DELETE CONFIRMATION (SweetAlert2)
    document.querySelectorAll('.delete-book-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            Swal.fire({
                title: 'Are you sure?',
                text: "Removing this title will delete all associated physical copies.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#111827',
                cancelButtonColor: '#ef4444',
                confirmButtonText: 'Yes, delete it'
            }).then(res => {
                if (res.isConfirmed) document.getElementById(`delete-book-form-${id}`).submit();
            });
        });
    });
</script>
@endpush
