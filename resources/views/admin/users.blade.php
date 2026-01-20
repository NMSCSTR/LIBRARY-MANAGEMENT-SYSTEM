@extends('components.default')

@section('title', 'Member Directory | Admin Dashboard | LMIS')

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

            {{-- Header & Multi-Actions --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="space-y-1">
                    <h1 class="text-4xl font-black text-gray-900 tracking-tighter">Member Directory</h1>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        Manage system access and member profiles
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <button data-modal-target="archiveUserModal" data-modal-toggle="archiveUserModal"
                        class="bg-white border border-gray-200 text-gray-500 text-[10px] font-black uppercase tracking-widest px-6 py-5 rounded-[2rem] hover:bg-gray-50 transition-all flex items-center gap-2">
                        <span class="material-icons-outlined text-sm">inventory_2</span>
                        Archive Vault
                    </button>

                    <button data-modal-target="createUserModal" data-modal-toggle="createUserModal"
                        class="bg-gray-900 hover:bg-black text-white text-[10px] font-black uppercase tracking-widest px-10 py-5 rounded-[2rem] shadow-2xl shadow-gray-200 transition-all active:scale-95 flex items-center gap-3">
                        <span class="text-lg leading-none">+</span> Add New Member
                    </button>
                </div>
            </div>

            {{-- Users Registry Table --}}
            <div class="bg-white rounded-[3rem] shadow-2xl shadow-gray-200/50 overflow-hidden border border-gray-100 p-6">
                <div class="overflow-x-auto">
                    <table id="datatable" class="w-full text-sm text-left text-gray-600">
                        <thead class="text-[10px] uppercase bg-gray-50/50 text-gray-400 font-black tracking-widest border-b border-gray-100">
                            <tr>
                                <th class="px-10 py-6">Member Identity</th>
                                <th class="px-8 py-6">Contact Info</th>
                                <th class="px-8 py-6">Access Level</th>
                                <th class="px-10 py-6 text-right">Profile Control</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-50">
                            @foreach($users as $user)
                            <tr class="hover:bg-blue-50/20 transition-all group">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-xs border border-indigo-100 shadow-sm group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-black text-gray-900 text-base leading-tight">{{ $user->name }}</p>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase mt-1 tracking-widest">{{ $user->address ?? 'No Address Provided' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-8">
                                    <p class="text-xs font-bold text-gray-700">{{ $user->email }}</p>
                                    <p class="text-[10px] text-indigo-400 font-black mt-1">{{ $user->contact_number ?? 'No Contact' }}</p>
                                </td>
                                <td class="px-8 py-8">
                                    <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border border-gray-100 bg-gray-50 text-gray-500">
                                        {{ $user->role ? $user->role->name : 'Unassigned' }}
                                    </span>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="flex justify-end gap-3">
                                        <a href="{{ route('users.edit', $user->id) }}"
                                            class="bg-white border border-gray-200 text-gray-900 text-[10px] font-black uppercase tracking-widest px-5 py-3 rounded-xl hover:bg-gray-50 transition-all shadow-sm">
                                            Edit
                                        </a>

                                        <button data-id="{{ $user->id }}"
                                            class="archive-user-btn bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-widest px-5 py-3 rounded-xl hover:bg-red-600 hover:text-white transition-all border border-red-100">
                                            Archive
                                        </button>

                                        <form id="archive-form-{{ $user->id }}" action="{{ route('users.archive', $user->id) }}" method="POST" class="hidden">
                                            @csrf @method('PUT')
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- CREATE USER MODAL --}}
    <div id="createUserModal" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/80 backdrop-blur-xl transition-all">
        <div class="relative w-full max-w-2xl p-6">
            <div class="relative bg-white rounded-[3.5rem] shadow-2xl p-10 border border-white/20 animate-in zoom-in duration-300">
                <div class="text-center mb-8">
                    <h3 class="text-3xl font-black text-gray-900 tracking-tighter">Register New Member</h3>
                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mt-2">Initialize a new user profile</p>
                </div>

                <form action="{{ route('users.store') }}" method="POST" class="grid grid-cols-2 gap-6">
                    @csrf
                    <div class="col-span-2 space-y-1">
                        <label class="text-[11px] font-black uppercase text-gray-400 ml-4">Full Name</label>
                        <input type="text" name="name" required class="w-full border-none bg-gray-100 rounded-2xl p-4 font-bold focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-black uppercase text-gray-400 ml-4">Email Address</label>
                        <input type="email" name="email" required class="w-full border-none bg-gray-100 rounded-2xl p-4 font-bold focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-black uppercase text-gray-400 ml-4">Account Role</label>
                        <select name="role_id" class="w-full border-none bg-gray-100 rounded-2xl p-4 font-bold focus:ring-2 focus:ring-blue-500" required>
                            @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ ucfirst($role->name) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-black uppercase text-gray-400 ml-4">Password</label>
                        <input type="password" name="password" required class="w-full border-none bg-gray-100 rounded-2xl p-4 font-bold focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div class="space-y-1">
                        <label class="text-[11px] font-black uppercase text-gray-400 ml-4">Confirm</label>
                        <input type="password" name="password_confirmation" required class="w-full border-none bg-gray-100 rounded-2xl p-4 font-bold focus:ring-2 focus:ring-blue-500">
                    </div>

                    <div class="col-span-2 flex gap-4 pt-4">
                        <button type="submit" class="flex-1 py-5 bg-blue-600 text-white font-black uppercase tracking-widest text-xs rounded-2xl shadow-xl shadow-blue-100">Create Profile</button>
                        <button type="button" data-modal-toggle="createUserModal" class="px-8 py-5 text-gray-400 font-black uppercase text-[10px]">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- ARCHIVED USERS MODAL --}}
    <div id="archiveUserModal" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/80 backdrop-blur-xl transition-all">
        <div class="relative w-full max-w-4xl p-6">
            <div class="relative bg-white rounded-[3.5rem] shadow-2xl p-10 border border-white/20 animate-in zoom-in duration-300">
                <h3 class="text-3xl font-black text-gray-900 tracking-tighter mb-8">Archive Vault</h3>

                <div class="overflow-x-auto max-h-[30rem] rounded-2xl border border-gray-100">
                    <table class="w-full text-sm text-left">
                        <thead class="text-[10px] uppercase bg-gray-50 text-gray-400 font-black tracking-widest">
                            <tr>
                                <th class="px-6 py-4">Fullname</th>
                                <th class="px-6 py-4">Archived Date</th>
                                <th class="px-6 py-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($archivedUsers as $user)
                            <tr>
                                <td class="px-6 py-5 font-black text-gray-700">{{ $user->name }}</td>
                                <td class="px-6 py-5 text-xs text-gray-400">{{ $user->archived_at ? \Carbon\Carbon::parse($user->archived_at)->format('M d, Y') : '---' }}</td>
                                <td class="px-6 py-5 text-right">
                                    <form action="{{ route('users.unarchive', $user->id) }}" method="POST">
                                        @csrf @method('PUT')
                                        <button type="submit" class="text-[10px] font-black uppercase text-emerald-500 hover:text-emerald-700">Restore</button>
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

</section>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.archive-user-btn').forEach(button => {
        button.addEventListener('click', function () {
            let userId = this.getAttribute('data-id');
            Swal.fire({
                title: 'Archive Member?',
                text: "This user will lose access to the system immediately.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#000',
                confirmButtonText: 'Confirm Archive'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`archive-form-${userId}`).submit();
                }
            });
        });
    });
</script>
@endpush
