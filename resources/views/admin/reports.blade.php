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
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-[0.2em] mt-1">Comprehensive Library Analytics</p>
                </div>

                <div class="flex flex-col md:flex-row items-center gap-4">
                    {{-- Live Search Bar --}}
                    <div class="relative w-full md:w-72 group">
                        <span class="material-icons-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 group-focus-within:text-blue-600 transition-colors">search</span>
                        <input type="text" id="reportSearch" placeholder="Search across all reports..."
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

            {{-- QUICK STATS GRID --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
                {{-- Top Borrowed Books --}}
                <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-gray-100 flex flex-col h-full report-section">
                    <h3 class="text-lg font-black text-gray-800 mb-4 flex items-center gap-2">
                        <span class="material-icons-outlined text-orange-500">trending_up</span> Top Borrows
                    </h3>
                    <div class="space-y-4 flex-grow">
                        @forelse($topBooks as $book)
                        <div class="flex justify-between items-center searchable-row">
                            <span class="text-sm font-bold text-gray-600 truncate w-2/3 search-target">{{ $book->title }}</span>
                            <span class="px-3 py-1 bg-orange-50 text-orange-600 rounded-lg text-[10px] font-black uppercase">{{ $book->borrows_count }} loans</span>
                        </div>
                        @empty
                        <p class="text-gray-400 text-sm italic py-4">No data for this period.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Penalty / Overdue Watch --}}
                <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-gray-100 report-section">
                    <h3 class="text-lg font-black text-gray-800 mb-4 flex items-center gap-2">
                        <span class="material-icons-outlined text-red-500">gavel</span> Penalty Watch
                    </h3>
                    <div class="space-y-3 max-h-48 overflow-y-auto pr-2">
                        @forelse($penaltyBorrows as $penalty)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-2xl searchable-row">
                            <div>
                                <p class="text-xs font-black text-gray-900 search-target">{{ $penalty->user->name }}</p>
                                <p class="text-[9px] text-red-500 font-bold uppercase tracking-tighter">Due: {{ $penalty->due_date->format('M d') }} ({{ $penalty->due_date->diffForHumans() }})</p>
                            </div>
                            <span class="material-icons-outlined text-red-400 text-sm">error_outline</span>
                        </div>
                        @empty
                        <p class="text-gray-400 text-sm italic py-4 text-center">Clear! No overdue books.</p>
                        @endforelse
                    </div>
                </div>

                {{-- Damaged Books List --}}
                <div class="bg-white p-6 rounded-[2.5rem] shadow-sm border border-gray-100 report-section">
                    <h3 class="text-lg font-black text-gray-800 mb-4 flex items-center gap-2">
                        <span class="material-icons-outlined text-gray-500">inventory_2</span> Damaged Items
                    </h3>
                    <div class="space-y-3 max-h-48 overflow-y-auto pr-2">
                        @forelse($damagedBooks as $copy)
                        <div class="p-3 bg-gray-50 rounded-2xl border border-dashed border-gray-200 searchable-row">
                            <p class="text-xs font-black text-gray-800 truncate search-target">{{ $copy->book->title }}</p>
                            <p class="text-[9px] font-bold text-gray-400 uppercase">Copy #{{ $copy->copy_number }} • Loc: {{ $copy->shelf_location }}</p>
                        </div>
                        @empty
                        <p class="text-gray-400 text-sm italic py-4 text-center">All copies are in good condition.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Main Loan Registry Table --}}
            <div class="space-y-4 report-section">
                <div class="flex items-center gap-3 px-4">
                    <div class="w-2 h-8 bg-blue-600 rounded-full"></div>
                    <h2 class="text-xl font-black text-gray-800 tracking-tight">Full Circulation History</h2>
                </div>

                <div class="bg-white rounded-[3rem] shadow-2xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="px-8 py-6 text-[10px] font-black uppercase text-gray-400 tracking-widest">Borrower</th>
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
                                        <div class="w-10 h-10 rounded-xl bg-gray-100 text-gray-600 flex items-center justify-center font-black text-xs">
                                            {{ substr($log->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-black text-gray-900 leading-tight search-target">{{ $log->user->name }}</p>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase">{{ $log->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="font-bold text-gray-800 text-sm search-target">{{ $log->book->title }}</p>
                                    <span class="text-[9px] font-black text-blue-500 uppercase">COPY #{{ $log->bookCopy->copy_number ?? 'N/A' }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="flex flex-col text-[11px]">
                                        <span class="font-bold text-gray-700">Out: {{ $log->borrow_date->format('M d, Y') }}</span>
                                        <span class="{{ $log->return_date ? 'text-green-600 font-black' : 'text-gray-300 italic' }}">
                                            In: {{ $log->return_date ? $log->return_date->format('M d, Y') : 'Active' }}
                                        </span>
                                    </div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase border {{ $log->status === 'returned' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-orange-100 text-orange-700 border-orange-200' }}">
                                        {{ $log->status }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-12 text-center text-gray-400 italic">No loan records found for this date range.</td>
                            </tr>
                            @endforelse
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
            const targets = row.querySelectorAll('.search-target');
            let match = false;
            targets.forEach(t => {
                if(t.innerText.toLowerCase().includes(searchTerm)) match = true;
            });
            row.style.display = match ? '' : 'none';
        });

        // Toggle section visibility
        document.querySelectorAll('.report-section').forEach(section => {
            const hasVisibleRows = Array.from(section.querySelectorAll('.searchable-row'))
                                       .some(row => row.style.display !== 'none');
            section.style.opacity = hasVisibleRows ? '1' : '0.3';
        });
    });
</script>
@endpush
@endsection
