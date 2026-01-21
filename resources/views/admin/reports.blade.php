@extends('components.default')

@section('title', 'Analytics & Reports | LMIS')

@section('content')
<section class="bg-gray-50/50 min-h-screen pt-24 pb-12">
    @include('components.admin.topnav')

    <div class="flex flex-col lg:flex-row px-4 lg:px-10 gap-8">
        {{-- Sidebar --}}
        <div class="lg:w-2/12 w-full">
            @include('components.admin.sidebar')
        </div>

        {{-- Main Dashboard --}}
        <div class="lg:w-10/12 w-full space-y-10">

            {{-- Professional Header --}}
            <div class="flex flex-col xl:flex-row xl:items-center justify-between bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 gap-6">
                <div>
                    <h1 class="text-4xl font-black text-gray-900 tracking-tight">System Report</h1>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-[0.2em] mt-1">Audit Trail & Circulation History</p>
                </div>

                <div class="flex flex-col md:flex-row items-center gap-4">
                    {{-- NEW: Live Search Bar --}}
                    <div class="relative w-full md:w-72 group">
                        <span class="material-icons-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">search</span>
                        <input type="text" id="reportSearch" placeholder="Search logs..."
                            class="w-full pl-12 pr-4 py-3 bg-gray-50 border-none rounded-2xl text-xs font-black text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all shadow-inner">
                    </div>

                    {{-- Date Filter Form --}}
                    <form action="{{ route('reports.index') }}" method="GET" class="flex items-center gap-2">
                        <div class="relative group">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-400 uppercase">From</span>
                            <input type="date" name="start_date" value="{{ $startDate }}"
                                class="pl-14 pr-4 py-3 bg-gray-50 border-none rounded-2xl text-xs font-black text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all shadow-inner">
                        </div>
                        <div class="relative group">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-[10px] font-black text-gray-400 uppercase">To</span>
                            <input type="date" name="end_date" value="{{ $endDate }}"
                                class="pl-10 pr-4 py-3 bg-gray-50 border-none rounded-2xl text-xs font-black text-gray-700 focus:ring-2 focus:ring-blue-500 transition-all shadow-inner">
                        </div>
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white p-3.5 rounded-2xl shadow-lg shadow-blue-100 transition-all active:scale-95">
                            <span class="material-icons-outlined text-sm">tune</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Loan History Section --}}
            <div class="space-y-4 report-section">
                <div class="flex items-center gap-3 px-4">
                    <div class="w-2 h-8 bg-blue-600 rounded-full"></div>
                    <h2 class="text-xl font-black text-gray-800 tracking-tight">Loan Registry</h2>
                </div>

                <div class="bg-white rounded-[3rem] shadow-2xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                    <table class="w-full text-left" id="loanTable">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="px-8 py-6 text-[10px] font-black uppercase text-gray-400 tracking-widest">Borrower Information</th>
                                <th class="px-8 py-6 text-[10px] font-black uppercase text-gray-400 tracking-widest">Book & Copy</th>
                                <th class="px-8 py-6 text-[10px] font-black uppercase text-gray-400 tracking-widest">Timeline</th>
                                <th class="px-8 py-6 text-[10px] font-black uppercase text-gray-400 tracking-widest text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($borrowLogs as $log)
                            <tr class="hover:bg-blue-50/10 transition-colors group searchable-row">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-gray-100 to-gray-200 text-gray-600 flex items-center justify-center font-black text-sm">
                                            {{ substr($log->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-black text-gray-900 leading-tight group-hover:text-blue-600 transition-colors search-target">{{ $log->user->name }}</p>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase search-target">{{ $log->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="font-bold text-gray-800 text-sm leading-snug search-target">{{ $log->book->title }}</p>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="px-2 py-0.5 bg-blue-50 text-blue-600 text-[9px] font-black rounded-md border border-blue-100 uppercase">COPY #{{ $log->bookCopy->copy_number }}</span>
                                    </div>
                                </td>
                                {{-- ... (rest of row) ... --}}
                                <td class="px-8 py-6">
                                    <div class="flex flex-col gap-1 text-xs">
                                        <span class="font-bold text-gray-700">Out: {{ $log->borrow_date->format('M d, Y') }}</span>
                                        <span class="{{ $log->return_date ? 'text-green-600 font-bold' : 'text-gray-300 italic' }}">
                                            In: {{ $log->return_date ? $log->return_date->format('M d, Y') : 'Active' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $log->status === 'returned' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-orange-100 text-orange-700 border-orange-200' }}">
                                        {{ $log->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            {{-- ... --}}
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Reservation History Section --}}
            <div class="space-y-4 report-section">
                <div class="flex items-center gap-3 px-4">
                    <div class="w-2 h-8 bg-indigo-600 rounded-full"></div>
                    <h2 class="text-xl font-black text-gray-800 tracking-tight">Reservation Logs</h2>
                </div>

                <div class="bg-white rounded-[3rem] shadow-2xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                    <table class="w-full text-left" id="resTable">
                        <tbody class="divide-y divide-gray-50">
                            @foreach($reservationLogs as $res)
                            <tr class="hover:bg-indigo-50/10 transition-colors searchable-row">
                                <td class="px-8 py-6 font-black text-gray-900 text-sm search-target">{{ $res->user->name }}</td>
                                <td class="px-8 py-6 font-bold text-gray-700 text-sm search-target">{{ $res->book->title }}</td>
                                <td class="px-8 py-6 search-target text-[10px] font-black uppercase tracking-widest text-gray-400">{{ $res->status }}</td>
                                <td class="px-8 py-6 text-right font-bold text-xs text-gray-400">{{ $res->created_at->format('M d, Y') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
    document.getElementById('reportSearch').addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const rows = document.querySelectorAll('.searchable-row');

        rows.forEach(row => {
            const text = row.innerText.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        });

        // Hide section headers if no results in that section
        document.querySelectorAll('.report-section').forEach(section => {
            const visibleRows = section.querySelectorAll('.searchable-row[style="display: \'\', display: \"\"]');
            const hiddenRows = section.querySelectorAll('.searchable-row[style="display: none;"]');
            // Simple logic: if all rows in a section are hidden, hide section
            const totalRows = section.querySelectorAll('.searchable-row').length;
            section.style.display = (hiddenRows.length === totalRows && totalRows > 0) ? 'none' : 'block';
        });
    });
</script>
@endpush
@endsection
