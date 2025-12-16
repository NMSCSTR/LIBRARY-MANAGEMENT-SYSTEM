<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Reservation;

class BorrowerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->search ?? null;

        // Search books
        $books = Book::with(['author', 'publisher', 'category', 'copies'])
            ->when($keyword, fn($q) => $q->where('title', 'like', "%$keyword%"))
            ->get();

        // Borrowed books
        $borrowed = Borrow::with(['book', 'bookCopy'])
            ->where('user_id', auth()->id())
            ->get();

        // Reserved books
        $reserved = Reservation::with('book')
            ->where('user_id', auth()->id())
            ->get();

        // Merge transactions
        $transactions = $borrowed->concat($reserved)->sortByDesc(fn($t) => $t->borrow_date ?? $t->reserved_at);

        // Dashboard summary
        $summary = [
            'borrowed'  => $borrowed->count(),
            'overdue'   => $borrowed->where('status', 'overdue')->count(),
            'available' => $books->sum(fn($b) => $b->copies->where('status', 'available')->count()),
            'reserved'  => $reserved->count(),
        ];

        return view('borrower.dashboard', compact('books', 'transactions', 'keyword', 'summary'));
    }

    public function reserve(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'copy_id' => 'required|exists:book_copies,id',
        ]);

        $bookCopy = Book::find($request->book_id)->copies()->where('id', $request->copy_id)->first();

        if (!$bookCopy || $bookCopy->status !== 'available') {
            return back()->with('error', 'This copy is not available for reservation.');
        }

        // Create reservation
        Reservation::create([
            'user_id' => auth()->id(),
            'book_id' => $request->book_id,
            'status' => 'reserved',
            'reserved_at' => now(),
        ]);

        // Mark copy as reserved
        $bookCopy->update(['status' => 'reserved']);

        return back()->with('success', 'Book reserved successfully!');
    }

    public function cancelReservation(Reservation $reservation)
    {
        // Ensure the user owns the reservation
        if ($reservation->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Update the book copy status back to available
        $bookCopy = $reservation->book->copies()->where('status', 'reserved')->first();
        if ($bookCopy) {
            $bookCopy->update(['status' => 'available']);
        }

        $reservation->delete();

        return back()->with('success', 'Reservation canceled successfully.');
    }
}
