@extends('components.default')

@section('title', 'System Audit Log | LMIS')

@section('content')
<section class="bg-[#fcfcfd] min-h-screen pt-24 pb-12">
    @include('components.admin.topnav')

    <div class="flex flex-col lg:flex-row px-4 lg:px-10 gap-8">
        {{-- Sidebar --}}
        <div class="lg:w-2/12 w-full">
            @include('components.admin.sidebar')
        </div>

        {{-- Main Content --}}
        <div class="lg:w-10/12 w-full space-y-8">

            {{-- Header & Total Count --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="space-y-1">
                    <h1 class="text-4xl font-black text-gray-900 tracking-tighter">System Audit Log</h1>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                        Administrative Action Registry
                    </p>
                </div>

                {{-- This part uses the .total() method which now works due to ->paginate() --}}
                <div class="bg-white px-8 py-5 rounded-[2rem] shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                        <span class="material-icons-outlined text-indigo-600 text-xl">history</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-black uppercase text-gray-400 block mb-1">Total Entries</span>
                        <span class="text-xl font-black text-gray-900 leading-none">{{ number_format($logs->total()) }}</span>
                    </div>
                </div>
            </div>

            {{-- Table --}}
            <div class="bg-white rounded-[3rem] shadow-2xl shadow-gray-200/50 overflow-hidden border border-gray-100">
                <div class="overflow-x-auto">
                    <table id="datatable" class="w-full text-sm text-left text-gray-600">
                        <thead class="text-[10px] uppercase bg-gray-50/50 text-gray-400 font-black tracking-widest border-b border-gray-100">
                            <tr>
                                <th class="px-10 py-6">Admin User</th>
                                <th class="px-8 py-6">Operation</th>
                                <th class="px-8 py-6">Timestamp</th>
                                <th class="px-10 py-6">Log Description</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-50">
                            @forelse($logs as $log)
                            <tr class="hover:bg-indigo-50/10 transition-colors group">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center font-black text-xs border border-gray-200 group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-sm">
                                            {{ substr($log->user->name ?? '?', 0, 1) }}
                                        </div>
                                        <p class="font-black text-gray-900 text-sm leading-tight">{{ $log->user->name ?? 'System' }}</p>
                                    </div>
                                </td>
                                <td class="px-8 py-8">
                                    @php
                                        $actionStyles = [
                                            'borrow' => 'bg-blue-50 text-blue-600 border-blue-100',
                                            'return' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            'approve_reservation' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                            'reject_reservation' => 'bg-orange-50 text-orange-600 border-orange-100',
                                        ];
                                        $style = $actionStyles[$log->action] ?? 'bg-gray-50 text-gray-500 border-gray-100';
                                    @endphp
                                    <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $style }}">
                                        {{ str_replace('_', ' ', $log->action) }}
                                    </span>
                                </td>
                                <td class="px-8 py-8">
                                    <div class="flex flex-col">
                                        <span class="font-black text-gray-900 text-xs">{{ $log->created_at->format('M d, Y') }}</span>
                                        <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-tighter">{{ $log->created_at->format('g:i A') }}</span>
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <p class="text-xs font-medium text-gray-500 italic leading-relaxed">"{{ $log->description }}"</p>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="px-10 py-20 text-center text-gray-300 font-black uppercase tracking-widest italic opacity-40">The audit log is currently empty</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination Links --}}
            <div class="mt-6">
                {{ $logs->links() }}
            </div>
        </div>
    </div>
</section>
@endsection
