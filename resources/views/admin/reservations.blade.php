@extends('components.default')

@section('title', 'Reservation Queue | LMIS')

@section('content')
<section class="bg-gray-50/50 min-h-screen pt-24 pb-12">
    @include('components.admin.topnav')

    <div class="flex flex-col lg:flex-row px-4 lg:px-10 gap-6">

        {{-- Sidebar --}}
        <div class="lg:w-2/12 w-full">
            @include('components.admin.sidebar')
        </div>

        {{-- Main Content --}}
        <div class="lg:w-10/12 w-full space-y-6">

            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight">Reservation Queue</h1>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Approve or decline hold requests from borrowers</p>
                </div>
                <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-gray-100">
                    <span class="text-[10px] font-black uppercase text-gray-400 block">Pending Requests</span>
                    <span class="text-xl font-black text-orange-500">{{ $reservations->where('status', 'pending')->count() }}</span>
                </div>
            </div>

            {{-- Table Container --}}
            <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/40 overflow-hidden border border-gray-100 p-6">
                <div class="overflow-x-auto">
                    <table id="datatable" class="w-full text-sm text-left text-gray-600">
                        <thead class="text-[10px] uppercase bg-gray-50/50 text-gray-400 font-black tracking-widest border-b border-gray-100">
                            <tr>
                                <th class="px-8 py-5">Requestor</th>
                                <th class="px-8 py-5">Book Details</th>
                                <th class="px-8 py-5">Status</th>
                                <th class="px-8 py-5">Timeline</th>
                                <th class="px-8 py-5 text-right">Decision Hub</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-50">
                            @forelse($reservations as $reservation)
                            <tr class="hover:bg-indigo-50/20 transition group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-sm border border-indigo-100">
                                            {{ substr($reservation->user->name ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-black text-gray-900 leading-tight">{{ $reservation->user->name ?? 'Unknown User' }}</p>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter">{{ $reservation->user->email ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="font-black text-gray-800 leading-tight">{{ $reservation->book->title ?? 'N/A' }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[10px] font-bold text-indigo-500 uppercase">Copy #{{ $reservation->copy->copy_number ?? 'N/A' }}</span>
                                        <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                        <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">Loc: {{ $reservation->copy->shelf_location ?? 'N/A' }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    @php
                                        $statusStyles = [
                                            'pending'  => 'bg-orange-100 text-orange-600 border-orange-200 animate-pulse shadow-[0_0_10px_rgba(249,115,22,0.1)]',
                                            'reserved' => 'bg-blue-100 text-blue-700 border-blue-200',
                                            'declined' => 'bg-red-50 text-red-400 border-red-100',
                                        ];
                                        $currentStyle = $statusStyles[$reservation->status] ?? 'bg-gray-100 text-gray-500';
                                    @endphp
                                    <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $currentStyle }}">
                                        {{ $reservation->status }}
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-xs">
                                    <div class="flex flex-col gap-0.5">
                                        <span class="font-black text-gray-700">Req: {{ $reservation->created_at->format('M d, Y') }}</span>
                                        @if($reservation->reserved_at)
                                            <span class="font-bold text-emerald-500 italic">App: {{ \Carbon\Carbon::parse($reservation->reserved_at)->format('M d, Y') }}</span>
                                        @else
                                            <span class="font-bold text-gray-300 italic">Waiting...</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="px-8 py-6 text-right">
                                    @if($reservation->status === 'pending')
                                    <div class="flex justify-end gap-2">
                                        {{-- Approve Button --}}
                                        <button
                                            onclick="confirmAction('{{ route('reservations.approve', $reservation->id) }}', 'Approve this reservation?')"
                                            class="bg-gray-900 text-white text-[10px] font-black uppercase tracking-widest px-5 py-3 rounded-2xl shadow-xl shadow-gray-200 hover:bg-black transition-all active:scale-95">
                                            Approve
                                        </button>

                                        {{-- Reject Button --}}
                                        <button
                                            onclick="confirmAction('{{ route('reservations.reject', $reservation->id) }}', 'Decline this reservation?', 'warning', '#d33')"
                                            class="bg-white border border-gray-200 text-red-500 text-[10px] font-black uppercase tracking-widest px-5 py-3 rounded-2xl hover:bg-red-50 transition-all">
                                            Decline
                                        </button>
                                    </div>
                                    @else
                                        <div class="flex items-center justify-end gap-2 text-gray-300">
                                            <span class="text-[10px] font-black uppercase tracking-widest">Logged</span>
                                            <span class="material-icons-outlined text-lg">history_edu</span>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-8 py-20 text-center">
                                    <div class="opacity-20 flex flex-col items-center">
                                        <span class="material-icons text-6xl">hourglass_empty</span>
                                        <p class="font-black uppercase tracking-widest mt-4">No reservations currently in queue</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- Hidden Action Forms --}}
    <form id="action-form" method="POST" class="hidden">
        @csrf
        @method('PUT')
    </form>
</section>
@endsection

@push('scripts')
@include('components.alerts')
<script>
    function confirmAction(url, title, icon = 'question', confirmColor = '#000') {
        Swal.fire({
            title: title,
            text: "This action will update the inventory status automatically.",
            icon: icon,
            showCancelButton: true,
            confirmButtonColor: confirmColor,
            cancelButtonColor: '#9ca3af',
            confirmButtonText: 'Confirm Action',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('action-form');
                form.action = url;
                form.submit();
            }
        });
    }
</script>
@endpush
