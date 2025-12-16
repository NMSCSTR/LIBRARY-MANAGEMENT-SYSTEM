<?php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Reservation;
use App\Models\BookCopy;
use Illuminate\Http\Request;

class BorrowerDashboardController extends Controller
{
    /**
     * Display the Borrower Dashboard.
     */
    public function index(Request $request)
    {
        $keyword = $request->search ?? null;

        // Fetch books with related data
        $books = Book::with(['author', 'publisher', 'category', 'copies'])
            ->when($keyword, fn($q) => $q->where('title', 'like', "%$keyword%"))
            ->get();

        // Fetch borrowed books for the logged-in user
        $borrowed = Borrow::with(['book', 'bookCopy'])
            ->where('user_id', auth()->id())
            ->get();

        // Fetch reservations for the logged-in user, including copy
        $reserved = Reservation::with(['book', 'copy'])
            ->where('user_id', auth()->id())
            ->get();

        // Merge borrowed + reserved for transaction list
        $transactions = $borrowed->concat($reserved)
            ->sortByDesc(fn($t) => $t->borrow_date ?? $t->reserved_at);

        // Dashboard summary counts
        $summary = [
            'borrowed'  => $borrowed->count(),
            'overdue'   => $borrowed->where('status', 'overdue')->count(),
            'available' => $books->sum(fn($b) => $b->copies->where('status', 'available')->count()),
            'reserved'  => $reserved->count(),
        ];

        return view('borrower.dashboard', compact('books', 'transactions', 'keyword', 'summary'));
    }

    /**
     * Reserve a book copy.
     */
    public function reserve(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'copy_id' => 'required|exists:book_copies,id',
        ]);

        // Get the exact copy
        $bookCopy = BookCopy::where('id', $request->copy_id)
            ->where('book_id', $request->book_id)
            ->first();

        if (!$bookCopy || $bookCopy->status !== 'available') {
            return back()->with('error', 'This copy is not available for reservation.');
        }

        // Create reservation with copy_id
        Reservation::create([
            'user_id'     => auth()->id(),
            'book_id'     => $request->book_id,
            'copy_id'     => $bookCopy->id, // store exact copy
            'status'      => 'reserved',
            'reserved_at' => now(),
        ]);

        // Update copy status to reserved
        $bookCopy->update(['status' => 'reserved']);

        return back()->with('success', 'Book reserved successfully!');
    }

    /**
     * Cancel a reservation.
     */
    public function cancelReservation(Reservation $reservation)
    {
        // Ensure the user owns the reservation
        if ($reservation->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        // Retrieve the reserved copy
        $bookCopy = BookCopy::find($reservation->copy_id);
        if ($bookCopy) {
            $bookCopy->update(['status' => 'available']);
        }

        // Delete the reservation
        $reservation->delete();

        return back()->with('success', 'Reservation canceled successfully.');
    }
}
