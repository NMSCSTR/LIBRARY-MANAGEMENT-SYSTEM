@extends('components.default')

@section('title', 'Authors | Admin Dashboard | LMIS')

@section('content')
<section class="bg-gray-50 min-h-screen pt-24">

    {{-- Top Navigation --}}
    @include('components.admin.topnav')

    <div class="flex flex-col lg:flex-row px-4 lg:px-10 pb-10 gap-6">

        {{-- Sidebar --}}
        <div class="lg:w-2/12 w-full">
            @include('components.admin.sidebar')
        </div>

        {{-- Main Content --}}
        <div class="lg:w-10/12 w-full space-y-6">

            {{-- Header Card --}}
            <div class="bg-white rounded-2xl shadow">

                <div class="px-6 py-5 border-b flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                    {{-- Breadcrumb --}}
                    <nav class="flex text-gray-600 text-sm" aria-label="Breadcrumb">
                        <ol class="inline-flex items-center space-x-2">
                            <li class="inline-flex items-center">
                                <span class="font-medium text-gray-700">Admin</span>
                            </li>
                            <li>/</li>
                            <li class="text-gray-500">Authors</li>
                        </ol>
                    </nav>

                    {{-- Add Author Button --}}
                    <button
                        data-modal-target="defaultModal"
                        data-modal-toggle="defaultModal"
                        class="inline-flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700
                        text-white text-sm font-medium px-5 py-2.5 rounded-xl shadow transition"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                            viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Add Author
                    </button>
                </div>

                {{-- Table --}}
                <div class="overflow-x-auto p-6">
                    <table class="w-full text-sm text-left text-gray-700">
                        <thead class="text-xs uppercase bg-gray-100 rounded-lg">
                            <tr>
                                <th class="px-6 py-4">Author Name</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($authors as $author)
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="px-6 py-4 font-medium text-gray-800">
                                    {{ $author->name }}
                                </td>
                                <td class="px-6 py-4 flex justify-end gap-2">

                                    {{-- Edit --}}
                                    <a href="{{ route('authors.edit', $author->id) }}"
                                        class="inline-flex items-center gap-1 px-3 py-1.5
                                        text-xs text-white bg-blue-600 hover:bg-blue-700
                                        rounded-lg transition">
                                        Edit
                                    </a>

                                    {{-- Delete --}}
                                    <button
                                        data-id="{{ $author->id }}"
                                        class="delete-author-btn inline-flex items-center gap-1
                                        px-3 py-1.5 text-xs text-white bg-red-600 hover:bg-red-700
                                        rounded-lg transition">
                                        Delete
                                    </button>

                                    <form id="delete-author-form-{{ $author->id }}"
                                        action="{{ route('authors.destroy', $author->id) }}"
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

    {{-- Add Author Modal --}}
    <div id="defaultModal" tabindex="-1" aria-hidden="true"
        class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">

        <div class="w-full max-w-md p-4">
            <div class="bg-white rounded-2xl shadow-2xl p-6">

                {{-- Modal Header --}}
                <div class="flex justify-between items-center border-b pb-3 mb-4">
                    <h3 class="text-lg font-bold text-gray-800">Add Author</h3>
                    <button data-modal-toggle="defaultModal"
                        class="p-2 rounded-full hover:bg-gray-200 text-gray-500">
                        ✕
                    </button>
                </div>

                {{-- Modal Body --}}
                <form action="{{ route('authors.store') }}" method="POST" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Author Name
                        </label>
                        <input
                            type="text"
                            name="name"
                            required
                            placeholder="Enter full name"
                            class="w-full px-4 py-2.5 border rounded-xl text-sm
                            focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </div>

                    <button type="submit"
                        class="w-full bg-indigo-600 hover:bg-indigo-700
                        text-white font-medium py-2.5 rounded-xl transition">
                        Add Author
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
    document.querySelectorAll('.delete-author-btn').forEach(button => {
        button.addEventListener('click', function () {
            let authorId = this.dataset.id;

            Swal.fire({
                title: 'Are you sure?',
                text: 'This author will be deleted permanently!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#4f46e5',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`delete-author-form-${authorId}`).submit();
                }
            });
        });
    });
</script>
@endpush
