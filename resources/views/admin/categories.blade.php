@extends('components.default')
@section('title', 'Categories | Admin Dashboard | LMIS')
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
                                    Categories
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
                                Add Category
                            </button>
                        </div>
                    </div>

                    {{-- Table --}}
                    <div class="relative overflow-x-auto px-6 pb-6">
                        <table class="w-full text-sm text-left text-gray-600">
                            <thead class="text-xs uppercase bg-gray-100 text-gray-700">
                                <tr>
                                    <th class="px-6 py-3">Name</th>
                                    <th class="px-6 py-3">Description</th>
                                    <th class="px-6 py-3">Books</th>
                                    <th class="px-6 py-3">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                <tr class="odd:bg-white even:bg-gray-50 border-b border-gray-200">
                                    <td class="px-6 py-4">{{ $category->name }}</td>
                                    <td class="px-6 py-4">{{ $category->description ?? '-' }}</td>
                                    <td class="px-6 py-4">{{ $category->books_count }}</td>
                                    <td class="px-6 py-4 flex gap-2">
                                        <a href="{{ route('categories.edit', $category->id) }}"
                                            class="px-3 py-2 text-xs text-white bg-blue-600 hover:bg-blue-700 rounded-md">
                                            Edit
                                        </a>

                                        <button data-id="{{ $category->id }}"
                                            class="delete-category-btn px-3 py-2 text-xs text-white bg-red-500 hover:bg-red-600 rounded-md">
                                            Delete
                                        </button>

                                        <form id="delete-category-form-{{ $category->id }}"
                                            action="{{ route('categories.destroy', $category->id) }}"
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
        <div class="w-full max-w-xl p-4">
            <div class="bg-white rounded-2xl shadow-xl p-6">

                <div class="flex items-center justify-between border-b pb-3 mb-4">
                    <h3 class="text-lg font-semibold text-gray-800">
                        Add New Category
                    </h3>
                    <button data-modal-toggle="defaultModal"
                        class="text-gray-400 hover:text-gray-600">✕</button>
                </div>

                <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Category Name
                        </label>
                        <input type="text" name="name" required
                            class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Description
                        </label>
                        <textarea name="description" rows="3"
                            class="w-full bg-white border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"></textarea>
                    </div>

                    <button type="submit"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg px-5 py-2.5 shadow">
                        Add Category
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
    document.querySelectorAll('.delete-category-btn').forEach(button => {
        button.addEventListener('click', function () {
            let id = this.dataset.id;
            Swal.fire({
                title: 'Are you sure?',
                text: 'This category will be deleted permanently!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#2563eb',
                confirmButtonText: 'Yes, delete it!'
            }).then(result => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-category-form-${id}`).submit();
                }
            });
        });
    });
</script>
@endpush
