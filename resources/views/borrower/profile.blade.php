@extends('components.default')

@section('title', 'My Profile | LMIS')

@section('content')
<section class="bg-gray-100 min-h-screen py-12">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-3xl shadow-lg overflow-hidden">
            <div class="px-8 py-6 bg-indigo-50 border-b border-indigo-200">
                <h1 class="text-3xl font-extrabold text-indigo-900">My Profile</h1>
                <p class="mt-1 text-sm text-indigo-700">Update your personal information and password here.</p>
            </div>

            <div class="px-8 py-6">
                @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded mb-6 shadow-sm">
                    {{ session('success') }}
                </div>
                @endif

                <form action="{{ route('borrower.profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Personal Info Section -->
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Personal Information</h2>
                        <div class="grid grid-cols-1 gap-4">

                            <div>
                                <label class="text-sm font-medium text-gray-700">Name</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                    placeholder="John Doe"
                                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}"
                                    placeholder="you@example.com"
                                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-700">Contact Number</label>
                                <input type="text" name="contact_number" value="{{ old('contact_number', $user->contact_number) }}"
                                    placeholder="+1 234 567 890"
                                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                @error('contact_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-700">Address</label>
                                <input type="text" name="address" value="{{ old('address', $user->address) }}"
                                    placeholder="123 Main St, City, Country"
                                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                @error('address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                        </div>
                    </div>

                    <!-- Password Section -->
                    <div class="mb-6">
                        <h2 class="text-xl font-semibold text-gray-800 mb-4">Change Password</h2>
                        <div class="grid grid-cols-1 gap-4">

                            <div>
                                <label class="text-sm font-medium text-gray-700">New Password <span class="text-gray-400 text-xs">(leave blank to keep current)</span></label>
                                <input type="password" name="password"
                                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                                @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label class="text-sm font-medium text-gray-700">Confirm New Password</label>
                                <input type="password" name="password_confirmation"
                                    class="mt-1 block w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-indigo-500 focus:border-indigo-500 shadow-sm">
                            </div>

                        </div>
                    </div>

                    <button type="submit"
                        class="w-full mt-4 bg-indigo-600 text-white px-6 py-3 rounded-2xl font-semibold hover:bg-indigo-700 transition shadow-md">
                        Update Profile
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
