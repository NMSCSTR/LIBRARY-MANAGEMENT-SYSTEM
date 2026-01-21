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
        $borrows = Borrow::with(['user', 'book', 'bookCopy'])->orderByDesc('borrow_date')->get();
        $books   = Book::with(['copies' => function ($q) {
            $q->whereIn('status', ['available', 'reserved'])->with('reservations');
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

        $user   = User::findOrFail($request->user_id);
        $errors = [];

        foreach ($request->books as $bookId => $data) {
            if (! isset($data['copy_ids'])) {
                continue;
            }

            foreach ($data['copy_ids'] as $copyId) {
                $copy = BookCopy::find($copyId);
                if (! $copy || in_array($copy->status, ['borrowed', 'lost', 'damaged'])) {
                    continue;
                }

                $borrow = Borrow::create([
                    'user_id'      => $user->id,
                    'book_id'      => $bookId,
                    'book_copy_id' => $copy->id,
                    'borrow_date'  => now('Asia/Manila'),
                    'due_date'     => now('Asia/Manila')->addDays(3),
                    'status'       => 'borrowed',
                ]);

                $copy->update(['status' => 'borrowed']);

                ActivityLog::create([
                    'user_id'     => Auth::id(),
                    'action'      => 'borrow',
                    'description' => Auth::user()->name . " issued '{$borrow->book->title}' (Copy #{$copy->copy_number})",
                ]);
            }
        }

        return redirect()->route('borrows.index')->with('success', 'Borrowing processed with 3-day due date.');
    }

    public function return (Request $request, $id)
    {

        $borrow = Borrow::with(['bookCopy', 'book', 'user'])->findOrFail($id);

        if ($borrow->status === 'returned') {
            return redirect()->back()->with('info', 'This book is already returned.');
        }

        $returnDateTime = $request->input('return_date', now('Asia/Manila'));

        $borrow->update([
            'return_date' => Carbon::parse($returnDateTime, 'Asia/Manila'),
            'status'      => 'returned',
        ]);

        if ($borrow->bookCopy) {
            $borrow->bookCopy->update(['status' => 'available']);
        }

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'return',
            'description' => Auth::user()->name .
            " processed return for '{$borrow->book->title}' (Copy #{$borrow->bookCopy->copy_number}) " .
            "from borrower: {$borrow->user->name}",
        ]);

        return redirect()->route('borrows.index')->with('success', 'Book returned and activity logged.');
    }
}
