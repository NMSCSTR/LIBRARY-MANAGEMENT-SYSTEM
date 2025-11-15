@extends('components.default')

@section('title', 'Edit Author | Admin Dashboard | LMIS')

@section('content')

<section>
    <div class="min-h-screen pt-24">
        @include('components.admin.topnav')

        <div class="flex flex-col lg:flex-row px-4 lg:px-10 pb-4 gap-6">

            {{-- Sidebar --}}
            <div class="lg:w-2/12 w-full">
                @include('components.admin.sidebar')
            </div>

            {{-- Main Content --}}
            <div class="lg:w-10/12 w-full">

                <div class="bg-white rounded-xl shadow-lg px-6 py-6">

                    <h2 class="text-2xl font-semibold text-gray-800 mb-5">
                        Edit Author
                    </h2>

                    <!-- EDIT FORM -->
                    <form action="{{ route('authors.update', $author->id) }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')

                        <!-- Author Name -->
                        <div class="space-y-2">
                            <label for="name" class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                Author Name
                            </label>

                            <div
                                class="flex items-center bg-gray-50 border border-gray-300 rounded-lg px-3 dark:bg-gray-700 dark:border-gray-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500 dark:text-gray-300">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4.5 20.25a9 9 0 1115 0v.75H4.5v-.75z" />
                                </svg>

                                <input type="text" name="name" id="name"
                                    class="w-full p-2.5 text-sm bg-transparent focus:outline-none dark:text-white"
                                    value="{{ $author->name }}" required>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm px-5 py-2.5 shadow-md hover:shadow-lg transition-all">

                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5 13l4 4L19 7" />
                            </svg>

                            Update Author
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</section>

@endsection
