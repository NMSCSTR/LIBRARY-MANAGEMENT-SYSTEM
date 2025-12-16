<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Borrow;
use App\Models\Reservation;


class BorrowerDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
public function index(Request $request)
    {
        $keyword = $request->search ?? null;

        $books = Book::with(['author', 'publisher', 'category', 'copies'])
            ->when($keyword, fn($q) => $q->where('title', 'like', "%$keyword%"))
            ->get();

        $transactions = Borrow::with(['book', 'bookCopy'])
            ->where('user_id', auth()->id())
            ->orderBy('borrow_date', 'desc')
            ->get();

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

        // Optionally, mark copy as reserved
        $bookCopy->update(['status' => 'reserved']);

        return back()->with('success', 'Book reserved successfully!');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
