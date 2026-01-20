@extends('components.default')

@section('title', 'Circulation Hub | LMIS')

@section('content')
<section class="bg-gray-50/50 min-h-screen pt-24">
    @include('components.admin.topnav')

    <div class="flex flex-col lg:flex-row px-4 lg:px-10 pb-10 gap-6">

        {{-- Sidebar --}}
        <div class="lg:w-2/12 w-full">
            @include('components.admin.sidebar')
        </div>

        {{-- Main Content --}}
        <div class="lg:w-10/12 w-full space-y-6">

            {{-- Header & Actions --}}
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

            {{-- Table Container --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/40 overflow-hidden border border-gray-100">
                <div class="overflow-x-auto">
                    <table id="datatable" class="w-full text-sm text-left text-gray-600">
                        <thead class="text-[10px] uppercase bg-gray-50/50 text-gray-400 font-black tracking-widest">
                            <tr>
                                <th class="px-8 py-5">Borrower</th>
                                <th class="px-8 py-5">Book Details</th>
                                <th class="px-8 py-5">Loan Period</th>
                                <th class="px-8 py-5">Status</th>
                                <th class="px-8 py-5 text-right">Actions</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-50">
                            @foreach($borrows as $borrow)
                            <tr class="hover:bg-blue-50/30 transition-colors group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-xs">
                                            {{ substr($borrow->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-black text-gray-900">{{ $borrow->user->name }}</p>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase">{{ $borrow->user->id_number ?? 'MEMBER' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="font-black text-gray-800 leading-tight">{{ $borrow->book->title }}</p>
                                    <p class="text-[10px] font-bold text-blue-500 uppercase mt-1">Copy #{{ $borrow->bookCopy->copy_number ?? 'N/A' }}</p>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-xs font-bold text-gray-700">Out: {{ $borrow->borrow_date->format('M d, Y') }}</span>
                                        @if($borrow->return_date)
                                            <span class="text-[10px] font-bold text-green-500 uppercase">In: {{ $borrow->return_date->format('M d, Y') }}</span>
                                        @else
                                            <span class="text-[10px] font-bold text-orange-400 uppercase tracking-tighter italic">Due: {{ $borrow->due_date->format('M d, Y') }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    @php
                                        $statusStyles = [
                                            'returned' => 'bg-green-100 text-green-700 border-green-200',
                                            'overdue'  => 'bg-red-100 text-red-600 border-red-200',
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
                                        data-user="{{ $borrow->user->name }}"
                                        data-modal-target="returnBorrowModal"
                                        data-modal-toggle="returnBorrowModal"
                                        class="return-borrow-btn bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest px-4 py-2 rounded-xl shadow-lg shadow-gray-200 hover:bg-black transition-all">
                                        Process Return
                                    </button>
                                    @else
                                    <span class="material-icons-outlined text-green-400">check_circle</span>
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
            <h3 class="text-3xl font-black text-gray-900 mb-2 tracking-tighter">Confirm Return</h3>
            <p class="mb-8 text-sm font-medium text-gray-400">Updating library inventory for Copy #<span id="returnCopy" class="text-blue-600 font-black"></span></p>

            <form id="returnBorrowForm" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="p-6 bg-gray-50 rounded-3xl border border-gray-100">
                    <p class="text-sm font-bold text-gray-700 leading-relaxed">
                        Are you sure <span id="returnUser" class="text-indigo-600 font-black"></span> has returned this book today?
                    </p>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Return Date Tracking</label>
                    <input type="date" name="return_date" value="{{ now()->format('Y-m-d') }}" required
                        class="w-full border-none bg-gray-100 rounded-2xl p-4 font-bold focus:ring-2 focus:ring-green-500">
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" data-modal-toggle="returnBorrowModal"
                        class="flex-1 px-3 py-4 bg-gray-100 text-gray-500 font-black rounded-2xl hover:bg-gray-200 transition">Cancel</button>
                    <button type="submit" class="flex-2 px-8 py-4 bg-green-600 text-white font-black rounded-2xl shadow-xl shadow-green-100 hover:bg-green-700 transition">Complete Return</button>
                </div>
            </form>
        </div>
    </div>

    {{-- CREATE BORROW MODAL --}}
    <div id="createBorrowModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/60 backdrop-blur-sm">
        <div class="bg-white p-10 rounded-[3rem] shadow-2xl w-full max-w-3xl border border-gray-100 animate-in zoom-in duration-200">
            <div class="flex justify-between items-center mb-8">
                <h3 class="text-3xl font-black text-gray-900 tracking-tighter">New Loan</h3>
                <button data-modal-toggle="createBorrowModal" class="text-gray-400">✕</button>
            </div>

            <form id="borrowForm" action="{{ route('borrows.store') }}" method="POST" class="space-y-8">
                @csrf

                <div class="space-y-2">
                    <label for="user_id" class="text-[10px] font-black uppercase tracking-widest text-gray-400">Identify Borrower</label>
                    <select name="user_id" id="user_id" class="w-full border-none bg-gray-100 rounded-2xl p-5 font-bold focus:ring-2 focus:ring-blue-500" required>
                        <option value="">Search member name or email...</option>
                        @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="text-[10px] font-black uppercase tracking-widest text-gray-400">Selection Basket</label>
                    <div class="max-h-72 overflow-y-auto bg-gray-50/50 rounded-3xl border border-dashed border-gray-200 p-6" id="bookCopiesContainer">
                        <div class="flex flex-col items-center justify-center py-10 opacity-40">
                            <span class="material-icons-outlined text-4xl mb-2">person_search</span>
                            <p class="text-xs font-black uppercase">Select a borrower to load eligible books</p>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" data-modal-toggle="createBorrowModal"
                        class="px-8 py-4 text-gray-400 font-bold uppercase text-xs">Cancel</button>
                    <button type="submit" class="bg-blue-600 text-white font-black px-12 py-4 rounded-2xl shadow-xl shadow-blue-100 hover:bg-blue-700 transition active:scale-95">
                        Issue Loan
                    </button>
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

    document.getElementById('user_id').addEventListener('change', function() {
        const userId = this.value;
        const container = document.getElementById('bookCopiesContainer');
        container.innerHTML = '';

        if (!userId) {
            container.innerHTML = '<div class="flex flex-col items-center justify-center py-10 opacity-40"><span class="material-icons-outlined text-4xl mb-2">person_search</span><p class="text-xs font-black uppercase">Select a borrower to load eligible books</p></div>';
            return;
        }

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
            bookDiv.classList.add('mb-6', 'p-4', 'bg-white', 'rounded-2xl', 'shadow-sm', 'border', 'border-gray-100');
            bookDiv.innerHTML = `<p class="font-black text-gray-800 mb-3">${book.title}</p>`;

            availableCopies.forEach(copy => {
                let isReservedForUser = false;
                if (copy.status === 'reserved') {
                    if (copy.reservations.find(r => r.user_id == userId)) isReservedForUser = true;
                }

                const checked = isReservedForUser ? 'checked' : '';
                const badge = isReservedForUser ? '<span class="px-2 py-0.5 bg-blue-100 text-blue-600 text-[9px] font-black rounded-full ml-2">RESERVED FOR THEM</span>' : '';

                const copyHtml = `
                    <label class="flex items-center p-3 hover:bg-gray-50 rounded-xl cursor-pointer transition-colors">
                        <input type="checkbox" name="books[${book.id}][copy_ids][]" value="${copy.id}" class="w-5 h-5 rounded-lg border-gray-300 text-blue-600 focus:ring-blue-500" ${checked}>
                        <div class="ml-4">
                            <span class="text-xs font-black text-gray-700">Copy #${copy.copy_number}</span>
                            <span class="text-[10px] text-gray-400 font-bold uppercase ml-2">• ${copy.status}</span>
                            ${badge}
                        </div>
                    </label>
                `;
                bookDiv.innerHTML += copyHtml;
            });
            container.appendChild(bookDiv);
        });
    });

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
