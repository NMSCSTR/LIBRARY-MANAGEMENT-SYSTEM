<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Reservation;
use App\Models\BookCopy;
use Illuminate\Http\Request;

class BorrowerDashboardController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->search;
        $perPage = $request->get('per_page', 10);

        $books = Book::with(['author', 'publisher', 'category', 'copies'])
            ->when($keyword, function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                  ->orWhereHas('author', fn($a) => $a->where('name', 'like', "%{$keyword}%"))
                  ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$keyword}%"))
                  ->orWhereHas('publisher', fn($p) => $p->where('name', 'like', "%{$keyword}%"));
            })
            ->paginate($perPage)
            ->withQueryString();

        $borrowed = Borrow::with(['book', 'bookCopy'])
            ->where('user_id', auth()->id())
            ->get();

        $reserved = Reservation::with(['book', 'copy'])
            ->where('user_id', auth()->id())
            ->get();

        $transactions = $borrowed->concat($reserved)
            ->sortByDesc(fn($t) => $t->borrow_date ?? $t->reserved_at);

        $summary = [
            'borrowed'  => $borrowed->count(),
            'overdue'   => $borrowed->where('status', 'overdue')->count(),
            'available' => $books->sum(fn($b) => $b->copies->where('status', 'available')->count()),
            'reserved'  => $reserved->count(),
        ];

        return view('borrower.dashboard', compact(
            'books',
            'transactions',
            'keyword',
            'summary'
        ));
    }

    public function reserve(Request $request)
    {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'copy_id' => 'required|exists:book_copies,id',
        ]);

        $copy = BookCopy::where('id', $request->copy_id)
            ->where('book_id', $request->book_id)
            ->where('status', 'available')
            ->first();

        if (!$copy) {
            return back()->with('error', 'Copy not available.');
        }

        Reservation::create([
            'user_id'     => auth()->id(),
            'book_id'     => $request->book_id,
            'copy_id'     => $copy->id,
            'status'      => 'reserved',
            'reserved_at' => now(),
        ]);

        $copy->update(['status' => 'reserved']);

        return back()->with('success', 'Book reserved successfully.');
    }

    public function cancelReservation(Reservation $reservation)
    {
        abort_if($reservation->user_id !== auth()->id(), 403);

        BookCopy::where('id', $reservation->copy_id)
            ->update(['status' => 'available']);

        $reservation->delete();

        return back()->with('success', 'Reservation cancelled.');
    }
}
