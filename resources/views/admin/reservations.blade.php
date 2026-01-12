@extends('components.default')

@section('title', 'Reservations | Admin Dashboard | LMIS')

@section('content')
<section>
    <div class="min-h-screen pt-24">
        @include('components.admin.topnav')

        <div class="flex flex-col lg:flex-row px-4 lg:px-10 pb-4 gap-6">
            {{-- Sidebar --}}
            <div class="lg:w-2/12 w-full">
                @include('components.admin.sidebar')
            </div>

            {{-- Main Content --}}
            <div class="lg:w-10/12 w-full">
                <div class="bg-white rounded-xl shadow-lg">

                    {{-- Header --}}
                    <div class="px-6 py-6">
                        {{-- Breadcrumb --}}
                        <nav class="flex px-5 py-3 text-gray-700 border border-gray-200 rounded-lg bg-gray-50"
                             aria-label="Breadcrumb">
                            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                                <li class="inline-flex items-center">
                                    <span class="text-sm font-medium text-gray-700">Admin</span>
                                </li>
                                <li class="flex items-center">
                                    <span class="mx-2 text-gray-400">›</span>
                                    <span class="text-sm font-medium text-gray-700">Dashboard</span>
                                </li>
                                <li class="flex items-center">
                                    <span class="mx-2 text-gray-400">›</span>
                                    <span class="text-sm font-medium text-gray-500">Reservations</span>
                                </li>
                            </ol>
                        </nav>
                    </div>

                    {{-- Table --}}
                    <div class="relative overflow-x-auto px-6 py-6">
                        <table id="datatable" class="w-full text-sm text-left text-gray-500">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3">User</th>
                                    <th class="px-6 py-3">Book</th>
                                    <th class="px-6 py-3">Status</th>
                                    <th class="px-6 py-3">Reserved At</th>
                                    <th class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach($reservations as $reservation)
                                    <tr class="odd:bg-white even:bg-gray-50 border-b">
                                        <td class="px-6 py-4">
                                            {{ $reservation->user->name ?? 'N/A' }}
                                        </td>

                                        <td class="px-6 py-4">
                                            {{ $reservation->book->title ?? 'N/A' }}
                                        </td>

                                        <td class="px-6 py-4">
                                            @if($reservation->status === 'pending')
                                                <span class="px-2 py-1 text-xs bg-yellow-200 text-yellow-800 rounded">
                                                    Pending
                                                </span>
                                            @elseif($reservation->status === 'approved')
                                                <span class="px-2 py-1 text-xs bg-green-200 text-green-800 rounded">
                                                    Approved
                                                </span>
                                            @else
                                                <span class="px-2 py-1 text-xs bg-red-200 text-red-800 rounded">
                                                    Rejected
                                                </span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4">
                                            {{ \Carbon\Carbon::parse($reservation->reserved_at)->format('M d, Y h:i A') }}
                                        </td>

                                        <td class="px-6 py-4 flex gap-2">
                                            @if($reservation->status === 'pending')
                                                {{-- Approve --}}
                                                <form action="{{ route('reservations.approve', $reservation->id) }}"
                                                      method="POST" class="approve-form">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                            class="px-3 py-2 text-xs text-white bg-green-600 hover:bg-green-700 rounded">
                                                        Approve
                                                    </button>
                                                </form>

                                                {{-- Reject --}}
                                                <form action="{{ route('reservations.reject', $reservation->id) }}"
                                                      method="POST" class="reject-form">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                            class="px-3 py-2 text-xs text-white bg-gray-600 hover:bg-gray-700 rounded">
                                                        Reject
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-xs italic text-gray-400">No actions</span>
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
    </div>
</section>
@endsection

@push('scripts')
@include('components.alerts')

<script>
    // Approve confirmation
    document.querySelectorAll('.approve-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Approve reservation?',
                text: "This reservation will be approved.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, approve'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Reject confirmation
    document.querySelectorAll('.reject-form').forEach(form => {
        form.addEventListener('submit', function (e) {
            e.preventDefault();

            Swal.fire({
                title: 'Reject reservation?',
                text: "This reservation will be rejected.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, reject'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endpush
