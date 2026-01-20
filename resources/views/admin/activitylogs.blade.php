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

            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="space-y-1">
                    <h1 class="text-4xl font-black text-gray-900 tracking-tighter">System Audit Log</h1>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-indigo-500 animate-pulse"></span>
                        Real-time tracking of administrative operations
                    </p>
                </div>
                <div class="bg-white px-8 py-5 rounded-[2rem] shadow-sm border border-gray-100 flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                        <span class="material-icons-outlined text-indigo-600 text-xl">history</span>
                    </div>
                    <div>
                        <span class="text-[10px] font-black uppercase text-gray-400 block mb-1">Log Entries</span>
                        <span class="text-xl font-black text-gray-900 leading-none">{{ number_format($logs->total() ?? count($logs)) }}</span>
                    </div>
                </div>
            </div>

            {{-- Activity Registry Table --}}
            <div class="bg-white rounded-[3rem] shadow-2xl shadow-gray-200/50 overflow-hidden border border-gray-100">
                <div class="overflow-x-auto">
                    <table id="datatable" class="w-full text-sm text-left text-gray-600">
                        <thead class="text-[10px] uppercase bg-gray-50/50 text-gray-400 font-black tracking-widest border-b border-gray-100">
                            <tr>
                                <th class="px-10 py-6 text-center">Reference At</th>
                                <th class="px-8 py-6">Administrator</th>
                                <th class="px-8 py-6 text-center">Operation</th>
                                <th class="px-10 py-6">Transaction Details</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-50">
                            @forelse($logs as $log)
                            <tr class="hover:bg-indigo-50/20 transition-all group">
                                <td class="px-10 py-8 whitespace-nowrap text-center">
                                    <div class="flex flex-col">
                                        <span class="font-black text-gray-900 text-xs">{{ $log->created_at->format('M d, Y') }}</span>
                                        <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-tighter mt-0.5">{{ $log->created_at->format('g:i A') }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-2xl bg-gray-100 text-gray-400 flex items-center justify-center font-black text-xs border border-gray-200 group-hover:bg-indigo-600 group-hover:text-white group-hover:border-indigo-600 transition-all shadow-sm">
                                            {{ substr($log->user->name ?? '?', 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-black text-gray-900 leading-tight">{{ $log->user->name ?? 'System Process' }}</p>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase mt-1">Admin User</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-8 text-center">
                                    @php
                                        $actionStyles = [
                                            'borrow' => 'bg-blue-50 text-blue-600 border-blue-100',
                                            'return' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                            'approve_reservation' => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                                            'reject_reservation' => 'bg-orange-50 text-orange-600 border-orange-100',
                                            'delete_borrow' => 'bg-red-50 text-red-600 border-red-100',
                                        ];
                                        $cleanAction = str_replace('_', ' ', $log->action);
                                        $currentStyle = $actionStyles[$log->action] ?? 'bg-gray-50 text-gray-500 border-gray-100';
                                    @endphp
                                    <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $currentStyle }} shadow-sm">
                                        {{ $cleanAction }}
                                    </span>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="p-4 bg-gray-50/50 rounded-2xl border border-gray-100 max-w-lg">
                                        <p class="text-[11px] font-bold text-gray-600 leading-relaxed italic">
                                            "{{ $log->description }}"
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-10 py-20 text-center">
                                    <div class="opacity-20 flex flex-col items-center">
                                        <span class="material-icons text-6xl">inventory_2</span>
                                        <p class="font-black uppercase tracking-widest mt-4">System log is currently empty</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Pagination Container --}}
            @if(method_exists($logs, 'links'))
            <div class="px-6 py-4">
                {{ $logs->links() }}
            </div>
            @endif

        </div>
    </div>
</section>
@endsection

@push('scripts')
@include('components.alerts')
@endpush
