<?php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Reservation;
use App\Models\Borrow;
use App\Models\Supplier;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->search;

        $books = Book::with([
            'author',
            'category',
            'publisher',
            'supplier',
            'copies',
        ])
            ->when($keyword, function ($query) use ($keyword) {
                $query->where('title', 'like', "%$keyword%")
                    ->orWhere('isbn', 'like', "%$keyword%")
                    ->orWhereHas('author', fn($q) =>
                        $q->where('name', 'like', "%$keyword%")
                    )
                    ->orWhereHas('category', fn($q) =>
                        $q->where('name', 'like', "%$keyword%")
                    )
                    ->orWhereHas('publisher', fn($q) =>
                        $q->where('name', 'like', "%$keyword%")
                    );
            })
            ->get();

        return view('admin.dashboard', [
            'totalUsers'        => User::count(),
            'totalBooks'        => Book::count(),
            'totalReservations' => Reservation::count(),
            'totalBorrows'      => Borrow::count(),
            'totalSuppliers'    => Supplier::count(),
            'books'             => $books,
            'keyword'           => $keyword,
        ]);
    }
}
