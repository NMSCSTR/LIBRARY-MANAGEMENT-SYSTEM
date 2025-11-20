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
                            <form action="{{ route('categories.update', $category->id) }}" method="POST"
                                class="space-y-4">
                                @csrf
                                @method('PUT')

                                {{-- Category Name --}}
                                <div class="relative space-y-1">
                                    <label class="text-sm font-medium text-gray-700">Category Name</label>
                                    <div class="relative">
                                        <input type="text" name="name" value="{{ old('name', $category->name) }}"
                                            required
                                            class="w-full pl-10 bg-gray-50 rounded-lg border-gray-600">
                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <!-- Pencil Icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Description --}}
                                <div class="relative space-y-1">
                                    <label class="text-sm font-medium p-2.5 text-gray-700">Description</label>
                                    <div class="relative">
                                        <textarea name="description"
                                            class="w-full pl-10 bg-gray-50 p-2.5 rounded-lg border-gray-600">{{ old('description', $category->description) }}</textarea>
                                        <div class="absolute top-3 left-3 flex items-center pointer-events-none">
                                            <!-- Document Icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M7 8h10M7 12h10M7 16h10M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Submit Button --}}
                                <button type="submit"
                                    class="w-full bg-blue-600 text-white pl-10 rounded-lg font-medium hover:bg-blue-700 transition flex items-center justify-center gap-2">
                                    <!-- Update Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 4v16m8-8H4" />
                                    </svg>
                                    Update Category
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
