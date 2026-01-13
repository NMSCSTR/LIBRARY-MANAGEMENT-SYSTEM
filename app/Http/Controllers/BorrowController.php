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

        $user          = User::findOrFail($request->user_id);
        $borrowedBooks = [];
        $errors        = [];

        \DB::beginTransaction(); // Start transaction

        try {
            foreach ($request->books as $bookId => $data) {
                if (! isset($data['copy_id'])) {
                    continue;
                }

                $copy = BookCopy::find($data['copy_id']);
                if (! $copy) {
                    $errors[] = "Book copy ID {$data['copy_id']} not found.";
                    continue;
                }

                // Skip if the copy is borrowed, lost, or damaged
                if (in_array($copy->status, ['borrowed', 'lost', 'damaged'])) {
                    $errors[] = "Book copy #{$copy->copy_number} cannot be borrowed (status: {$copy->status}).";
                    continue;
                }

                // Check if this copy is reserved by this user
                $reservation = Reservation::where('copy_id', $copy->id)
                    ->where('user_id', $user->id)
                    ->where('status', 'reserved')
                    ->first();

                // Skip if reserved by another user
                if ($copy->status === 'reserved' && ! $reservation) {
                    $errors[] = "Book copy #{$copy->copy_number} is reserved by another user.";
                    continue;
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

                // Update copy status to borrowed
                $copy->update(['status' => 'borrowed']);

                // Update reservation status if exists
                if ($reservation) {
                    $reservation->update(['status' => 'borrowed']);
                }

                // Log borrow action
                ActivityLog::create([
                    'user_id'     => Auth::id(),
                    'action'      => 'borrow',
                    'description' => Auth::user()->name . " borrowed '{$borrow->book->title}' (Copy #{$borrow->bookCopy->copy_number})",
                ]);

                $borrowedBooks[] = $borrow;
            }

            \DB::commit(); // Commit transaction

        } catch (\Exception $e) {
            \DB::rollBack(); // Rollback if anything fails
            return redirect()->route('borrows.index')
                ->with('error', 'Failed to borrow books: ' . $e->getMessage());
        }

        // Build success/warning message
        $message = count($borrowedBooks) > 0
            ? 'Borrow records created successfully.'
            : '';

        if (! empty($errors)) {
            $message .= ' Some copies could not be borrowed: ' . implode(' | ', $errors);
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
