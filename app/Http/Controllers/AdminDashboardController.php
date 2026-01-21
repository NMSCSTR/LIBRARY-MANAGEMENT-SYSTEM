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
        $keyword = trim($request->search);
        $categoryFilter = $request->category;
        $authorFilter = $request->author;
        $statusFilter = $request->status;

        // Condition: Only search if at least one input is provided
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
            // Return an empty paginator instance to prevent 'undefined' errors in Blade
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
            'isSearching'       => $isSearching,
            'categories'        => Category::orderBy('name')->get(),
            'authors'           => Author::orderBy('name')->get(),
        ]);
    }
}
