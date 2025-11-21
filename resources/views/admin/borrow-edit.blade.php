@extends('components.default')
@section('title', 'Edit Borrow Record | Admin Dashboard | LMIS')
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

            {{-- Main --}}
            <div class="lg:w-10/12 w-full">
                <div class="bg-white rounded-xl shadow-lg">
                    <div class="px-6 py-6">

                        <!-- Breadcrumb -->
                        <nav class="flex px-5 py-3 text-gray-700 border border-gray-200 rounded-lg bg-gray-50">
                            <ol class="inline-flex items-center space-x-1 md:space-x-2">

                                <li class="inline-flex items-center">
                                    <a href="{{ route('borrows.index') }}"
                                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">

                                        <!-- Book Icon -->
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                            <path d="M12 6v12M6 6h12M6 18h12" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>

                                        Borrows
                                    </a>
                                </li>

                                <li aria-current="page">
                                    <div class="flex items-center">
                                        <svg class="w-3 h-3 mx-1 text-gray-400" fill="none"
                                            viewBox="0 0 6 10" stroke="currentColor" stroke-width="2">
                                            <path d="m1 9 4-4-4-4" stroke-linecap="round" stroke-linejoin="round"/>
                                        </svg>
                                        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2">Edit Borrow</span>
                                    </div>
                                </li>
                            </ol>
                        </nav>

                        {{-- Edit Form --}}
                        <div class="mt-6">
                            <form action="{{ route('borrows.update', $borrow->id) }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PUT')

                                {{-- User --}}
                                <div class="relative space-y-1">
                                    <label class="text-sm font-medium text-gray-700">User</label>
                                    <div class="relative">
                                        <select name="user_id" class="w-full pl-10 bg-gray-50 rounded-lg border-gray-600">
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}"
                                                    {{ $borrow->user_id == $user->id ? 'selected' : '' }}>
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <!-- User Icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16 14a4 4 0 10-8 0v5h8v-5zM12 6a4 4 0 110-8 4 4 0 010 8z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Book --}}
                                <div class="relative space-y-1">
                                    <label class="text-sm font-medium text-gray-700">Book</label>
                                    <div class="relative">
                                        <select name="book_id" class="w-full pl-10 bg-gray-50 rounded-lg border-gray-600">
                                            @foreach($books as $book)
                                                <option value="{{ $book->id }}"
                                                    {{ $borrow->book_id == $book->id ? 'selected' : '' }}>
                                                    {{ $book->title }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <!-- Book Icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Borrow Date (readonly) --}}
                                <div class="relative space-y-1">
                                    <label class="text-sm font-medium text-gray-700">Borrow Date</label>
                                    <div class="relative">
                                        <input type="text" value="{{ $borrow->borrow_date }}"
                                            readonly
                                            class="w-full pl-10 bg-gray-200 cursor-not-allowed rounded-lg border-gray-600">

                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <!-- Calendar Icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M8 7v2m4-2v2m4-2v2M4 11h16M4 5h16v14H4V5z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Due Date (readonly) --}}
                                <div class="relative space-y-1">
                                    <label class="text-sm font-medium text-gray-700">Due Date</label>
                                    <div class="relative">
                                        <input type="text" value="{{ $borrow->due_date }}"
                                            readonly
                                            class="w-full pl-10 bg-gray-200 cursor-not-allowed rounded-lg border-gray-600">

                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <!-- Clock Icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M12 6v6l4 2M12 22a10 10 0 110-20 10 10 0 010 20z" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Return Date --}}
                                <div class="relative space-y-1">
                                    <label class="text-sm font-medium text-gray-700">Return Date</label>
                                    <div class="relative">
                                        <input type="datetime-local" name="return_date"
                                            value="{{ $borrow->return_date ? date('Y-m-d\TH:i', strtotime($borrow->return_date)) : '' }}"
                                            class="w-full pl-10 bg-gray-50 rounded-lg border-gray-600">

                                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                            <!-- Return Icon -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M3 10h18m-7-7l7 7-7 7" />
                                            </svg>
                                        </div>
                                    </div>
                                </div>

                                {{-- Submit --}}
                                <button type="submit"
                                    class="w-full bg-blue-600 text-white pl-10 rounded-lg font-medium hover:bg-blue-700 transition flex items-center justify-center gap-2">

                                    <!-- Update Icon -->
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 4v16m8-8H4" />
                                    </svg>

                                    Update Borrow Record
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
