@extends('components.default')

@section('title', 'Role Architecture | Admin Dashboard | LMIS')

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

            {{-- Header & Add Action --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="space-y-1">
                    <h1 class="text-4xl font-black text-gray-900 tracking-tighter">Access Architecture</h1>
                    <p class="text-sm font-bold text-gray-400 uppercase tracking-widest flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                        Manage system roles and permission levels
                    </p>
                </div>

                <button data-modal-target="createUserModal" data-modal-toggle="createUserModal"
                    class="bg-gray-900 hover:bg-black text-white text-[11px] font-black uppercase tracking-widest px-10 py-5 rounded-[2rem] shadow-2xl shadow-gray-200 transition-all active:scale-95 flex items-center gap-4">
                    <span class="text-xl leading-none">+</span> Define New Role
                </button>
            </div>

            {{-- Roles Registry Table --}}
            <div class="bg-white rounded-[3rem] shadow-2xl shadow-gray-200/50 overflow-hidden border border-gray-100">
                <div class="overflow-x-auto">
                    <table id="datatable" class="w-full text-sm text-left text-gray-600">
                        <thead class="text-[10px] uppercase bg-gray-50/50 text-gray-400 font-black tracking-widest border-b border-gray-100">
                            <tr>
                                <th class="px-10 py-6">Role Identity</th>
                                <th class="px-8 py-6 text-center">User Saturation</th>
                                <th class="px-10 py-6 text-right">Operations</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-50">
                            @foreach($roles as $role)
                            <tr class="hover:bg-blue-50/20 transition-all group">
                                <td class="px-10 py-8">
                                    <div class="flex items-center gap-4">
                                        <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-black text-xs border border-indigo-100 shadow-sm group-hover:bg-indigo-600 group-hover:text-white transition-all">
                                            {{ substr($role->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <p class="font-black text-gray-900 text-base leading-tight">{{ ucfirst($role->name) }}</p>
                                            <p class="text-[10px] text-gray-400 font-bold uppercase mt-1 tracking-widest">System Access Level</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-8 text-center">
                                    <div class="inline-flex flex-col items-center">
                                        <span class="text-xl font-black text-gray-900 leading-none">{{ $role->users_count }}</span>
                                        <span class="text-[9px] font-black text-indigo-400 uppercase tracking-tighter mt-1">Assigned Users</span>
                                    </div>
                                </td>
                                <td class="px-10 py-8">
                                    <div class="flex justify-end gap-3">
                                        {{-- Edit --}}
                                        <a href="{{ route('roles.edit', $role->id) }}"
                                            class="bg-white border border-gray-200 text-gray-900 text-[10px] font-black uppercase tracking-widest px-5 py-3 rounded-xl hover:bg-gray-50 transition-all shadow-sm">
                                            Modify
                                        </a>

                                        {{-- Delete --}}
                                        <button data-id="{{ $role->id }}"
                                            class="delete-role-btn bg-red-50 text-red-600 text-[10px] font-black uppercase tracking-widest px-5 py-3 rounded-xl hover:bg-red-600 hover:text-white transition-all border border-red-100">
                                            Revoke
                                        </button>

                                        <form id="delete-role-form-{{ $role->id }}"
                                            action="{{ route('roles.destroy', $role->id) }}" method="POST"
                                            class="hidden">
                                            @csrf @method('DELETE')
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

    <div id="createUserModal" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-[100] flex items-center justify-center bg-gray-900/80 backdrop-blur-xl transition-all">

        <div class="relative w-full max-w-lg p-6">
            <div class="relative bg-white rounded-[3.5rem] shadow-2xl p-10 border border-white/20 animate-in zoom-in duration-300">

                <div class="text-center mb-10">
                    <div class="w-20 h-20 bg-indigo-50 text-indigo-600 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                        <span class="material-icons text-4xl">admin_panel_settings</span>
                    </div>
                    <h3 class="text-3xl font-black text-gray-900 tracking-tighter">Define New Role</h3>
                    <p class="text-sm font-bold text-gray-400 mt-2 uppercase tracking-widest">Set new system access permissions</p>
                </div>

                <form action="{{ route('roles.store') }}" method="POST" class="space-y-8">
                    @csrf
                    <div class="space-y-3">
                        <label class="text-[11px] font-black uppercase tracking-widest text-gray-400 ml-4">Role Designation</label>
                        <input type="text" name="name" placeholder="e.g. Librarian, Assistant" required
                            class="w-full border-none bg-gray-100 rounded-3xl p-6 font-black text-gray-700 focus:ring-4 focus:ring-blue-100 transition-all">
                    </div>

                    <div class="flex flex-col gap-4 pt-4">
                        <button type="submit" class="w-full py-6 bg-blue-600 text-white font-black uppercase tracking-widest text-xs rounded-3xl shadow-2xl shadow-blue-200 hover:bg-blue-700 transition-all active:scale-95">
                            Authorize Role
                        </button>
                        <button type="button" data-modal-toggle="createUserModal" class="w-full py-4 text-gray-400 font-black uppercase tracking-widest text-[10px] hover:text-gray-900 transition-colors">
                            Discard
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts')
@include('components.alerts')

<script>
    document.querySelectorAll('.delete-role-btn').forEach(button => {
        button.addEventListener('click', function () {
            let roleId = this.getAttribute('data-id');

            Swal.fire({
                title: 'Confirm Revocation?',
                text: "Removing this role may affect users currently assigned to it!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#000',
                cancelButtonColor: '#9ca3af',
                confirmButtonText: 'Yes, Revoke Role',
                cancelButtonText: 'Keep Role'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-role-form-${roleId}`).submit();
                }
            });
        });
    });
</script>
@endpush
