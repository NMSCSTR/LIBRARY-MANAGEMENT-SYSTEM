@extends('components.default')

@section('title', 'Users | Admin Dashboard | LMIS')

@section('content')

<section>
    <div class="min-h-screen pt-24">
        {{-- Top Navigation --}}
        @include('components.admin.topnav')

        <div class="flex flex-col lg:flex-row px-4 lg:px-10 pb-4 gap-6">
            {{-- Sidebar --}}
            <div class="lg:w-2/12 w-full">
                @include('components.admin.sidebar')
            </div>

            {{-- Main Content --}}
            <div class="lg:w-10/12 w-full">
                <div class="bg-white rounded-xl shadow-lg">
                    <div class="px-6 py-6">

                        <!-- Breadcrumb -->
                        <nav class="flex px-5 py-3 text-gray-700 border border-gray-200 rounded-lg bg-gray-50 dark:bg-gray-800 dark:border-gray-700"
                            aria-label="Breadcrumb">
                            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                                <li class="inline-flex items-center">
                                    <a href="#" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                                        Admin
                                    </a>
                                </li>
                                <li>
                                    <div class="flex items-center">
                                        <a href="#" class="ms-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ms-2 dark:text-gray-400 dark:hover:text-white">Dashboard</a>
                                    </div>
                                </li>
                                <li aria-current="page">
                                    <div class="flex items-center">
                                        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">Users</span>
                                    </div>
                                </li>
                            </ol>
                        </nav>

                        <div class="flex justify-end py-2 gap-2">
                            <!-- Add User Button -->
                            <button id="defaultModalButton" data-modal-target="createUserModal" data-modal-toggle="createUserModal" class="flex items-center gap-2 text-white bg-blue-600 hover:bg-blue-700
                                    focus:ring-4 focus:outline-none focus:ring-blue-300
                                    font-medium rounded-lg text-sm px-5 py-2.5 shadow-md hover:shadow-lg transition">

                                <!-- Plus Icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                    stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Add User
                            </button>

                            <!-- View Archived Users Button -->
                            <button data-modal-target="archiveUserModal" data-modal-toggle="archiveUserModal" class="flex items-center gap-2 text-white bg-orange-600 hover:bg-orange-700
                                    focus:ring-4 focus:outline-none focus:ring-orange-300
                                    font-medium rounded-lg text-sm px-5 py-2.5 shadow-md hover:shadow-lg transition">
                                Archived Users
                            </button>
                        </div>

                    </div>

                    {{-- Users Table --}}
                    <div class="relative overflow-x-auto sm:rounded-lg px-6 py-6">
                        <table id="datatable"
                            class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th class="px-6 py-3">Fullname</th>
                                    <th class="px-6 py-3">Email</th>
                                    <th class="px-6 py-3">Contact</th>
                                    <th class="px-6 py-3">Address</th>
                                    <th class="px-6 py-3">Role</th>
                                    <th class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($users as $user)
                                    @if(!$user->archived_at)
                                    <tr class="odd:bg-white even:bg-gray-50 border-b">
                                        <td class="px-6 py-4 font-medium">{{ $user->name }}</td>
                                        <td class="px-6 py-4">{{ $user->email }}</td>
                                        <td class="px-6 py-4">{{ $user->contact_number ?? '---' }}</td>
                                        <td class="px-6 py-4">{{ $user->address ?? '---' }}</td>
                                        <td class="px-6 py-4">{{ $user->role ? ucfirst($user->role->name) : 'No Role' }}</td>
                                        <td class="px-6 py-4">
                                            <div class="flex gap-2">
                                                <a href="{{ route('users.edit', $user->id) }}" class="px-3 py-2 text-xs text-white bg-blue-700 hover:bg-blue-800">
                                                    Edit
                                                </a>

                                                <form id="archive-form-{{ $user->id }}" action="{{ route('users.archive', $user->id) }}" method="POST" class="hidden">
                                                    @csrf
                                                    @method('PUT')
                                                </form>

                                                <button data-id="{{ $user->id }}" class="archive-user-btn px-3 py-2 text-xs text-white bg-orange-600 hover:bg-orange-700">
                                                    Archive
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Create User Modal -->
    @include('users.partials.create-user-modal')

    <!-- Archive Users Modal -->
    <div id="archiveUserModal" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="relative w-full max-w-3xl p-4">
            <div class="relative bg-white rounded-2xl shadow-2xl p-6">

                <div class="flex items-center justify-between border-b pb-3 mb-4">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        Archived Users
                    </h3>

                    <button type="button" data-modal-toggle="archiveUserModal"
                        class="p-2 rounded-full hover:bg-gray-200 text-gray-500 hover:text-gray-800">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                            <tr>
                                <th class="px-6 py-3">Fullname</th>
                                <th class="px-6 py-3">Email</th>
                                <th class="px-6 py-3">Role</th>
                                <th class="px-6 py-3">Archived At</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                @if($user->archived_at)
                                <tr class="odd:bg-white even:bg-gray-50 border-b">
                                    <td class="px-6 py-4 font-medium">{{ $user->name }}</td>
                                    <td class="px-6 py-4">{{ $user->email }}</td>
                                    <td class="px-6 py-4">{{ $user->role ? ucfirst($user->role->name) : 'No Role' }}</td>
                                    <td class="px-6 py-4">{{ $user->archived_at->format('Y-m-d H:i') }}</td>
                                </tr>
                                @endif
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
@include('components.alerts')

<script>
    document.querySelectorAll('.archive-user-btn').forEach(button => {
        button.addEventListener('click', function () {
            let userId = this.getAttribute('data-id');

            Swal.fire({
                title: 'Are you sure?',
                text: "This user will be archived!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#f59e0b',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, archive!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`archive-form-${userId}`).submit();
                }
            });
        });
    });
</script>
@endpush
