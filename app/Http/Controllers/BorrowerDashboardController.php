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
        $keyword = $request->search;

        // Fetch books (always), filter only if searching
        $books = Book::with(['author', 'publisher', 'category', 'copies'])
            ->when($keyword, function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhereHas('author', fn($a) => $a->where('name', 'like', "%{$keyword}%"))
                  ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$keyword}%"))
                  ->orWhereHas('publisher', fn($p) => $p->where('name', 'like', "%{$keyword}%"));
            })
            ->paginate(10);

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

        // Create reservation
        Reservation::create([
            'user_id'     => auth()->id(),
            'book_id'     => $request->book_id,
            'copy_id'     => $bookCopy->id,
            'status'      => 'reserved',
            'reserved_at' => now(),
        ]);

        // Update copy status
        $bookCopy->update(['status' => 'reserved']);

        return back()->with('success', 'Book reserved successfully!');
    }

    /**
     * Cancel a reservation.
     */
    public function cancelReservation(Reservation $reservation)
    {
        if ($reservation->user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $bookCopy = BookCopy::find($reservation->copy_id);
        if ($bookCopy) {
            $bookCopy->update(['status' => 'available']);
        }

        $reservation->delete();

        return back()->with('success', 'Reservation canceled successfully.');
    }
}
