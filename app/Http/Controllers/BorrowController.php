<?php
namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Borrow;
use App\Models\User;
use App\Models\Reservation;
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

        // Load books with available or reserved copies and their reservations
        $books = Book::with(['copies' => function ($q) {
            $q->whereIn('status', ['available', 'reserved'])
                ->with('reservations');
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

    $user = User::findOrFail($request->user_id);
    $borrowedBooks = [];
    $errors = [];

    foreach ($request->books as $bookId => $data) {
        if (!isset($data['copy_id'])) continue;

        $copy = BookCopy::with('reservations')->find($data['copy_id']);
        if (!$copy) continue;

        // Skip if status prevents borrowing
        if (in_array($copy->status, ['borrowed', 'lost', 'damaged'])) {
            $errors[] = "Book copy #{$copy->copy_number} cannot be borrowed (status: {$copy->status}).";
            continue;
        }

        // Handle reserved books
        if ($copy->status === 'reserved') {
            // Check if reserved by the same user
            $reservation = $copy->reservations->firstWhere('status', 'reserved');
            if (!$reservation || $reservation->user_id != $user->id) {
                $errors[] = "Book copy #{$copy->copy_number} is reserved by another user.";
                continue;
            }

            // Update reservation to borrowed
            // $reservation->update(['status' => 'borrowed']);
        }

        // Create borrow record
        $borrow = Borrow::create([
            'user_id'      => $user->id,
            'book_id'      => $bookId,
            'book_copy_id' => $copy->id,
            'borrow_date'  => now('Asia/Manila'),
            'due_date'     => now('Asia/Manila')->addDays(3),
            'status'       => 'borrowed',
        ]);

        // Update book copy status
        $copy->update(['status' => 'borrowed']);

        $borrowedBooks[] = $borrow;
    }

    // Log all successful borrows
    foreach ($borrowedBooks as $borrow) {
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'borrow',
            'description' => Auth::user()->name . " borrowed '{$borrow->book->title}' (Copy #{$borrow->bookCopy->copy_number})",
        ]);
    }

    // Return feedback
    $message = 'Borrow records created successfully.';
    if (!empty($errors)) {
        $message .= ' Some copies could not be borrowed: ' . implode(', ', $errors);
        return redirect()->route('borrows.index')->with('warning', $message);
    }

    return redirect()->route('borrows.index')->with('success', $message);
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
