@extends('components.default')
@section('title', 'Edit Category | Admin Dashboard | LMIS')
@section('content')

<section>
    <div class="min-h-screen pt-24">
        {{-- Include Top Navigation --}}
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
                        <nav class="flex px-5 py-3 text-gray-700 border border-gray-200 rounded-lg bg-gray-50"
                            aria-label="Breadcrumb">
                            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                                <li class="inline-flex items-center">
                                    <a href="{{ route('categories.index') }}"
                                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                        <!-- Folder Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M3 7h5l2 3h11v11H3V7z" />
                                        </svg>
                                        Categories
                                    </a>
                                </li>
                                <li aria-current="page">
                                    <div class="flex items-center">
                                        <svg class="w-3 h-3 mx-1 text-gray-400" fill="none" viewBox="0 0 6 10"
                                            stroke="currentColor" stroke-width="2">
                                            <path d="m1 9 4-4-4-4" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Edit
                                            Category</span>
                                    </div>
                                </li>
                            </ol>
                        </nav>

                        {{-- Edit Category Form --}}
                        <div class="mt-6">
                            <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST"
                                class="space-y-4">
                                @csrf
                                @method('PUT')

                                <input type="text" name="name" value="{{ $supplier->name }}"
                                    class="w-full border p-2 rounded">
                                <input type="text" name="address" value="{{ $supplier->address }}"
                                    class="w-full border p-2 rounded">
                                <input type="text" name="contact_person" value="{{ $supplier->contact_person }}"
                                    class="w-full border p-2 rounded">
                                <input type="email" name="email" value="{{ $supplier->email }}"
                                    class="w-full border p-2 rounded">
                                <input type="text" name="phone" value="{{ $supplier->phone }}"
                                    class="w-full border p-2 rounded">

                                <button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
