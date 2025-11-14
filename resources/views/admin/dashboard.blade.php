@extends('components.default')

@section('title', 'Admin Dashboard | LMIS')

@section('content')
<section>
    <div class="min-h-screen pt-24">
        {{-- @include('components.admin.bg') --}}
        {{-- Include Top Navigation --}}
        @include('components.admin.topnav')
        <div class="flex flex-col lg:flex-row px-4 lg:px-10 pb-4 gap-6">

            {{-- Include Sidebar --}}
            <div class="lg:w-2/12 w-full">
                @include('components.admin.sidebar')
            </div>

            {{-- Main Content --}}
            <div class="lg:w-10/12 w-full">
                <div class="flex flex-col lg:flex-row gap-6 mb-6" data-aos="zoom-in">
                    <!-- Welcome Card -->
                    <div
                        class="flex-1 bg-white rounded-xl shadow-md border border-gray-200 p-6 relative overflow-hidden">
                        <div class="absolute right-3 bottom-3 opacity-10 text-red-600 text-[110px] pointer-events-none">
                            <span class="material-icons-outlined">emoji_people</span>
                        </div>

                        <div class="border-l-4 border-red-500 pl-4">
                            <p class="text-lg text-gray-600">Welcome back</p>
                            {{-- <h2 class="text-3xl font-bold text-gray-900 mt-1">{{ auth()->user()->firstname }}</h2>
                            --}}
                        </div>

                        <div class="mt-5">
                            <span
                                class="inline-flex items-center bg-red-500 text-white px-4 py-2 rounded-lg shadow text-sm">
                                <i class="material-icons text-base mr-1">access_time</i>
                                {{-- {{ now()->setTimezone('Asia/Manila')->format('l, F j, Y h:i A') }} --}}
                            </span>
                        </div>
                    </div>

                    <!-- Inbox Card -->
                    <div
                        class="flex-1 bg-white rounded-xl shadow-md border border-gray-200 p-6 relative overflow-hidden">
                        <div
                            class="absolute right-3 bottom-3 opacity-10 text-orange-600 text-[110px] pointer-events-none">
                            <span class="material-icons-outlined">mail</span>
                        </div>

                        <div class="border-l-4 border-orange-500 pl-4">
                            <p class="text-lg text-gray-600">Inbox</p>
                            <h2 class="text-4xl font-bold text-gray-900 mt-1">23 Messages</h2>
                        </div>

                        <div class="mt-5">
                            <a href="#"
                                class="inline-flex items-center bg-orange-500 hover:bg-orange-600 transition text-white px-4 py-2 rounded-lg shadow text-sm">
                                <i class="material-icons text-base mr-1">mark_email_unread</i>
                                See Messages
                            </a>
                        </div>
                    </div>


                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6" data-aos="fade-up">
                    <!-- Total User -->
                    <div class="bg-white rounded-xl shadow-lg px-6 py-6 flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Total User</p>
                            <h3 class="text-3xl font-semibold text-indigo-900">123</h3> <!-- Placeholder Number -->
                        </div>
                        <span class="material-icons-outlined text-4xl text-blue-500">group</span>
                    </div>

                    <!-- Total Books -->
                    <div class="bg-white rounded-xl shadow-lg px-6 py-6 flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Total Books</p>
                            <h3 class="text-3xl font-semibold text-indigo-900">456</h3> <!-- Placeholder Number -->
                        </div>
                        <span class="material-icons-outlined text-4xl text-orange-500">event</span>
                    </div>

                    <!-- New Reservations -->
                    <div class="bg-white rounded-xl shadow-lg px-6 py-6 flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">New Reservations</p>
                            <h3 class="text-3xl font-semibold text-indigo-900">78</h3> <!-- Placeholder Number -->
                        </div>
                        <span class="material-icons-outlined text-4xl text-green-500">event_available</span>
                    </div>
                </div>

                <!-- New Sections -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6" data-aos="fade-up">
                    <!-- Total Borrows -->
                    <div class="bg-white rounded-xl shadow-lg px-6 py-6 flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Total Borrows</p>
                            <h3 class="text-3xl font-semibold text-indigo-900">102</h3> <!-- Placeholder Number -->
                        </div>
                        <span class="material-icons-outlined text-4xl text-blue-500">borrow</span>
                    </div>

                    <!-- Total Suppliers -->
                    <div class="bg-white rounded-xl shadow-lg px-6 py-6 flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Total Suppliers</p>
                            <h3 class="text-3xl font-semibold text-indigo-900">34</h3> <!-- Placeholder Number -->
                        </div>
                        <span class="material-icons-outlined text-4xl text-purple-500">storefront</span>
                    </div>

                    <!-- Total Donations -->
                    <div class="bg-white rounded-xl shadow-lg px-6 py-6 flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm">Total Donations</p>
                            <h3 class="text-3xl font-semibold text-indigo-900">58</h3> <!-- Placeholder Number -->
                        </div>
                        <span class="material-icons-outlined text-4xl text-yellow-500">volunteer_activism</span>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
