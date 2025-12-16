<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Borrow;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    public function index()
    {
        $borrows = Borrow::with(['user', 'book', 'bookCopy'])
            ->orderByDesc('borrow_date')
            ->get();

        $books = Book::withCount(['copies as available_copies' => function ($q) {
            $q->where('status', 'available');
        }])->get();

        $users = User::all();

        return view('admin.borrows', compact('borrows', 'books', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'books'   => 'required|array',
        ]);

        foreach ($request->books as $bookId => $data) {
            if (!isset($data['selected'])) continue;

            $quantity = (int) $data['quantity'];

            $availableCopies = BookCopy::where('book_id', $bookId)
                ->where('status', 'available')
                ->take($quantity)
                ->get();

            if ($availableCopies->count() < $quantity) {
                $book = Book::find($bookId);
                return redirect()->back()->with('error', "Not enough copies for '{$book->title}'");
            }

            foreach ($availableCopies as $copy) {
                Borrow::create([
                    'user_id'      => $request->user_id,
                    'book_id'      => $bookId,
                    'book_copy_id' => $copy->id,
                    'borrow_date'  => now(),
                    'due_date'     => now()->addDays(3),
                    'status'       => 'borrowed',
                ]);

                $copy->update(['status' => 'borrowed']);
            }
        }

        return redirect()->route('borrows.index')->with('success', 'Borrow records created successfully.');
    }

    public function return($id)
    {
        $borrow = Borrow::findOrFail($id);

        if ($borrow->status === 'returned') {
            return redirect()->back()->with('info', 'This book is already returned.');
        }

        $borrow->update([
            'return_date' => now(),
            'status'      => 'returned',
        ]);

        if ($borrow->bookCopy) {
            $borrow->bookCopy->update(['status' => 'available']);
        }

        return redirect()->route('borrows.index')->with('success', 'Book returned successfully.');
    }

    public function edit(Borrow $borrow)
    {
        $users = User::all();
        $books = Book::all();

        return view('admin.borrow-edit', compact('borrow', 'users', 'books'));
    }

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

        if ($request->return_date && $borrow->wasChanged('return_date') && $borrow->bookCopy) {
            $borrow->bookCopy->update(['status' => 'available']);
        }

        return redirect()->route('borrows.index')->with('success', 'Borrow record updated successfully.');
    }

    public function destroy(Borrow $borrow)
    {
        if ($borrow->status === 'borrowed' && $borrow->bookCopy) {
            $borrow->bookCopy->update(['status' => 'available']);
        }

        $borrow->delete();

        return redirect()->route('borrows.index')->with('success', 'Borrow record deleted successfully.');
    }
}
