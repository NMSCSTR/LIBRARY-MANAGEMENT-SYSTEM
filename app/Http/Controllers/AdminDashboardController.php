<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Reservation;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Category; // Add this
use App\Models\Author;   // Add this
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim($request->search);
        $categoryFilter = $request->category;
        $authorFilter = $request->author;
        $statusFilter = $request->status;

        $books = Book::with([
            'author',
            'category',
            'publisher',
            'supplier',
            'copies',
        ])
        // Filter by Keyword (Title, ISBN, etc.)
        ->when($keyword, function ($query) use ($keyword) {
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('isbn', 'like', "%{$keyword}%")
                    ->orWhereHas('author', fn($q2) => $q2->where('name', 'like', "%{$keyword}%"))
                    ->orWhereHas('category', fn($q2) => $q2->where('name', 'like', "%{$keyword}%"));
            });
        })
        // Filter by specific Category dropdown
        ->when($categoryFilter, function ($query) use ($categoryFilter) {
            $query->where('category_id', $categoryFilter);
        })
        // Filter by specific Author dropdown
        ->when($authorFilter, function ($query) use ($authorFilter) {
            $query->where('author_id', $authorFilter);
        })
        // Filter by Status dropdown (checks the 'copies' relationship)
        ->when($statusFilter, function ($query) use ($statusFilter) {
            $query->whereHas('copies', function ($q) use ($statusFilter) {
                $q->where('status', $statusFilter);
            });
        })
        ->get();

        return view('admin.dashboard', [
            // Dashboard Stats
            'totalUsers'        => User::count(),
            'totalBooks'        => Book::count(),
            'totalReservations' => Reservation::count(),
            'totalBorrows'      => Borrow::count(),
            'totalSuppliers'    => Supplier::count(),

            // Search Data
            'books'             => $books,
            'keyword'           => $keyword,

            // Missing Variables to fix the ErrorException
            'categories'        => Category::orderBy('name')->get(),
            'authors'           => Author::orderBy('name')->get(),
        ]);
    }
}
