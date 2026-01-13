<?php
namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Borrow;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BorrowController extends Controller
{
public function index()
{
    $borrows = Borrow::with(['user', 'book', 'bookCopy'])
        ->orderByDesc('borrow_date')
        ->get();

    // Load books with copies and their reservations
    $books = Book::with(['copies.reservations'])->get();

    $users = User::all();

    return view('admin.borrows', compact('borrows', 'books', 'users'));
}


    public function store(Request $request)
    {
        $request->validate([
            'user_id'          => 'required|exists:users,id',
            'books'            => 'required|array',
            'books.*.copy_ids' => 'required|array|min:1',
        ], [
            'books.*.copy_ids.required' => 'Please select at least one book copy to borrow.',
        ]);

        $borrowedBooks = [];

        foreach ($request->books as $bookId => $data) {
            if (! isset($data['copy_ids'])) {
                continue;
            }

            foreach ($data['copy_ids'] as $copyId) {
                $copy = BookCopy::with('reservations')->findOrFail($copyId);

                // Reserved copy check
                if ($copy->status === 'reserved') {
                    $reservation = $copy->reservations->where('status', 'reserved')->first();
                    if (! $reservation || $reservation->user_id != $request->user_id) {
                        return redirect()->back()->with('error', "Book copy #{$copy->copy_number} is reserved by another user.");
                    }
                    $reservation->update(['status' => 'borrowed']);
                }

                // Cannot borrow if copy is already borrowed, lost, or damaged
                if (in_array($copy->status, ['borrowed', 'lost', 'damaged'])) {
                    return redirect()->back()->with('error', "Book copy #{$copy->copy_number} cannot be borrowed.");
                }

                // Create borrow record
                $borrow = Borrow::create([
                    'user_id'      => $request->user_id,
                    'book_id'      => $bookId,
                    'book_copy_id' => $copy->id,
                    'borrow_date'  => now('Asia/Manila'),
                    'due_date'     => now('Asia/Manila')->addDays(3),
                    'status'       => 'borrowed',
                ]);

                $copy->update(['status' => 'borrowed']);
                $borrowedBooks[] = $borrow;
            }
        }

        // Log activity
        foreach ($borrowedBooks as $borrow) {
            ActivityLog::create([
                'user_id'     => auth()->id(),
                'action'      => 'borrow',
                'description' => auth()->user()->name . " borrowed '{$borrow->book->title}' (Copy ID: {$borrow->book_copy_id})",
            ]);
        }

        return redirect()->route('borrows.index')->with('success', 'Borrow record(s) created successfully.');
    }

    public function return ($id)
    {
        $borrow = Borrow::findOrFail($id);

        if ($borrow->status === 'returned') {
            return redirect()->back()->with('info', 'This book is already returned.');
        }

        $borrow->update([
            'return_date' => Carbon::now('Asia/Manila'),
            'status'      => 'returned',
        ]);

        if ($borrow->bookCopy) {
            $borrow->bookCopy->update(['status' => 'available']);
        }

        // Log return action
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'return',
            'description' => Auth::user()->name . " returned '{$borrow->book->title}' (Copy ID: {$borrow->book_copy_id})",
        ]);

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

        // Log update action
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'update_borrow',
            'description' => Auth::user()->name . " updated borrow record for '{$borrow->book->title}' (Copy ID: {$borrow->book_copy_id})",
        ]);

        return redirect()->route('borrows.index')->with('success', 'Borrow record updated successfully.');
    }

    public function destroy(Borrow $borrow)
    {
        if ($borrow->bookCopy) {
            $borrow->bookCopy->update(['status' => 'available']);
        }

        $borrow->delete();

        // Log deletion
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'delete_borrow',
            'description' => Auth::user()->name . " deleted borrow record for '{$borrow->book->title}' (Copy ID: {$borrow->book_copy_id})",
        ]);

        return redirect()->route('borrows.index')->with('success', 'Borrow record deleted successfully.');
    }
}
