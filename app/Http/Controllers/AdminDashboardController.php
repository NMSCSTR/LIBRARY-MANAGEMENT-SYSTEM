<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Reservation;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Category;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        // Get search and filter inputs
        $keyword = trim($request->search);
        $categoryFilter = $request->category;
        $authorFilter = $request->author;
        $statusFilter = $request->status;

        // Determine if we should show results
        // We only proceed if there is a keyword or a specific filter selected
        $isSearching = !empty($keyword) || !empty($categoryFilter) || !empty($authorFilter) || !empty($statusFilter);

        if ($isSearching) {
            $books = Book::with(['author', 'category', 'publisher', 'supplier', 'copies'])
                ->when($keyword, function ($query) use ($keyword) {
                    $query->where(function ($q) use ($keyword) {
                        $q->where('title', 'like', "%{$keyword}%")
                          ->orWhere('isbn', 'like', "%{$keyword}%")
                          ->orWhereHas('author', fn($q2) => $q2->where('name', 'like', "%{$keyword}%"))
                          ->orWhereHas('category', fn($q2) => $q2->where('name', 'like', "%{$keyword}%"));
                    });
                })
                ->when($categoryFilter, function ($query) use ($categoryFilter) {
                    $query->where('category_id', $categoryFilter);
                })
                ->when($authorFilter, function ($query) use ($authorFilter) {
                    $query->where('author_id', $authorFilter);
                })
                ->when($statusFilter, function ($query) use ($statusFilter) {
                    $query->whereHas('copies', function ($q) use ($statusFilter) {
                        $q->where('status', $statusFilter);
                    });
                })
                ->paginate(10)
                ->withQueryString();
        } else {
            // If not searching, return an empty paginator
            $books = new LengthAwarePaginator([], 0, 10);
        }

        return view('admin.dashboard', [
            'totalUsers'        => User::count(),
            'totalBooks'        => Book::count(),
            'totalReservations' => Reservation::count(),
            'totalBorrows'      => Borrow::count(),
            'totalSuppliers'    => Supplier::count(),
            'books'             => $books,
            'keyword'           => $keyword,
            'categories'        => Category::orderBy('name')->get(),
            'authors'           => Author::orderBy('name')->get(),
            'summary'           => [ // Added to prevent errors in your blade cards
                'borrowed' => Borrow::where('status', 'borrowed')->count(),
                'overdue'  => Borrow::where('status', 'overdue')->count(),
                'available' => \App\Models\BookCopy::where('status', 'available')->count(),
                'reserved'  => Reservation::count(),
            ]
        ]);
    }
}
