<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Reservation;
use App\Models\BookCopy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $userId = Auth::id();

        // Fetch all books for the Instant Filter to work with
        // We eager load copies to check availability in the UI
        $books = Book::with(['author', 'category', 'copies'])->get();

        return view('borrower.dashboard', [
            'books' => $books,
            'summary' => [
                'borrowed'  => Borrow::where('user_id', $userId)->where('status', 'borrowed')->count(),
                'overdue'   => Borrow::where('user_id', $userId)->where('status', 'overdue')->count(),
                'available' => BookCopy::where('status', 'available')->count(),
                'reserved'  => Reservation::where('user_id', $userId)->count(),
            ],
            'transactions' => Borrow::where('user_id', $userId)
                ->with(['book', 'bookCopy'])
                ->latest()
                ->take(10)
                ->get(),
        ]);
    }
}
