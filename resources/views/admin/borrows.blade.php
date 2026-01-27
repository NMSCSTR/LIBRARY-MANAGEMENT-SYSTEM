@extends('components.default')

@section('title', 'Borrows | LMIS')

@section('content')
<section class="bg-gray-50 min-h-screen pt-24">
    @include('components.admin.topnav')

    <div class="flex flex-col lg:flex-row px-4 lg:px-10 pb-10 gap-6">
        <div class="lg:w-2/12 w-full">
            @include('components.admin.sidebar')
        </div>

        <div class="lg:w-10/12 w-full space-y-6">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight">Circulation Registry</h1>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Manage book loans and returns</p>
                </div>
                <button data-modal-target="createBorrowModal" data-modal-toggle="createBorrowModal"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-black px-8 py-4 rounded-[2rem] shadow-xl shadow-blue-100 transition-all active:scale-95 flex items-center gap-3">
                    <span class="text-xl">+</span> New Loan
                </button>
            </div>

            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/40 overflow-hidden border border-gray-100 p-6">
                <div class="overflow-x-auto">
                    <table id="datatable" class="w-full text-sm text-left text-gray-600">
                        <thead class="text-[10px] uppercase bg-gray-50/50 text-gray-400 font-black tracking-widest border-b border-gray-100">
                            <tr>
                                <th class="px-8 py-5">Borrower</th>
                                <th class="px-8 py-5">Book Details</th>
                                <th class="px-8 py-5">Loan Timeline (Manila Time)</th>
                                <th class="px-8 py-5 text-center">Status</th>
                                <th class="px-8 py-5 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($borrows as $borrow)
                            <tr class="hover:bg-blue-50/20 transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-xs">
                                            {{ substr($borrow->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-black text-gray-900 leading-tight">{{ $borrow->user->name }}</p>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">{{ $borrow->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="font-black text-gray-800 leading-tight">{{ $borrow->book->title }}</p>
                                    <p class="text-[10px] font-bold text-blue-500 uppercase mt-1">Copy #{{ $borrow->bookCopy->copy_number ?? 'N/A' }}</p>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col gap-1.5">
                                        <div class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span>
                                            <span class="text-xs font-bold text-gray-700">Out: {{ $borrow->borrow_date->timezone('Asia/Manila')->format('M d, Y | g:i A') }}</span>
                                        </div>
                                        @if($borrow->return_date)
                                            <div class="flex items-center gap-2">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                                                <span class="text-xs font-bold text-green-600">In: {{ $borrow->return_date->timezone('Asia/Manila')->format('M d, Y | g:i A') }}</span>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-2">
                                                <span class="w-1.5 h-1.5 rounded-full bg-orange-400 animate-pulse"></span>
                                                <span class="text-xs font-bold text-orange-500 italic">Due: {{ $borrow->due_date->timezone('Asia/Manila')->format('M d, Y | g:i A') }}</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    @php
                                        $statusStyles = [
                                            'returned' => 'bg-green-100 text-green-700 border-green-200',
                                            'overdue' => 'bg-red-100 text-red-600 border-red-200',
                                            'borrowed' => 'bg-blue-100 text-blue-700 border-blue-200'
                                        ];
                                        $currentStyle = $statusStyles[$borrow->status] ?? 'bg-gray-100 text-gray-600';
                                    @endphp
                                    <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $currentStyle }}">
                                        {{ $borrow->status }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    @if($borrow->status !== 'returned')
                                        <button data-id="{{ $borrow->id }}"
                                            data-copy="{{ $borrow->bookCopy?->copy_number ?? 'N/A' }}"
                                            data-user="{{ $borrow->user->name }}" data-modal-target="returnBorrowModal"
                                            data-modal-toggle="returnBorrowModal"
                                            class="return-borrow-btn bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest px-5 py-3 rounded-2xl shadow-xl shadow-gray-200 hover:bg-black transition-all active:scale-95">
                                            Process Return
                                        </button>
                                    @else
                                        <div class="flex items-center justify-end gap-2 text-green-500">
                                            <span class="text-[10px] font-black uppercase tracking-widest">Completed</span>
                                            <span class="material-icons-outlined text-lg">check_circle</span>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- RETURN MODAL --}}
    <div id="returnBorrowModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm">
        <div class="bg-white p-10 rounded-[3rem] shadow-2xl w-full max-w-lg border border-gray-100 animate-in zoom-in duration-200">
            <h3 class="text-3xl font-black text-gray-900 mb-2 tracking-tighter">Process Return</h3>
            <p class="mb-8 text-sm font-medium text-gray-400">Recording return time and updating inventory...</p>
            <form id="returnBorrowForm" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                <div class="p-6 bg-blue-50/50 rounded-3xl border border-blue-100">
                    <p class="text-sm font-bold text-gray-700 leading-relaxed">
                        Processing return for Copy #<span id="returnCopy" class="text-blue-600 font-black"></span>
                        borrowed by <span id="returnUser" class="text-indigo-600 font-black"></span>.
                    </p>
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Return Date</label>
                    <input type="date" name="return_date" value="{{ now('Asia/Manila')->format('Y-m-d') }}" required
                        class="w-full border-none bg-gray-100 rounded-2xl p-4 font-bold focus:ring-2 focus:ring-green-500">
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="button" data-modal-toggle="returnBorrowModal" class="flex-1 px-3 py-4 bg-gray-100 text-gray-500 font-black rounded-2xl hover:bg-gray-200 transition">Cancel</button>
                    <button type="submit" class="flex-2 px-8 py-4 bg-green-600 text-white font-black rounded-2xl shadow-xl shadow-green-100 hover:bg-green-700 transition">Confirm Return</button>
                </div>
            </form>
        </div>
    </div>

    {{-- CREATE BORROW MODAL --}}
    <div id="createBorrowModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm">
        <div class="bg-white p-10 rounded-[3rem] shadow-2xl w-full max-w-3xl border border-gray-100 animate-in zoom-in duration-200 overflow-hidden">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-3xl font-black text-gray-900 tracking-tighter">New Loan</h3>
                <button data-modal-toggle="createBorrowModal" class="text-gray-400 hover:text-gray-900 transition-colors">✕</button>
            </div>

            <form id="borrowForm" action="{{ route('borrows.store') }}" method="POST" class="space-y-8">
                @csrf
                <div class="space-y-2">
                    <label for="user_id" class="text-[10px] font-black uppercase tracking-widest text-gray-400">Identify Borrower</label>
                    <select name="user_id" id="user_id" class="w-full border-none bg-gray-100 rounded-2xl p-5 font-bold focus:ring-2 focus:ring-blue-500 appearance-none shadow-inner" required>
                        <option value="">Search member name or email...</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div id="durationPreview" class="hidden animate-in fade-in slide-in-from-top-2"></div>

                {{-- BOOK SEARCH FILTER (NEW) --}}
                <div id="bookSearchContainer" class="hidden space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Filter Available Books</label>
                    <div class="relative">
                        <span class="material-icons-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-lg">search</span>
                        <input type="text" id="bookSearchInput" placeholder="Type book title to filter..."
                            class="w-full border-none bg-gray-100 rounded-2xl py-4 pl-12 pr-4 font-bold focus:ring-2 focus:ring-blue-500 shadow-inner text-sm">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Available Book Selection</label>
                    <div class="max-h-72 overflow-y-auto bg-gray-50/50 rounded-3xl border border-dashed border-gray-200 p-6 space-y-4" id="bookCopiesContainer">
                        <div class="flex flex-col items-center justify-center py-10 opacity-40">
                            <span class="material-icons-outlined text-4xl mb-2">person_search</span>
                            <p class="text-xs font-black uppercase tracking-widest">Select a borrower to load eligible inventory</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" data-modal-toggle="createBorrowModal" class="px-8 py-4 text-gray-400 font-black uppercase text-[10px] tracking-widest">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white font-black px-12 py-4 rounded-2xl shadow-xl shadow-blue-100 hover:bg-blue-700 transition active:scale-95">Complete Issue</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
@include('components.alerts')

<script>
    const books = @json($books);
    const users = @json($users);

    // User Selection and Book Loading
    document.getElementById('user_id').addEventListener('change', function() {
        const userId = this.value;
        const container = document.getElementById('bookCopiesContainer');
        const durationPreview = document.getElementById('durationPreview');
        const searchContainer = document.getElementById('bookSearchContainer');
        const searchInput = document.getElementById('bookSearchInput');

        container.innerHTML = '';
        searchInput.value = '';

        if (!userId) {
            durationPreview.classList.add('hidden');
            searchContainer.classList.add('hidden');
            container.innerHTML = '<div class="flex flex-col items-center justify-center py-10 opacity-40"><span class="material-icons-outlined text-4xl mb-2">person_search</span><p class="text-xs font-black uppercase tracking-widest">Select a borrower to load eligible inventory</p></div>';
            return;
        }

        searchContainer.classList.remove('hidden');

        // Handle Duration Preview
        const selectedUser = users.find(u => u.id == userId);
        if(selectedUser && selectedUser.role) {
            const isInstructor = selectedUser.role.name.toLowerCase() === 'instructor';
            const durationLabel = isInstructor ? '1 Month (Instructor Privileges)' : '3 Days (Standard Loan)';
            const colorClass = isInstructor ? 'bg-purple-50 text-purple-700 border-purple-100' : 'bg-blue-50 text-blue-700 border-blue-100';

            durationPreview.innerHTML = `
                <div class="flex items-center justify-between p-4 ${colorClass} border rounded-2xl">
                    <div class="flex items-center gap-3">
                        <span class="material-icons-outlined text-xl">${isInstructor ? 'workspace_premium' : 'schedule'}</span>
                        <span class="text-[10px] font-black uppercase tracking-widest">Calculated Return Window</span>
                    </div>
                    <span class="text-xs font-black">${durationLabel}</span>
                </div>
            `;
            durationPreview.classList.remove('hidden');
        }

        // Load and Render Books
        books.forEach(book => {
            const availableCopies = book.copies.filter(copy => {
                if (copy.status === 'available') return true;
                if (copy.status === 'reserved') {
                    return copy.reservations.find(r => r.user_id == userId) != null;
                }
                return false;
            });

            if (availableCopies.length === 0) return;

            const bookDiv = document.createElement('div');
            bookDiv.classList.add('book-item', 'p-5', 'bg-white', 'rounded-2xl', 'shadow-sm', 'border', 'border-gray-100', 'animate-in', 'fade-in');
            bookDiv.setAttribute('data-title', book.title.toLowerCase());

            let html = `<p class="font-black text-gray-800 mb-3 text-sm">${book.title}</p>`;

            availableCopies.forEach(copy => {
                const isReserved = copy.status === 'reserved' && copy.reservations.find(r => r.user_id == userId);
                const checked = isReserved ? 'checked' : '';
                const badge = isReserved ? '<span class="px-2 py-0.5 bg-indigo-100 text-indigo-600 text-[8px] font-black rounded-full ml-2">RESERVED FOR YOU</span>' : '';

                html += `
                    <label class="flex items-center p-3 hover:bg-gray-50 rounded-xl cursor-pointer transition-colors border border-transparent hover:border-blue-100">
                        <input type="checkbox" name="books[${book.id}][copy_ids][]" value="${copy.id}" class="w-5 h-5 rounded-lg border-gray-300 text-blue-600 focus:ring-blue-500" ${checked}>
                        <div class="ml-4 flex-1">
                            <div class="flex items-center">
                                <span class="text-xs font-black text-gray-700 uppercase">Copy #${copy.copy_number}</span>
                                ${badge}
                            </div>
                            <span class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">${copy.status} • Shelf: ${copy.shelf_location ?? 'N/A'}</span>
                        </div>
                    </label>
                `;
            });
            bookDiv.innerHTML = html;
            container.appendChild(bookDiv);
        });
    });

    // Live Search Logic
    document.getElementById('bookSearchInput').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        document.querySelectorAll('.book-item').forEach(item => {
            const title = item.getAttribute('data-title');
            item.style.display = title.includes(searchTerm) ? 'block' : 'none';
        });
    });

    // Return Modal Logic
    document.querySelectorAll('.return-borrow-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const form = document.getElementById('returnBorrowForm');
            form.action = `/admin/borrows/${this.dataset.id}/return`;
            document.getElementById('returnCopy').textContent = this.dataset.copy;
            document.getElementById('returnUser').textContent = this.dataset.user;
        });
    });
</script>
@endpush
