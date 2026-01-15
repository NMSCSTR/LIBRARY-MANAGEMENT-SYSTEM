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
            'books'   => 'required|array|min:1',
        ]);

        $user = User::findOrFail($request->user_id);

        $borrowedBooks = [];
        $errors        = [];

        foreach ($request->books as $bookId => $data) {

            if (! isset($data['copy_ids'])) {
                continue;
            }

            foreach ($data['copy_ids'] as $copyId) {

                $copy = BookCopy::with('reservations')->find($copyId);
                if (! $copy) {
                    continue;
                }

                // Block invalid statuses
                if (in_array($copy->status, ['borrowed', 'lost', 'damaged'])) {
                    $errors[] = "Copy #{$copy->copy_number} cannot be borrowed (status: {$copy->status}).";
                    continue;
                }

                // Handle reserved copy
                if ($copy->status === 'reserved') {
                    $reservation = $copy->reservations->firstWhere('user_id', $user->id);
                    if (! $reservation) {
                        $errors[] = "Copy #{$copy->copy_number} is reserved by another user.";
                        continue;
                    }
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

                // Update copy status
                $copy->update(['status' => 'borrowed']);

                $borrowedBooks[] = $borrow;
            }
        }

        // Log borrows
        foreach ($borrowedBooks as $borrow) {
            ActivityLog::create([
                'user_id'     => Auth::id(),
                'action'      => 'borrow',
                'description' => Auth::user()->name .
                " borrowed '{$borrow->book->title}' (Copy #{$borrow->bookCopy->copy_number})",
            ]);
        }

        // Feedback
        $message = 'Borrow records created successfully.';

        if (! empty($errors)) {
            $message .= ' Some copies could not be borrowed: ' . implode(', ', $errors);
            return redirect()->route('borrows.index')->with('warning', $message);
        }

        return redirect()->route('borrows.index')->with('success', $message);
    }

    public function return (Request $request, $id)
    {
        $borrow = Borrow::with('bookCopy', 'book')->findOrFail($id);

        if ($borrow->status === 'returned') {
            return redirect()->back()->with('info', 'This book is already returned.');
        }

        $returnDate = $request->input('return_date', now('Asia/Manila'));

        $borrow->update([
            'return_date' => Carbon::parse($returnDate, 'Asia/Manila'),
            'status'      => 'returned',
        ]);

        if ($borrow->bookCopy) {
            $borrow->bookCopy->update(['status' => 'available']);
        }

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'return',
            'description' => Auth::user()->name .
            " returned '{$borrow->book->title}' (Copy #" .
            ($borrow->bookCopy ? $borrow->bookCopy->copy_number : 'N/A') . ")",
        ]);

        return redirect()->route('borrows.index')->with('success', 'Book returned successfully.');
    }

    public function destroy(Borrow $borrow)
    {
        if ($borrow->bookCopy && $borrow->bookCopy->status !== 'available') {
            $borrow->bookCopy->update(['status' => 'available']);
        }

        $borrow->delete();

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'delete_borrow',
            'description' => Auth::user()->name .
            " deleted borrow record for '{$borrow->book->title}' (Copy #" .
            ($borrow->bookCopy ? $borrow->bookCopy->copy_number : 'N/A') . ")",
        ]);

        return redirect()->route('borrows.index')->with('success', 'Borrow record deleted successfully.');
    }
}
