<?php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $borrows = Borrow::with(['user', 'book'])->get();
        $users   = User::all();
        $books   = Book::all();
        return view('admin.borrows', compact('borrows', 'users', 'books'));
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
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
        ]);

        $book = Book::find($request->book_id);

        if ($book->copies_available < 1) {
            return redirect()->back()->with('error', 'No copies available for this book.');
        }

        $borrowDate = Carbon::now();
        $dueDate    = $borrowDate->copy()->addDays(3);

        Borrow::create([
            'user_id'     => $request->user_id,
            'book_id'     => $request->book_id,
            'borrow_date' => $borrowDate,
            'due_date'    => $dueDate,
            'status'      => 'borrowed',
        ]);

        $book->decrement('copies_available');

        return redirect()->route('borrows.index')->with('success', 'Borrow record created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Borrow $borrow)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Borrow $borrow)
    {
        $users = User::all();
        $books = Book::all();

        return view('admin.borrow-edit', compact('borrow', 'users', 'books'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Borrow $borrow)
    {
        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'book_id'     => 'required|exists:books,id',
            'return_date' => 'nullable|date',
        ]);

        $borrow->update([
            'user_id'     => $request->user_id,
            'book_id'     => $request->book_id,
            'return_date' => $request->return_date ? Carbon::parse($request->return_date) : null,
            'status'      => $request->return_date ? 'returned' : 'borrowed',
        ]);

        if ($request->return_date && $borrow->wasChanged('return_date')) {
            $borrow->book->increment('copies_available');
        }

        return redirect()->route('borrows.index')->with('success', 'Borrow record updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrow $borrow)
    {
        if ($borrow->status === 'borrowed') {
            $borrow->book->increment('copies_available');
        }

        $borrow->delete();

        return redirect()->route('borrows.index')->with('success', 'Borrow record deleted successfully.');
    }

    public function returnBook(Borrow $borrow)
    {
        if ($borrow->status === 'returned') {
            return redirect()->back()->with('info', 'This book is already returned.');
        }

        $borrow->update([
            'return_date' => Carbon::now(),
            'status'      => 'returned',
        ]);

        $borrow->book->increment('copies_available');

        return redirect()->back()->with('success', 'Book returned successfully.');
    }
}
