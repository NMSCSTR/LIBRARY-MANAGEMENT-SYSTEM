<?php
namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Book;
use App\Models\BookCopy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookCopyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookCopies = BookCopy::with('book')->get();
        $books      = Book::all();
        return view('admin.bookcopies', compact('bookCopies', 'books'));
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
            'book_id'        => 'required|exists:books,id',
            'copy_number'    => 'required|unique:book_copies,copy_number',
            'status'         => 'required',
            'shelf_location' => 'required',
        ]);

        $bookCopy = BookCopy::create($request->all());

        // Log creation
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'create',
            'description' => "Added book copy #{$bookCopy->copy_number} for book '{$bookCopy->book->title}'",
        ]);

        return redirect()->route('book-copies.index')->with('success', 'Book copy created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(BookCopy $bookCopy)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $bookCopy = BookCopy::findOrFail($id);
        $books    = Book::all();
        return view('admin.edit-bookcopy', compact('bookCopy', 'books'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, BookCopy $bookCopy)
    {
        $request->validate([
            'book_id'        => 'required|exists:books,id',
            'copy_number'    => 'required|unique:book_copies,copy_number,' . $bookCopy->id,
            'status'         => 'required',
            'shelf_location' => 'required',
        ]);

        $oldCopyNumber = $bookCopy->copy_number;
        $oldBookTitle  = $bookCopy->book->title;

        $bookCopy->update($request->all());

        // Log update
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'update',
            'description' => "Updated book copy #{$oldCopyNumber} for book '{$oldBookTitle}'",
        ]);

        return redirect()->route('book-copies.index')->with('success', 'Book copy updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bookCopy   = BookCopy::findOrFail($id);
        $copyNumber = $bookCopy->copy_number;
        $bookTitle  = $bookCopy->book->title;
        $bookCopy->delete();

        // Log deletion
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'delete',
            'description' => "Deleted book copy #{$copyNumber} for book '{$bookTitle}'",
        ]);

        return redirect()->route('book-copies.index')->with('success', 'Book copy deleted successfully.');
    }
}
