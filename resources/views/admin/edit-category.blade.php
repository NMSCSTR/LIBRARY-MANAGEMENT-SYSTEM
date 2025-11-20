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

                                <div class="space-y-1">
                                    <label class="text-sm font-medium text-gray-700">Category Name</label>
                                    <input type="text" name="name" value="{{ old('name', $category->name) }}" required
                                        class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">
                                </div>

                                <div class="space-y-1">
                                    <label class="text-sm font-medium text-gray-700">Description</label>
                                    <textarea name="description"
                                        class="w-full rounded-lg border-gray-300 focus:ring-blue-500 focus:border-blue-500">{{ old('description', $category->description) }}</textarea>
                                </div>

                                <button type="submit"
                                    class="w-full bg-blue-600 text-white py-2.5 rounded-lg font-medium hover:bg-blue-700 transition">
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
