<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;

class BorrowerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->search ?? null;

        // Search books
        $books = Book::with(['author', 'publisher', 'category', 'copies'])
            ->when($keyword, fn($q) => $q->where('title', 'like', "%$keyword%"))
            ->get();

        // Merge Borrow and Reservation records
        $borrowed = Borrow::with(['book', 'bookCopy'])
            ->where('user_id', auth()->id())
            ->get()
            ->map(fn($b) => [
                'id' => $b->id,
                'book_title' => $b->book->title,
                'copy_number' => $b->bookCopy->copy_number ?? '-',
                'status' => $b->status,
                'borrowed_at' => $b->borrow_date,
                'due_date' => $b->due_date,
                'returned_at' => $b->return_date,
            ]);

        $reserved = Reservation::with('book')
            ->where('user_id', auth()->id())
            ->get()
            ->map(fn($r) => [
                'id' => $r->id,
                'book_title' => $r->book->title,
                'copy_number' => '-', // reservation does not track copy number yet
                'status' => 'reserved',
                'borrowed_at' => $r->reserved_at,
                'due_date' => null,
                'returned_at' => null,
            ]);

        // Merge and sort by date
        $transactions = $borrowed->merge($reserved)->sortByDesc('borrowed_at');

        return view('borrower.dashboard', compact('books', 'transactions', 'keyword'));
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
}
