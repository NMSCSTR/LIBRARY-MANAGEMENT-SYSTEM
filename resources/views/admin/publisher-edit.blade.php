@extends('components.default')

@section('title', 'Edit Publisher | Admin Dashboard | LMIS')

@section('content')

<section>
    <div class="min-h-screen pt-24">

        {{-- Top Navigation --}}
        @include('components.admin.topnav')

        <div class="flex flex-col lg:flex-row px-4 lg:px-10 pb-4 gap-6">

            {{-- Sidebar --}}
            <div class="lg:w-2/12 w-full">
                @include('components.admin.sidebar')
            </div>

            {{-- Main Content --}}
            <div class="lg:w-10/12 w-full">

                <div class="bg-white rounded-xl shadow-lg px-6 py-6">

                    <h2 class="text-2xl font-semibold text-gray-800 mb-5">Edit Publisher</h2>

                    <form action="{{ route('publishers.update', $publisher->id) }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')

                        {{-- Name --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Publisher Name</label>
                            <div class="flex items-center bg-gray-50 border border-gray-300 rounded-lg px-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M12 6v12m6-6H6" />
                                </svg>
                                <input type="text" name="name" value="{{ $publisher->name }}"
                                    class="w-full p-2.5 bg-transparent focus:outline-none text-sm" required>
                            </div>
                        </div>

                        {{-- Address --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Address</label>
                            <div class="flex items-center bg-gray-50 border border-gray-300 rounded-lg px-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 7h18M3 12h18M3 17h18" />
                                </svg>
                                <input type="text" name="address" value="{{ $publisher->address }}"
                                    class="w-full p-2.5 bg-transparent focus:outline-none text-sm">
                            </div>
                        </div>

                        {{-- Contact Person --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Contact Person</label>
                            <div class="flex items-center bg-gray-50 border border-gray-300 rounded-lg px-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4.5 20.25a9 9 0 1115 0v.75H4.5v-.75z" />
                                </svg>
                                <input type="text" name="contact_person" value="{{ $publisher->contact_person }}"
                                    class="w-full p-2.5 bg-transparent focus:outline-none text-sm">
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Email</label>
                            <div class="flex items-center bg-gray-50 border border-gray-300 rounded-lg px-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4 6h16M4 12h16m-7 6h7" />
                                </svg>
                                <input type="email" name="email" value="{{ $publisher->email }}"
                                    class="w-full p-2.5 bg-transparent focus:outline-none text-sm">
                            </div>
                        </div>

                        {{-- Phone --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Phone Number</label>
                            <div class="flex items-center bg-gray-50 border border-gray-300 rounded-lg px-3">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-500">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 8l7.89 7.89c.27.27.72.27.99 0L21 8M5 9l7 7 7-7" />
                                </svg>
                                <input type="text" name="phone" value="{{ $publisher->phone }}"
                                    class="w-full p-2.5 bg-transparent focus:outline-none text-sm">
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700
                                   text-white font-medium rounded-lg text-sm px-5 py-2.5 shadow-md hover:shadow-lg transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Update Publisher
                        </button>

                    </form>

                </div>

            </div>

        </div>
    </div>
</section>

@endsection
