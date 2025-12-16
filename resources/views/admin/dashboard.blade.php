@extends('components.default')

@section('title', 'Admin Dashboard | LMIS')

@section('content')
<section class="bg-gray-50 min-h-screen pt-24">

    {{-- Top Navigation --}}
    @include('components.admin.topnav')

    <div class="flex flex-col lg:flex-row px-4 lg:px-10 pb-10 gap-6">

        {{-- Sidebar --}}
        <div class="lg:w-2/12 w-full">
            @include('components.admin.sidebar')
        </div>

        {{-- Main Content --}}
        <div class="lg:w-10/12 w-full space-y-8">

            {{-- Search Bar --}}
            <form method="GET" action="{{ route('admin.dashboard') }}">
                <div class="flex items-center gap-2 bg-white rounded-2xl shadow px-4 py-3">
                    <span class="material-icons-outlined text-gray-400">search</span>
                    <input
                        type="text"
                        name="search"
                        value="{{ $keyword }}"
                        placeholder="Search books, authors, categories, publishers..."
                        class="w-full border-0 focus:ring-0 text-sm"
                    >
                    <button
                        type="submit"
                        class="bg-indigo-600 text-white px-6 py-2 rounded-xl text-sm font-medium hover:bg-indigo-700 transition"
                    >
                        Search
                    </button>
                </div>
            </form>

            {{-- Search Results --}}
            @if($keyword)
            <div class="bg-white rounded-2xl shadow p-6">

                <h3 class="text-lg font-semibold mb-4">
                    Search Results for
                    <span class="text-indigo-600">“{{ $keyword }}”</span>
                </h3>

                <div class="space-y-4">
                    @forelse($books as $book)
                    <div class="border rounded-xl p-5 hover:shadow-md transition">

                        <div class="flex justify-between items-start">
                            <div>
                                <h4 class="text-lg font-semibold text-indigo-900">
                                    {{ $book->title }}
                                </h4>
                                <p class="text-xs text-gray-500">ISBN: {{ $book->isbn }}</p>
                            </div>

                            <span class="text-xs bg-indigo-100 text-indigo-700 px-3 py-1 rounded-full">
                                {{ $book->category->name ?? 'N/A' }}
                            </span>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mt-3 text-sm text-gray-700">
                            <p><strong>Author:</strong> {{ $book->author->name ?? 'N/A' }}</p>
                            <p><strong>Publisher:</strong> {{ $book->publisher->name ?? 'N/A' }}</p>
                            <p><strong>Supplier:</strong> {{ $book->supplier->name ?? 'N/A' }}</p>
                        </div>

                        <div class="mt-4">
                            <p class="text-sm font-semibold mb-2">Copies</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($book->copies as $copy)
                                <span class="text-xs px-3 py-1 rounded-full
                                    {{ $copy->status === 'available'
                                        ? 'bg-green-100 text-green-700'
                                        : 'bg-red-100 text-red-700' }}">
                                    #{{ $copy->copy_number }} · {{ ucfirst($copy->status) }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @empty
                    <p class="text-gray-500 text-sm">No books found.</p>
                    @endforelse
                </div>
            </div>
            @endif

            {{-- Dashboard Stats --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

                @php
                $cards = [
                    ['label'=>'Total Users','value'=>$totalUsers,'icon'=>'group','color'=>'blue'],
                    ['label'=>'Total Books','value'=>$totalBooks,'icon'=>'menu_book','color'=>'orange'],
                    ['label'=>'Reservations','value'=>$totalReservations,'icon'=>'event_available','color'=>'green'],
                    ['label'=>'Borrows','value'=>$totalBorrows,'icon'=>'assignment_return','color'=>'indigo'],
                    ['label'=>'Suppliers','value'=>$totalSuppliers,'icon'=>'storefront','color'=>'purple'],
                ];
                @endphp

                @foreach($cards as $card)
                <div class="relative bg-white rounded-2xl shadow p-6 hover:shadow-xl transition">

                    <div class="absolute left-0 top-0 h-full w-1 bg-{{ $card['color'] }}-500 rounded-l-2xl"></div>

                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-sm text-gray-500">{{ $card['label'] }}</p>
                            <h3 class="text-3xl font-bold text-gray-800">
                                {{ $card['value'] }}
                            </h3>
                        </div>

                        <span class="material-icons-outlined text-4xl text-{{ $card['color'] }}-500">
                            {{ $card['icon'] }}
                        </span>
                    </div>
                </div>
                @endforeach

            </div>

        </div>
    </div>
</section>
@endsection
