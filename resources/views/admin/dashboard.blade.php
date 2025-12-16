@extends('components.default')

@section('title', 'Admin Dashboard | LMIS')

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

                {{-- Search Results --}}
                @if($keyword)
                <div class="bg-white rounded-xl shadow-lg p-6 mt-6">

                    <h3 class="text-lg font-semibold mb-4">
                        Search Results for:
                        <span class="text-indigo-600">"{{ $keyword }}"</span>
                    </h3>

                    @forelse($books as $book)
                    <div class="border-b pb-4 mb-4">
                        <h4 class="text-xl font-semibold text-indigo-900">
                            {{ $book->title }}
                        </h4>

                        <p class="text-sm text-gray-600">ISBN: {{ $book->isbn }}</p>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-2 text-sm">
                            <p><strong>Author:</strong> {{ $book->author->name ?? 'N/A' }}</p>
                            <p><strong>Category:</strong> {{ $book->category->name ?? 'N/A' }}</p>
                            <p><strong>Publisher:</strong> {{ $book->publisher->name ?? 'N/A' }}</p>
                            <p><strong>Supplier:</strong> {{ $book->supplier->name ?? 'N/A' }}</p>
                        </div>

                        <div class="mt-3">
                            <p class="font-semibold text-sm mb-2">Copies & Locations:</p>
                            <ul class="list-disc list-inside text-sm">
                                @foreach($book->copies as $copy)
                                <li>
                                    Copy #{{ $copy->copy_number }}
                                    — Shelf: <strong>{{ $copy->shelf_location }}</strong>
                                    —
                                    <span
                                        class="{{ $copy->status === 'available' ? 'text-green-600' : 'text-red-600' }}">
                                        {{ ucfirst($copy->status) }}
                                    </span>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500">No books found.</p>
                    @endforelse
                </div>
                @endif


                {{-- Dashboard Cards --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">

                    {{-- Total Users --}}
                    <div class="bg-white rounded-xl shadow-lg px-6 py-6 flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Total Users</p>
                            <h3 class="text-3xl font-semibold text-indigo-900">
                                {{ $totalUsers }}
                            </h3>
                        </div>
                        <span class="material-icons-outlined text-4xl text-blue-500">group</span>
                    </div>

                    {{-- Total Books --}}
                    <div class="bg-white rounded-xl shadow-lg px-6 py-6 flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Total Books</p>
                            <h3 class="text-3xl font-semibold text-indigo-900">
                                {{ $totalBooks }}
                            </h3>
                        </div>
                        <span class="material-icons-outlined text-4xl text-orange-500">menu_book</span>
                    </div>

                    {{-- Total Reservations --}}
                    <div class="bg-white rounded-xl shadow-lg px-6 py-6 flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Reservations</p>
                            <h3 class="text-3xl font-semibold text-indigo-900">
                                {{ $totalReservations }}
                            </h3>
                        </div>
                        <span class="material-icons-outlined text-4xl text-green-500">event_available</span>
                    </div>

                    {{-- Total Borrows --}}
                    <div class="bg-white rounded-xl shadow-lg px-6 py-6 flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Total Borrows</p>
                            <h3 class="text-3xl font-semibold text-indigo-900">
                                {{ $totalBorrows }}
                            </h3>
                        </div>
                        <span class="material-icons-outlined text-4xl text-blue-500">assignment_return</span>
                    </div>

                    {{-- Total Suppliers --}}
                    <div class="bg-white rounded-xl shadow-lg px-6 py-6 flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Suppliers</p>
                            <h3 class="text-3xl font-semibold text-indigo-900">
                                {{ $totalSuppliers }}
                            </h3>
                        </div>
                        <span class="material-icons-outlined text-4xl text-purple-500">storefront</span>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@endsection
