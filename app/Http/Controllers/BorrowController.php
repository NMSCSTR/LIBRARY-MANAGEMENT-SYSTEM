<?php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Borrow;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index()
    {
        // Fetch borrows grouped by user and book with quantity
        $borrows = Borrow::with(['user', 'book'])
            ->select('user_id', 'book_id', DB::raw('COUNT(*) as quantity'), DB::raw('MAX(borrow_date) as borrow_date'), DB::raw('MAX(due_date) as due_date'))
            ->groupBy('user_id', 'book_id')
            ->orderByDesc('borrow_date')
            ->get();

        // Pass to the view
        return view('admin.borrows', compact('borrows'));
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
            'books'   => 'required|array',
        ]);

        $borrowDate = Carbon::now();
        $dueDate    = $borrowDate->copy()->addDays(3);

        foreach ($request->books as $bookId => $data) {
            if (! isset($data['selected'])) {
                continue;
            }

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

    public function return ($id)
    {
        $borrow = Borrow::findOrFail($id);

        if ($borrow->return_date !== null) {
            return redirect()->back()->with('error', 'This book is already returned.');
        }

        $borrow->update([
            'return_date' => now(),
            'status'      => 'returned',
        ]);

        // Restore book count
        $borrow->book->increment('copies_available');

        return redirect()->route('borrows.index')->with('success', 'Book returned successfully.');
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
