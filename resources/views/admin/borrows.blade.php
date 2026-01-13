@extends('components.default')
@section('title', 'Borrows | Admin Dashboard | LMIS')
@section('content')

<section>
    <div class="min-h-screen pt-24">

        @include('components.admin.topnav')

        <div class="flex flex-col lg:flex-row px-4 lg:px-10 pb-4 gap-6">

            <div class="lg:w-2/12 w-full">
                @include('components.admin.sidebar')
            </div>

            <div class="lg:w-10/12 w-full">

                <div class="bg-white rounded-xl shadow-lg">
                    <div class="px-6 py-6">

                        <!-- Breadcrumb -->
                        <nav class="flex px-5 py-3 text-gray-700 border rounded-lg bg-gray-50">
                            <ol class="inline-flex items-center space-x-2">
                                <li class="text-sm text-gray-600">Admin</li>
                                <li>/</li>
                                <li class="text-sm text-gray-600">Dashboard</li>
                                <li>/</li>
                                <li class="text-sm text-gray-500">Borrow</li>
                            </ol>
                        </nav>

                        <!-- Add Button -->
                        <div class="flex justify-end py-4">
                            <button data-modal-target="createBorrowModal" data-modal-toggle="createBorrowModal"
                                class="flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-lg shadow">
                                + Add Borrow
                            </button>
                        </div>

                    </div>

                    <!-- Table -->
                    <div class="relative overflow-x-auto px-6 py-6">
                        <table id="datatable" class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs uppercase bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3">Borrower</th>
                                    <th class="px-6 py-3">Book</th>
                                    <th class="px-6 py-3">Copy Number</th>
                                    <th class="px-6 py-3">Borrow Date</th>
                                    <th class="px-6 py-3">Return Date</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($borrows as $borrow)
                                <tr class="border-b">
                                    <td class="px-6 py-4">{{ $borrow->user->name }}</td>
                                    <td class="px-6 py-4">{{ $borrow->book->title }}</td>
                                    <td class="px-6 py-4">{{ $borrow->bookCopy->copy_number ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $borrow->borrow_date->timezone('Asia/Manila')->format('F j, Y g:i A') }}</td>
                                    <td class="px-6 py-4">
                                        {{ $borrow->return_date ? $borrow->return_date->timezone('Asia/Manila')->format('F j, Y g:i A') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 capitalize">
                                        @if($borrow->status === 'returned')
                                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs">Returned</span>
                                        @elseif($borrow->status === 'overdue')
                                        <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-xs">Overdue</span>
                                        @else
                                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">Borrowed</span>
                                        @endif
                                    </td>

                                    <td class="px-6 py-4 flex gap-2">

                                        {{-- Return --}}
                                        @if($borrow->status !== 'returned')
                                        <button data-id="{{ $borrow->id }}" data-copy="{{ $borrow->bookCopy->copy_number }}"
                                            data-user="{{ $borrow->user->name }}" data-modal-target="returnBorrowModal"
                                            data-modal-toggle="returnBorrowModal"
                                            class="return-borrow-btn px-3 py-2 text-xs bg-green-600 text-white rounded">
                                            Return
                                        </button>
                                        @endif

                                        {{-- Delete --}}
                                        <button data-id="{{ $borrow->id }}"
                                            class="delete-borrow-btn px-3 py-2 text-xs bg-red-600 text-white rounded">
                                            Delete
                                        </button>

                                        <form method="POST"
                                            action="{{ route('borrows.destroy', ['borrow' => $borrow->id]) }}"
                                            id="delete-borrow-form-{{ $borrow->id }}" class="hidden">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>

    {{-- RETURN MODAL --}}
    <div id="returnBorrowModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="bg-white p-6 rounded-xl shadow-xl w-full max-w-md">
            <h3 class="text-lg font-bold mb-4">Return Book</h3>

            <form id="returnBorrowForm" method="POST">
                @csrf
                @method('PUT')

                <p class="mb-2 text-sm">
                    Mark <span id="returnCopy" class="font-semibold"></span> borrowed by <span id="returnUser" class="font-semibold"></span> as returned?
                </p>

                <div class="mb-4">
                    <label class="block mb-1 font-medium">Return Date</label>
                    <input type="date" name="return_date" value="{{ now()->format('Y-m-d') }}" required
                        class="w-full border px-3 py-2 rounded">
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" data-modal-toggle="returnBorrowModal"
                        class="px-3 py-2 bg-gray-300 rounded">Cancel</button>
                    <button type="submit" class="px-3 py-2 bg-green-600 text-white rounded">Return</button>
                </div>
            </form>
        </div>
    </div>

   <!-- Add Borrow Modal -->
<div id="createBorrowModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
    <div class="bg-white p-6 rounded-xl shadow-xl w-full max-w-2xl">
        <h3 class="text-lg font-bold mb-4">Add Borrow</h3>

        <form id="borrowForm" action="{{ route('borrows.store') }}" method="POST">
            @csrf

            <!-- Select User -->
            <div class="mb-4">
                <label for="user_id" class="block mb-2 font-medium">Select Borrower</label>
                <select name="user_id" id="user_id" class="w-full border px-3 py-2 rounded" required>
                    <option value="">-- Select User --</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div>

            <!-- Select Book Copies -->
            <div class="mb-4">
                <label class="block mb-2 font-medium">Select Book Copies</label>
                <div class="space-y-2 max-h-64 overflow-y-auto border p-3 rounded" id="bookCopiesContainer">
                    <p class="text-gray-400 text-sm">Select a borrower first to see available/reserved copies.</p>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button type="button" data-modal-toggle="createBorrowModal" class="px-3 py-2 bg-gray-300 rounded">Cancel</button>
                <button type="submit" class="px-3 py-2 bg-blue-600 text-white rounded">Borrow</button>
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
        container.innerHTML = '<p class="text-gray-400 text-sm">Select a borrower first to see available/reserved copies.</p>';
        return;
    }

    books.forEach(book => {
        // Filter copies: available or reserved by selected user
        const availableCopies = book.copies.filter(copy => {
            if(copy.status === 'available') return true;
            if(copy.status === 'reserved') {
                const reservation = copy.reservations.find(r => r.status === 'reserved');
                return reservation && reservation.user_id == userId;
            }
            return false;
        });

        if (availableCopies.length === 0) return;

        const bookDiv = document.createElement('div');
        bookDiv.classList.add('mb-2');
        bookDiv.innerHTML = `<p class="font-medium">${book.title}</p>`;

        availableCopies.forEach(copy => {
            const copyHtml = `
            <div class="flex items-center gap-2 ml-3">
                <input type="checkbox" name="books[${book.id}][copy_id]" value="${copy.id}" id="copy-${copy.id}">
                <label for="copy-${copy.id}">
                    Copy #${copy.copy_number} (${copy.status})
                </label>
            </div>`;
            bookDiv.innerHTML += copyHtml;
        });

        container.appendChild(bookDiv);
    });
});
</script>

@endpush
