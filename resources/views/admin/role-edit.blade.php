@extends('components.default')
@section('title', 'Edit Role | Admin Dashboard | LMIS')
@section('content')

<section>
    <div class="min-h-screen pt-24">
        @include('components.admin.topnav')

        <div class="flex flex-col lg:flex-row px-4 lg:px-10 pb-4 gap-6">

            <div class="lg:w-2/12">
                @include('components.admin.sidebar')
            </div>

            <div class="lg:w-10/12">
                <div class="bg-white rounded-xl shadow-lg p-6">

                    <h2 class="text-xl font-bold mb-4">Edit Role</h2>

                    <form action="{{ route('roles.update', $role->id) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')

                        <div>
                            <label class="text-sm font-medium">Role Name</label>
                            <input type="text" name="name" value="{{ $role->name }}"
                                class="w-full p-2.5 border rounded-lg bg-gray-50" required>
                        </div>

                        <button type="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded-lg">
                            Save Changes
                        </button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</section>

@endsection
