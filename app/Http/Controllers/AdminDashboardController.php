<?php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Reservation;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        $keyword = trim($request->search);

        $books = Book::with([
            'author',
            'category',
            'publisher',
            'supplier',
            'copies',
        ])
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->where('title', 'like', "%{$keyword}%")
                        ->orWhere('isbn', 'like', "%{$keyword}%")
                        ->orWhereHas('author', function ($q2) use ($keyword) {
                            $q2->where('name', 'like', "%{$keyword}%");
                        })
                        ->orWhereHas('category', function ($q2) use ($keyword) {
                            $q2->where('name', 'like', "%{$keyword}%");
                        })
                        ->orWhereHas('publisher', function ($q2) use ($keyword) {
                            $q2->where('name', 'like', "%{$keyword}%");
                        });
                });
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
