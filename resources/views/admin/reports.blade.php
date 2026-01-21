@extends('components.default')

@section('title', 'System Reports | LMIS')

@section('content')
<section class="bg-gray-50 min-h-screen pt-24 pb-10">
    @include('components.admin.topnav')

    <div class="flex flex-col lg:flex-row px-4 lg:px-10 gap-6">
        <div class="lg:w-2/12 w-full">
            @include('components.admin.sidebar')
        </div>

        <div class="lg:w-10/12 w-full space-y-8">
            {{-- Header & Filters --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-black text-gray-900 tracking-tight">System Reports</h1>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest">Historical Transaction Logs</p>
                </div>

                <form action="{{ route('reports.index') }}" method="GET" class="flex flex-wrap items-end gap-3 bg-white p-4 rounded-[2rem] shadow-sm border border-gray-100">
                    <div class="flex flex-col gap-1">
                        <label class="text-[9px] font-black text-gray-400 uppercase ml-2">From</label>
                        <input type="date" name="start_date" value="{{ $startDate }}" class="bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="text-[9px] font-black text-gray-400 uppercase ml-2">To</label>
                        <input type="date" name="end_date" value="{{ $endDate }}" class="bg-gray-50 border-none rounded-xl text-xs font-bold focus:ring-2 focus:ring-blue-500">
                    </div>
                    <button type="submit" class="bg-gray-900 text-white p-3 rounded-xl hover:bg-black transition-all">
                        <span class="material-icons-outlined text-sm">filter_alt</span>
                    </button>
                </form>
            </div>

            {{-- Borrowing Report Table --}}
            <div class="space-y-4">
                <h2 class="text-xs font-black text-blue-600 uppercase tracking-[0.2em] ml-2">Loan History</h2>
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/40 overflow-hidden border border-gray-100 p-6">
                    <table class="w-full text-sm text-left text-gray-600 datatable">
                        <thead class="text-[10px] uppercase bg-gray-50/50 text-gray-400 font-black tracking-widest border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4">Borrower</th>
                                <th class="px-6 py-4">Book Title</th>
                                <th class="px-6 py-4">Issue Date</th>
                                <th class="px-6 py-4">Return Date</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($borrowLogs as $log)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 font-black text-gray-900">{{ $log->user->name }}</td>
                                <td class="px-6 py-4">
                                    <span class="block font-bold text-gray-800">{{ $log->book->title }}</span>
                                    <span class="text-[10px] text-blue-500 font-black uppercase">Copy #{{ $log->bookCopy->copy_number }}</span>
                                </td>
                                <td class="px-6 py-4 text-xs font-medium text-gray-500">{{ $log->borrow_date->format('M d, Y') }}</td>
                                <td class="px-6 py-4 text-xs">
                                    @if($log->return_date)
                                        <span class="font-bold text-green-600">{{ $log->return_date->format('M d, Y') }}</span>
                                    @else
                                        <span class="text-gray-300 italic">Pending</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <span class="text-[9px] font-black uppercase px-3 py-1 rounded-full border {{ $log->status === 'returned' ? 'bg-green-50 text-green-600 border-green-100' : 'bg-orange-50 text-orange-600 border-orange-100' }}">
                                        {{ $log->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Reservation Report Table --}}
            <div class="space-y-4">
                <h2 class="text-xs font-black text-indigo-600 uppercase tracking-[0.2em] ml-2">Reservation History</h2>
                <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/40 overflow-hidden border border-gray-100 p-6">
                    <table class="w-full text-sm text-left text-gray-600 datatable">
                        <thead class="text-[10px] uppercase bg-gray-50/50 text-gray-400 font-black tracking-widest border-b border-gray-100">
                            <tr>
                                <th class="px-6 py-4">Requestor</th>
                                <th class="px-6 py-4">Book</th>
                                <th class="px-6 py-4">Requested On</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($reservationLogs as $res)
                            <tr class="hover:bg-gray-50/50 transition-colors">
                                <td class="px-6 py-4 font-black text-gray-900">{{ $res->user->name }}</td>
                                <td class="px-6 py-4 font-bold text-gray-700">{{ $res->book->title }}</td>
                                <td class="px-6 py-4 text-xs text-gray-500">{{ $res->created_at->format('M d, Y') }}</td>
                                <td class="px-6 py-4">
                                    <span class="text-[9px] font-black uppercase px-3 py-1 rounded-full border {{ $res->status === 'declined' ? 'bg-red-50 text-red-500 border-red-100' : 'bg-blue-50 text-blue-600 border-blue-100' }}">
                                        {{ $res->status }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
