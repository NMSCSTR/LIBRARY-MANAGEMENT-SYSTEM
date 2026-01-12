@extends('components.default')
@section('title', 'Publisher | Admin Dashboard | LMIS')
@section('content')

<section>
    <div class="min-h-screen pt-24 bg-gray-100">
        @include('components.admin.topnav')

        <div class="flex flex-col lg:flex-row px-4 lg:px-10 pb-6 gap-6">

            {{-- Sidebar --}}
            <div class="lg:w-2/12 w-full">
                @include('components.admin.sidebar')
            </div>

            {{-- Main Content --}}
            <div class="lg:w-10/12 w-full">
                <div class="bg-white rounded-xl shadow-md">

                    {{-- Header --}}
                    <div class="px-6 py-6">

                        {{-- Breadcrumb --}}
                        <nav class="flex px-5 py-3 text-gray-700 border border-gray-200 rounded-lg bg-gray-100">
                            <ol class="inline-flex items-center space-x-2">
                                <li>
                                    <a href="#" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                        Admin
                                    </a>
                                </li>
                                <li class="text-gray-400">/</li>
                                <li>
                                    <a href="#" class="text-sm font-medium text-gray-700 hover:text-blue-600">
                                        Dashboard
                                    </a>
                                </li>
                                <li class="text-gray-400">/</li>
                                <li class="text-sm font-medium text-gray-500">
                                    Publishers
                                </li>
                            </ol>
                        </nav>

                        {{-- Add Button --}}
                        <div class="flex justify-end mt-4">
                            <button
                                data-modal-target="defaultModal"
                                data-modal-toggle="defaultModal"
                                class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg shadow">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 4.5v15m7.5-7.5h-15" />
                                </svg>
                                Add Publisher
                            </button>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="relative overflow-x-auto px-6 pb-6">
                        <table class="w-full text-sm text-left text-gray-600">
                            <thead class="text-xs uppercase bg-gray-100 text-gray-700">
                                <tr>
                                    <th class="px-6 py-3">Name</th>
                                    <th class="px-6 py-3">Address</th>
                                    <th class="px-6 py-3">Contact</th>
                                    <th class="px-6 py-3">Email</th>
                                    <th class="px-6 py-3">Phone</th>
                                    <th class="px-6 py-3">Books</th>
                                    <th class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($publishers as $publisher)
                                <tr class="odd:bg-white even:bg-gray-50 border-b border-gray-200">
                                    <td class="px-6 py-4">{{ $publisher->name }}</td>
                                    <td class="px-6 py-4">{{ $publisher->address ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $publisher->contact_person ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $publisher->email ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $publisher->phone ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $publisher->books_count }}</td>
                                    <td class="px-6 py-4 flex gap-2">
                                        <a href="{{ route('publishers.edit', $publisher->id) }}"
                                            class="px-3 py-2 text-xs text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                                            Edit
                                        </a>

                                        <button data-id="{{ $publisher->id }}"
                                            class="delete-publisher-btn px-3 py-2 text-xs text-white bg-red-500 hover:bg-red-600 rounded-md">
                                            Delete
                                        </button>

                                        <form id="delete-publisher-form-{{ $publisher->id }}"
                                            action="{{ route('publishers.destroy', $publisher->id) }}"
                                            method="POST" class="hidden">
                                            @csrf
                                            @method('DELETE')
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
    </div>

    {{-- Modal --}}
    <div id="defaultModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/30">
        <div class="w-full max-w-lg p-4">
            <div class="bg-white rounded-2xl shadow-xl p-6">

                <div class="flex justify-between items-center border-b pb-3 mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Add New Publisher
                    </h3>
                    <button data-modal-toggle="defaultModal"
                        class="text-gray-400 hover:text-gray-600">✕</button>
                </div>

                <form action="{{ route('publishers.store') }}" method="POST" class="space-y-4">
                    @csrf

                    @foreach(['name'=>'Publisher Name','address'=>'Address','contact_person'=>'Contact Person','email'=>'Email','phone'=>'Phone'] as $field=>$label)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">{{ $label }}</label>
                        <input type="text" name="{{ $field }}"
                            class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>
                    @endforeach

                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg px-5 py-2.5 shadow">
                        Add Publisher
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
                title: 'Are you sure?',
                text: 'This publisher will be deleted permanently!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#2563eb',
                confirmButtonText: 'Yes, delete it!'
            }).then(result => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-publisher-form-${id}`).submit();
                }
            });
        });
    });
</script>
@endpush
