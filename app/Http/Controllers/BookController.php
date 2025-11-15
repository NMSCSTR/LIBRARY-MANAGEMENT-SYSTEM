<?php
namespace App\Http\Controllers;

use App\Models\Author;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\Category;
use App\Models\Publisher;
use App\Models\Supplier;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.books', [
            'books'      => Book::with(['author', 'category', 'publisher', 'supplier', 'copies'])->get(),
            'authors'    => Author::all(),
            'categories' => Category::all(),
            'publishers' => Publisher::all(),
            'suppliers'  => Supplier::all(),
        ]);
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
        $validated = $request->validate([
            'title'            => 'required',
            'isbn'             => 'nullable|unique:books',
            'author_id'        => 'required|exists:authors,id',
            'category_id'      => 'required|exists:categories,id',
            'publisher_id'     => 'required|exists:publishers,id',
            'supplier_id'      => 'required|exists:suppliers,id',
            'copies_available' => 'required|integer|min:1',
        ]);

        $book = Book::create($validated);

        for ($i = 1; $i <= $validated['copies_available']; $i++) {
            BookCopy::create([
                'book_id'        => $book->id,
                'copy_number'    => $i,
                'status'         => 'available',
                'shelf_location' => 'N/A',
            ]);
        }

        return redirect()->route('books.index')
            ->with('success', 'Book added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Books $books)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $book = Book::findOrFail($id);

        $authors    = Author::all();
        $categories = Category::all();
        $publishers = Publisher::all();
        $suppliers  = Supplier::all();

        return view('admin.books-edit', compact('book', 'authors', 'categories', 'publishers', 'suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'            => 'required|string|max:255',
            'isbn'             => 'required|string|max:255',
            'author_id'        => 'required|exists:authors,id',
            'category_id'      => 'required|exists:categories,id',
            'publisher_id'     => 'required|exists:publishers,id',
            'supplier_id'      => 'required|exists:suppliers,id',
            'copies_available' => 'required|integer|min:0',
        ]);

        $book = Book::findOrFail($id);

        $book->update($request->all());
        return redirect()->route('books.index')->with('success', 'Book updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return redirect()->route('books.index')->with('success', 'Book deleted successfully.');
    }
}
