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
                        <nav class="flex px-5 py-3 text-gray-700 border rounded-lg bg-gray-50" aria-label="Breadcrumb">
                            <ol class="inline-flex items-center space-x-2">
                                <li>
                                    <a href="#" class="text-sm text-gray-600">Admin</a>
                                </li>
                                <li>/</li>
                                <li>
                                    <a href="#" class="text-sm text-gray-600">Dashboard</a>
                                </li>
                                <li>/</li>
                                <li class="text-sm text-gray-500">Borrow</li>
                            </ol>
                        </nav>

                        <!-- Add Button -->
                        <div class="flex justify-end py-4">
                            <button data-modal-target="createBorrowModal" data-modal-toggle="createBorrowModal"
                                class="flex items-center gap-2 bg-blue-600 text-white px-5 py-2.5 rounded-lg shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Add Borrow
                            </button>
                        </div>

                    </div>

                    <!-- Table -->
                    <div class="relative overflow-x-auto px-6 py-6">
                        <table id="datatable" class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs uppercase bg-gray-100">
                                <tr>
                                    <th class="px-6 py-3">User</th>
                                    <th class="px-6 py-3">Book</th>
                                    <th class="px-6 py-3">Quantity</th>
                                    <th class="px-6 py-3">Borrow Date</th>
                                    <th class="px-6 py-3">Due Date</th>
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
                                    <td class="px-6 py-4">{{ $borrow->quantity }}</td>
<td class="px-6 py-4">{{ \Carbon\Carbon::parse($borrow->borrow_date)->format('Y-m-d') }}</td>
<td class="px-6 py-4">{{ \Carbon\Carbon::parse($borrow->due_date)->format('Y-m-d') }}</td>

                                    <td class="px-6 py-4">
                                        {{ $borrow->return_date ? $borrow->return_date->format('Y-m-d') : '-' }}
                                    </td>
                                    <td class="px-6 py-4 capitalize">
                                        @if($borrow->status == 'returned')
                                        <span
                                            class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs">Returned</span>
                                        @elseif($borrow->status == 'overdue')
                                        <span
                                            class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-xs">Overdue</span>
                                        @else
                                        <span
                                            class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">Borrowed</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 flex gap-2">
                                        {{-- Return --}}
                                        @if($borrow->status !== 'returned')
                                        <button data-id="{{ $borrow->id }}" data-modal-target="returnBorrowModal"
                                            data-modal-toggle="returnBorrowModal"
                                            class="return-borrow-btn px-3 py-2 text-xs bg-green-600 text-white rounded hover:bg-green-700">
                                            Return
                                        </button>
                                        @endif

                                        {{-- Delete --}}
                                        <button data-id="{{ $borrow->id }}"
                                            class="delete-borrow-btn px-3 py-2 text-xs text-white bg-red-600 rounded hover:bg-red-700">
                                            Delete
                                        </button>

                                        <form id="delete-borrow-form-{{ $borrow->id }}"
                                            action="{{ route('borrows.destroy', $borrow->id) }}" method="POST"
                                            class="hidden">
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
    <!-- ADD BORROW MODAL -->
    <div id="createBorrowModal" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur">

        <div class="w-full max-w-2xl p-4">
            <div class="bg-white rounded-2xl p-6 shadow-lg">

                <div class="flex justify-between items-center border-b pb-3 mb-4">
                    <h3 class="text-xl font-bold">Add Borrow Record</h3>
                    <button data-modal-toggle="createBorrowModal" class="p-2 hover:bg-gray-200 rounded-full">✕</button>
                </div>

                <form action="{{ route('borrows.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <!-- Select User -->
                    <div>
                        <label class="font-medium text-sm">Select User</label>
                        <select name="user_id" class="w-full p-2 border rounded-lg bg-gray-50" required>
                            <option value="">-- choose user --</option>
                            @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Select Books and Quantity -->
                    <div>
                        <label class="font-medium text-sm">Select Books and Quantity</label>
                        <div class="overflow-x-auto max-h-64 border rounded p-2">
                            @foreach($books as $book)
                            <div class="flex items-center justify-between mb-2">
                                <div>
                                    <input type="checkbox" name="books[{{ $book->id }}][selected]" value="1"
                                        id="book-{{ $book->id }}" @if($book->copies_available < 1) disabled @endif>
                                        <label for="book-{{ $book->id }}">
                                            {{ $book->title }} ({{ $book->copies_available }} available)
                                        </label>
                                </div>
                                <div>
                                    <input type="number" name="books[{{ $book->id }}][quantity]" min="1"
                                        max="{{ $book->copies_available }}" value="1" class="w-20 p-1 border rounded"
                                        @if($book->copies_available < 1) disabled @endif>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                        Add Borrow
                    </button>
                </form>

            </div>
        </div>
    </div>



    <!-- ===================================================== -->
    <!-- RETURN BORROW MODAL -->
    <!-- ===================================================== -->
    <div id="returnBorrowModal" tabindex="-1"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur">

        <div class="w-full max-w-md p-4">
            <div class="bg-white p-6 rounded-xl shadow-xl">

                <div class="flex justify-between items-center border-b pb-3 mb-4">
                    <h3 class="text-lg font-bold">Return Book</h3>
                    <button data-modal-toggle="returnBorrowModal" class="p-2 hover:bg-gray-200 rounded-full">✕</button>
                </div>

                <form id="returnBorrowForm" method="POST">
                    @csrf
                    @method('PUT')

                    <p class="text-gray-700 mb-4">
                        Confirm marking this book as <strong>returned</strong>?
                    </p>

                    <div class="flex justify-end gap-3">
                        <button type="button" data-modal-toggle="returnBorrowModal"
                            class="px-3 py-2 bg-gray-300 rounded-lg">Cancel</button>
                        <button type="submit"
                            class="px-3 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Return</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

</section>
@endsection

@push('scripts')
@include('components.alerts')

<script>
    /* DELETE CONFIRMATION */
document.querySelectorAll('.delete-borrow-btn').forEach(btn => {
    btn.addEventListener('click', function () {
        let borrowId = this.dataset.id;

        Swal.fire({
            title: 'Delete this borrow record?',
            text: "This action cannot be undone.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it'
        }).then(result => {
            if (result.isConfirmed) {
                document.getElementById(`delete-borrow-form-${borrowId}`).submit();
            }
        });
    });
});

document.querySelectorAll('.return-borrow-btn').forEach(button => {
    button.addEventListener('click', function () {
        let borrowId = this.dataset.id;
        let form = document.getElementById('returnBorrowForm');
        form.action = `/admin/borrows/${borrowId}/return`;

    });
});

</script>

@endpush
