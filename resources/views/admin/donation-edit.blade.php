@extends('components.default')

@section('title', 'Edit Donation | Admin Dashboard | LMIS')

@section('content')

<section>
    <div class="min-h-screen pt-24">

        @include('components.admin.topnav')

        <div class="flex flex-col lg:flex-row px-4 lg:px-10 pb-4 gap-6">

            <div class="lg:w-2/12 w-full">
                @include('components.admin.sidebar')
            </div>

            <div class="lg:w-10/12 w-full">

                <div class="bg-white rounded-xl shadow-lg px-6 py-6">

                    <h2 class="text-2xl font-semibold text-gray-800 mb-5">Edit Donation</h2>

                    <form action="{{ route('donations.update', $donation->id) }}" method="POST" class="space-y-5">
                        @csrf
                        @method('PUT')

                        {{-- Donor --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Donor</label>
                            <div class="flex items-center bg-gray-50 border border-gray-300 rounded-lg px-3">
                                <select name="donor_id" class="w-full p-2.5 bg-transparent focus:outline-none text-sm">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ $donation->donor_id == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Book Title --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Book Title</label>
                            <div class="flex items-center bg-gray-50 border border-gray-300 rounded-lg px-3">
                                <input type="text" name="book_title" value="{{ $donation->book_title }}"
                                       class="w-full p-2.5 bg-transparent focus:outline-none text-sm" required>
                            </div>
                        </div>

                        {{-- Author --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Author</label>
                            <div class="flex items-center bg-gray-50 border border-gray-300 rounded-lg px-3">
                                <input type="text" name="author" value="{{ $donation->author->name }}"
                                       class="w-full p-2.5 bg-transparent focus:outline-none text-sm" required>
                            </div>
                        </div>

                        {{-- Publisher --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Publisher</label>
                            <div class="flex items-center bg-gray-50 border border-gray-300 rounded-lg px-3">
                                <input type="text" name="publisher" value="{{ $donation->publisher->name }}"
                                       class="w-full p-2.5 bg-transparent focus:outline-none text-sm">
                            </div>
                        </div>

                        {{-- Year Published --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Year Published</label>
                            <div class="flex items-center bg-gray-50 border border-gray-300 rounded-lg px-3">
                                <input type="number" name="year_published" value="{{ $donation->year_published }}"
                                       class="w-full p-2.5 bg-transparent focus:outline-none text-sm" required>
                            </div>
                        </div>

                        {{-- Quantity --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Quantity</label>
                            <div class="flex items-center bg-gray-50 border border-gray-300 rounded-lg px-3">
                                <input type="number" name="quantity" value="{{ $donation->quantity }}"
                                       class="w-full p-2.5 bg-transparent focus:outline-none text-sm" required>
                            </div>
                        </div>

                        {{-- Status --}}
                        <div class="space-y-2">
                            <label class="text-sm font-medium text-gray-700">Status</label>
                            <div class="flex items-center bg-gray-50 border border-gray-300 rounded-lg px-3">
                                <select name="status" class="w-full p-2.5 bg-transparent focus:outline-none text-sm">
                                    <option value="pending"   {{ $donation->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved"  {{ $donation->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                                    <option value="rejected"  {{ $donation->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>
                            </div>
                        </div>

                        {{-- Submit --}}
                        <button type="submit"
                            class="w-full inline-flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700
                                   text-white font-medium rounded-lg text-sm px-5 py-2.5 shadow-md hover:shadow-lg transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                 stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M5 13l4 4L19 7" />
                            </svg>
                            Update Donation
                        </button>

                    </form>

                </div>

            </div>

        </div>
    </div>
</section>

@endsection
