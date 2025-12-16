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
