@extends('components.default')

@section('title', 'Library Management Hub | LMIS')

@section('content')
<section>
    <div class="min-h-screen pt-24 bg-gray-50">
        @include('components.admin.topnav')

        <div class="flex flex-col lg:flex-row px-4 lg:px-10 pb-6 gap-6">

            {{-- Sidebar --}}
            <div class="lg:w-2/12 w-full">
                @include('components.admin.sidebar')
            </div>

            {{-- Main Content --}}
            <div class="lg:w-10/12 w-full">
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden">

                    {{-- Navigation Tabs --}}
                    <div class="border-b border-gray-200 bg-gray-50/50">
                        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="hubTab" role="tablist">
                            <li class="me-2"><button class="inline-block p-4 border-b-2 rounded-t-lg active-tab" data-target="#books-content">Books & Copies</button></li>
                            <li class="me-2"><button class="inline-block p-4 border-b-2 rounded-t-lg border-transparent" data-target="#authors-content">Authors</button></li>
                            <li class="me-2"><button class="inline-block p-4 border-b-2 rounded-t-lg border-transparent" data-target="#categories-content">Categories</button></li>
                            <li class="me-2"><button class="inline-block p-4 border-b-2 rounded-t-lg border-transparent" data-target="#publishers-content">Publishers</button></li>
                            <li class="me-2"><button class="inline-block p-4 border-b-2 rounded-t-lg border-transparent" data-target="#suppliers-content">Suppliers</button></li>
                        </ul>
                    </div>

                    <div id="hubTabContent">

                        {{-- 1. BOOKS & COPIES --}}
                        <div class="p-6 tab-pane" id="books-content">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold text-gray-800">Books Inventory</h2>
                                <div class="flex gap-2">
                                    <button data-modal-target="addBookModal" data-modal-toggle="addBookModal" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm flex items-center gap-2">
                                        + Add Book
                                    </button>
                                </div>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-sm text-left">
                                    <thead class="bg-gray-100 uppercase text-xs">
                                        <tr>
                                            <th class="p-3">Title</th>
                                            <th class="p-3">Author</th>
                                            <th class="p-3">Total Copies</th>
                                            <th class="p-3 text-right">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($books as $book)
                                        <tr class="border-b">
                                            <td class="p-3 font-medium">{{ $book->title }}</td>
                                            <td class="p-3">{{ $book->author?->name ?? 'N/A' }}</td>
                                            <td class="p-3 font-bold text-blue-600">{{ $book->copies?->count() ?? 0 }}</td>
                                            <td class="p-3 text-right flex justify-end gap-2">
                                                <a href="{{ route('books.edit', $book->id) }}" class="text-blue-600 text-xs">Edit</a>
                                                <button data-id="{{ $book->id }}" class="delete-book-btn text-red-600 text-xs">Delete</button>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- 2. AUTHORS --}}
                        <div class="p-6 hidden tab-pane" id="authors-content">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold text-gray-800">Authors Registry</h2>
                                <button data-modal-target="addAuthorModal" data-modal-toggle="addAuthorModal" class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm">+ Add Author</button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                @foreach($authors as $author)
                                <div class="p-4 border rounded-xl flex justify-between items-center">
                                    <span class="font-bold">{{ $author->name }}</span>
                                    <a href="{{ route('authors.edit', $author->id) }}" class="text-blue-600 text-xs italic">Edit</a>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- 3. CATEGORIES --}}
                        <div class="p-6 hidden tab-pane" id="categories-content">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold text-gray-800">Categories</h2>
                                <button data-modal-target="addCategoryModal" data-modal-toggle="addCategoryModal" class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm">+ Add Category</button>
                            </div>
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                @foreach($categories as $category)
                                <div class="p-4 bg-gray-50 border rounded-xl text-center">
                                    <span class="block font-bold text-gray-700">{{ $category->name }}</span>
                                    <a href="{{ route('categories.edit', $category->id) }}" class="text-xs text-blue-500">Edit</a>
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- 4. PUBLISHERS --}}
                        <div class="p-6 hidden tab-pane" id="publishers-content">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold text-gray-800">Publishers</h2>
                                <button data-modal-target="addPublisherModal" data-modal-toggle="addPublisherModal" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm">+ Add Publisher</button>
                            </div>
                            <table class="w-full text-sm text-left">
                                <thead class="bg-gray-50"><tr><th class="p-3">Name</th><th class="p-3">Contact</th><th class="p-3">Actions</th></tr></thead>
                                <tbody>
                                    @foreach($publishers as $pub)
                                    <tr class="border-b">
                                        <td class="p-3 font-medium">{{ $pub->name }}</td>
                                        <td class="p-3 text-xs">{{ $pub->email }}</td>
                                        <td class="p-3"><a href="{{ route('publishers.edit', $pub->id) }}" class="text-blue-600">Edit</a></td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        {{-- 5. SUPPLIERS --}}
                        <div class="p-6 hidden tab-pane" id="suppliers-content">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold text-gray-800">Suppliers</h2>
                                <button data-modal-target="addSupplierModal" data-modal-toggle="addSupplierModal" class="bg-orange-600 text-white px-4 py-2 rounded-lg text-sm">+ Add Supplier</button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($suppliers as $sup)
                                <div class="p-4 border rounded-xl flex justify-between items-center">
                                    <span>{{ $sup->name }}</span>
                                    <a href="{{ route('suppliers.edit', $sup->id) }}" class="text-blue-600 text-xs">Edit</a>
                                </div>
                                @endforeach
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ==========================================
         MODALS SECTION (One for each resource)
         ========================================== --}}

    {{-- ADD BOOK MODAL --}}
    <div id="addBookModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="w-full max-w-lg p-6 bg-white rounded-2xl shadow-xl">
            <h3 class="text-xl font-bold mb-4">Add New Book</h3>
            <form action="{{ route('books.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="text" name="title" class="w-full border rounded-lg p-2" placeholder="Book Title" required>
                <input type="text" name="isbn" class="w-full border rounded-lg p-2" placeholder="ISBN">
                <input type="number" name="copies_available" value="1" min="1" class="w-full border rounded-lg p-2" required>
                <select name="author_id" class="w-full border rounded-lg p-2" required>
                    <option value="">Select Author</option>@foreach($authors as $author)<option value="{{ $author->id }}">{{ $author->name }}</option>@endforeach
                </select>
                <div class="flex gap-2">
                    <select name="category_id" class="w-full border rounded-lg p-2" required>
                        <option value="">Category</option>@foreach($categories as $category)<option value="{{ $category->id }}">{{ $category->name }}</option>@endforeach
                    </select>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg">Save Book</button>
                <button type="button" data-modal-toggle="addBookModal" class="w-full text-gray-500 text-sm">Cancel</button>
            </form>
        </div>
    </div>

    {{-- ADD AUTHOR MODAL --}}
    <div id="addAuthorModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="w-full max-w-md p-6 bg-white rounded-2xl">
            <h3 class="text-xl font-bold mb-4 text-green-700">Add New Author</h3>
            <form action="{{ route('authors.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="text" name="name" class="w-full border rounded-lg p-2" placeholder="Author's Full Name" required>
                <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg">Add Author</button>
                <button type="button" data-modal-toggle="addAuthorModal" class="w-full text-gray-500 text-sm">Cancel</button>
            </form>
        </div>
    </div>

    {{-- ADD CATEGORY MODAL --}}
    <div id="addCategoryModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="w-full max-w-md p-6 bg-white rounded-2xl">
            <h3 class="text-xl font-bold mb-4 text-purple-700">Add New Category</h3>
            <form action="{{ route('categories.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="text" name="name" class="w-full border rounded-lg p-2" placeholder="Category Name" required>
                <textarea name="description" class="w-full border rounded-lg p-2" placeholder="Description (Optional)"></textarea>
                <button type="submit" class="w-full bg-purple-600 text-white py-2 rounded-lg">Save Category</button>
            </form>
        </div>
    </div>

    {{-- ADD SUPPLIER MODAL --}}
    <div id="addSupplierModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="w-full max-w-md p-6 bg-white rounded-2xl">
            <h3 class="text-xl font-bold mb-4 text-orange-700">Add New Supplier</h3>
            <form action="{{ route('suppliers.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="text" name="name" class="w-full border rounded-lg p-2" placeholder="Supplier Name" required>
                <input type="text" name="contact_person" class="w-full border rounded-lg p-2" placeholder="Contact Person">
                <button type="submit" class="w-full bg-orange-600 text-white py-2 rounded-lg">Add Supplier</button>
            </form>
        </div>
    </div>

    {{-- ADD PUBLISHER MODAL --}}
    <div id="addPublisherModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/40">
        <div class="w-full max-w-md p-6 bg-white rounded-2xl">
            <h3 class="text-xl font-bold mb-4 text-indigo-700">Add New Publisher</h3>
            <form action="{{ route('publishers.store') }}" method="POST" class="space-y-4">
                @csrf
                <input type="text" name="name" class="w-full border rounded-lg p-2" placeholder="Publisher Name" required>
                <input type="email" name="email" class="w-full border rounded-lg p-2" placeholder="Email">
                <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-lg">Add Publisher</button>
            </form>
        </div>
    </div>

</section>
@endsection

@push('scripts')
@include('components.alerts')
<style> .active-tab { border-bottom: 2px solid #2563eb; color: #2563eb; } </style>
<script>
    // Tab Logic
    document.querySelectorAll('[data-target]').forEach(tab => {
        tab.addEventListener('click', function() {
            document.querySelectorAll('[data-target]').forEach(t => t.classList.remove('active-tab'));
            document.querySelectorAll('.tab-pane').forEach(p => p.classList.add('hidden'));
            this.classList.add('active-tab');
            document.querySelector(this.dataset.target).classList.remove('hidden');
        });
    });

    // Simple Modal Toggle (for buttons that don't use Flowbite)
    document.querySelectorAll('[data-modal-toggle]').forEach(btn => {
        btn.addEventListener('click', () => {
            const target = btn.dataset.modalTarget;
            document.getElementById(target).classList.toggle('hidden');
        });
    });
</script>
@endpush
