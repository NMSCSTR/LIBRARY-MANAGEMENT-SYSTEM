@extends('components.default')

@section('title', 'Publishers Management | LMIS')

@section('content')
<section>
    <div class="min-h-screen pt-24 bg-gray-50/50">
        @include('components.admin.topnav')

        <div class="flex flex-col lg:flex-row px-4 lg:px-10 pb-6 gap-6">

            {{-- Sidebar --}}
            <div class="lg:w-2/12 w-full">
                @include('components.admin.sidebar')
            </div>

            {{-- Main Content --}}
            <div class="lg:w-10/12 w-full">

                {{-- Header & Stats --}}
                <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-gray-800">Publishers Directory</h1>
                        <p class="text-sm text-gray-500">Manage your library's book sources and contact information.</p>
                    </div>
                    <button data-modal-target="defaultModal" data-modal-toggle="defaultModal"
                        class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-5 py-2.5 rounded-xl shadow-md transition-all active:scale-95">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                        </svg>
                        Add New Publisher
                    </button>
                </div>

                {{-- Stats Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                        <p class="text-sm font-medium text-gray-500">Total Publishers</p>
                        <p class="text-2xl font-bold text-gray-800">{{ $publishers->count() }}</p>
                    </div>
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                        <p class="text-sm font-medium text-gray-500">Total Books Published</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $publishers->sum('books_count') }}</p>
                    </div>
                    <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                        <p class="text-sm font-medium text-gray-500">Active Contacts</p>
                        <p class="text-2xl font-bold text-green-600">{{ $publishers->whereNotNull('email')->count() }}</p>
                    </div>
                </div>

                {{-- Table Container --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left text-gray-600">
                            <thead class="text-xs uppercase bg-gray-50 text-gray-500 font-semibold">
                                <tr>
                                    <th class="px-6 py-4">Publisher Details</th>
                                    <th class="px-6 py-4">Contact Person</th>
                                    <th class="px-6 py-4">Communication</th>
                                    <th class="px-6 py-4 text-center">Library Data</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @forelse($publishers as $publisher)
                                <tr class="hover:bg-blue-50/30 transition-colors group">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-lg">
                                                {{ substr($publisher->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="font-bold text-gray-800 group-hover:text-blue-700">{{ $publisher->name }}</div>
                                                <div class="text-xs text-gray-400 max-w-[200px] truncate">{{ $publisher->address ?? 'No address provided' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="text-gray-700 font-medium">{{ $publisher->contact_person ?? 'N/A' }}</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col space-y-1">
                                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                                {{ $publisher->email ?? '-' }}
                                            </div>
                                            <div class="flex items-center gap-2 text-xs text-gray-500">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                                {{ $publisher->phone ?? '-' }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            {{ $publisher->books_count }} Books
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-2">
                                            <a href="{{ route('publishers.edit', $publisher->id) }}"
                                                class="p-2 text-blue-600 hover:bg-blue-100 rounded-lg transition-colors" title="Edit Publisher">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                            <button data-id="{{ $publisher->id }}"
                                                class="delete-publisher-btn p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="Delete Publisher">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                            <form id="delete-publisher-form-{{ $publisher->id }}" action="{{ route('publishers.destroy', $publisher->id) }}" method="POST" class="hidden">
                                                @csrf @method('DELETE')
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                        <div class="flex flex-col items-center">
                                            <svg class="w-12 h-12 mb-3 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                            <p>No publishers found in the system.</p>
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
    </div>

    {{-- Modern Add Modal --}}
    <div id="defaultModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm">
        <div class="w-full max-w-lg p-4 animate-in fade-in zoom-in duration-200">
            <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
                <div class="bg-blue-600 px-6 py-4 flex justify-between items-center text-white">
                    <h3 class="font-bold text-lg">Add New Publisher</h3>
                    <button data-modal-toggle="defaultModal" class="text-white/80 hover:text-white">✕</button>
                </div>

                <form action="{{ route('publishers.store') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Company Name</label>
                        <input type="text" name="name" required class="w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500" placeholder="e.g. Penguin Books">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Contact Person</label>
                            <input type="text" name="contact_person" class="w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500" placeholder="Full Name">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Phone Number</label>
                            <input type="text" name="phone" class="w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500" placeholder="+123...">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Email Address</label>
                        <input type="email" name="email" class="w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500" placeholder="contact@company.com">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Physical Address</label>
                        <textarea name="address" rows="2" class="w-full bg-gray-50 border-gray-200 rounded-xl focus:ring-blue-500 focus:border-blue-500" placeholder="Street, City, Country"></textarea>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 rounded-xl shadow-lg shadow-blue-200 transition-all">
                        Save Publisher
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
@include('components.alerts')
<script>
    document.querySelectorAll('.delete-publisher-btn').forEach(button => {
        button.addEventListener('click', function () {
            let id = this.dataset.id;
            Swal.fire({
                title: 'Delete Publisher?',
                text: 'This action will remove all contact data associated with this publisher.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, delete it'
            }).then(result => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-publisher-form-${id}`).submit();
                }
            });
        });
    });
</script>
@endpush
