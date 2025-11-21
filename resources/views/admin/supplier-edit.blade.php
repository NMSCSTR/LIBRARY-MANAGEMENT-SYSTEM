@extends('components.default')

@section('content')

<div class="p-10">
    <h2 class="text-2xl font-bold mb-4">Edit Supplier</h2>

    <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        <input type="text" name="name" value="{{ $supplier->name }}" class="w-full border p-2 rounded">
        <input type="text" name="address" value="{{ $supplier->address }}" class="w-full border p-2 rounded">
        <input type="text" name="contact_person" value="{{ $supplier->contact_person }}" class="w-full border p-2 rounded">
        <input type="email" name="email" value="{{ $supplier->email }}" class="w-full border p-2 rounded">
        <input type="text" name="phone" value="{{ $supplier->phone }}" class="w-full border p-2 rounded">

        <button class="bg-blue-600 text-white px-4 py-2 rounded">Update</button>
    </form>

</div>

@endsection
