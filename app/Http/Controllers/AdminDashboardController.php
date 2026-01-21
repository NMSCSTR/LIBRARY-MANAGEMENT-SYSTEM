<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Reservation;
use App\Models\Supplier;
use App\Models\User;
use App\Models\Category;
use App\Models\Author;
use App\Models\BookCopy;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim($request->search);

        // We ONLY fetch books if a search keyword exists
        if (!empty($keyword)) {
            $books = Book::with(['author', 'category', 'copies'])
                ->where(function ($q) use ($keyword) {
                    $q->where('title', 'like', "%{$keyword}%")
                      ->orWhere('isbn', 'like', "%{$keyword}%")
                      ->orWhereHas('author', fn($q2) => $q2->where('name', 'like', "%{$keyword}%"))
                      ->orWhereHas('category', fn($q2) => $q2->where('name', 'like', "%{$keyword}%"));
                })
                ->paginate(10)
                ->withQueryString();
        } else {
            // Return an empty paginator so the Blade template doesn't crash
            $books = new LengthAwarePaginator([], 0, 10);
        }

        return view('admin.dashboard', [
            'totalUsers' => User::count(),
            'books'      => $books,
            'keyword'    => $keyword,
            // Passing actual library counts for your analytics cards
            'summary'    => [
                'borrowed'  => Borrow::where('status', 'borrowed')->count(),
                'overdue'   => Borrow::where('status', 'overdue')->count(),
                'available' => BookCopy::where('status', 'available')->count(),
                'reserved'  => Reservation::count(),
            ],
            'transactions' => Borrow::with('book', 'bookCopy')->latest()->take(5)->get(),
        ]);
    }
}
