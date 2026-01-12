@extends('components.default')
@section('title', 'Reservations | Admin Dashboard | LMIS')
@section('content')

<section>
    <div class="min-h-screen pt-24">
        {{-- @include('components.admin.bg') --}}
        {{-- Include Top Navigation --}}
        @include('components.admin.topnav')
        <div class="flex flex-col lg:flex-row px-4 lg:px-10 pb-4 gap-6">

            {{-- Include Sidebar --}}
            <div class="lg:w-2/12 w-full">
                @include('components.admin.sidebar')
            </div>

            {{-- Main Content --}}
            <div class="lg:w-10/12 w-full">

                <div class="bg-white rounded-xl shadow-lg">
                    <div class="px-6 py-6">

                        <!-- Breadcrumb -->
                        <nav class="flex px-5 py-3 text-gray-700 border border-gray-200 rounded-lg bg-gray-50"
                            aria-label="Breadcrumb">
                            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                                <li class="inline-flex items-center">
                                    <a href="#"
                                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                        <svg class="w-3 h-3 me-2.5" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                            <path
                                                d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                                        </svg>
                                        Admin
                                    </a>
                                </li>
                                <li>
                                    <div class="flex items-center">
                                        <svg class="rtl:rotate-180 block w-3 h-3 mx-1 text-gray-400 " aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m1 9 4-4-4-4" />
                                        </svg>
                                        <a href="#"
                                            class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2">Dashboard</a>
                                    </div>
                                </li>
                                <li aria-current="page">
                                    <div class="flex items-center">
                                        <svg class="rtl:rotate-180  w-3 h-3 mx-1 text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m1 9 4-4-4-4" />
                                        </svg>
                                        <span
                                            class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Reservation</span>
                                    </div>
                                </li>
                            </ol>
                        </nav>

                    </div>

                    <div class="relative overflow-x-auto sm:rounded-lg px-6 py-6">
                        <table id="datatable"
                            class="w-full text-sm text-left rtl:text-right text-gray-700">
                            <thead
                                class="text-xs text-gray-700 uppercase bg-gray-100 py-4">
                                <tr>
                                    <th scope="col" class="px-6 py-3">
                                        <div class="flex items-center">
                                            User
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        <div class="flex items-center">
                                            Book
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        <div class="flex items-center">
                                            Status
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        <div class="flex items-center">
                                            Reserve At
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-3">
                                        <div class="flex items-center">
                                            Actions
                                        </div>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($reservations as $reservation)
                                <tr class="odd:bg-white even:bg-gray-50 border-b">
                                    <td class="px-6 py-4">{{ $reservation->user->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4">{{ $reservation->book->title ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 capitalize">{{ $reservation->status }}</td>
                                    <td class="px-6 py-4">{{ $reservation->reserved_at }}</td>
                                    <td class="px-6 py-4 flex gap-2">
                                        {{-- Approve --}}
                                        <form action="{{ route('reservations.approve', $reservation->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                class="approve-reservation-btn px-3 py-2 text-xs text-white bg-green-600 hover:bg-green-700">
                                                Approve
                                            </button>
                                        </form>

                                        {{-- Reject --}}
                                        <form action="{{ route('reservations.reject', $reservation->id) }}"
                                            method="POST">
                                            @csrf
                                            @method('PUT')
                                            <button type="submit"
                                                class="reject-reservation-btn px-3 py-2 text-xs text-white bg-gray-500 hover:bg-gray-600">
                                                Reject
                                            </button>
                                        </form>
                                    </td>
                                    @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
@include('components.alerts')
<script>
    document.querySelectorAll('.delete-reservation-btn').forEach(button => {
    button.addEventListener('click', function () {
        let reservationId = this.getAttribute('data-id');

        Swal.fire({
            title: 'Are you sure?',
            text: "This reservation will be deleted permanently!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(`delete-reservation-form-${reservationId}`).submit();
            }
        });
    });
});

document.querySelectorAll('.reject-reservation-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        let form = this.closest('form');

        Swal.fire({
            title: 'Are you sure?',
            text: "This reservation will be rejected!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, reject it!'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endpush
