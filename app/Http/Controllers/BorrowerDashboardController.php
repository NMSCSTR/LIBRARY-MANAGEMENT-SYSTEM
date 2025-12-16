<?php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;

use Illuminate\Http\Request;


class BorrowerDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $keyword = $request->search;

        $books = Book::with(['author', 'publisher', 'category', 'copies'])
            ->when($keyword, fn($q) => $q->where('title', 'like', "%$keyword%"))
            ->get();

        $transactions = Borrow::with(['book', 'bookCopy'])
            ->where('user_id', auth()->id())
            ->orderBy('borrow_date', 'desc')
            ->get();

        return view('borrower.dashboard', compact('books', 'transactions', 'keyword'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
