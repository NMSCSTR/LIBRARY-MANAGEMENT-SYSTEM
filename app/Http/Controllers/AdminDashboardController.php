<?php
namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrow;
use App\Models\Reservation;
use App\Models\Supplier;
use App\Models\User;

class AdminDashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalUsers'        => User::count(),
            'totalBooks'        => Book::count(),
            'totalReservations' => Reservation::count(),
            'totalBorrows'      => Borrow::count(),
            'totalSuppliers'    => Supplier::count(),
        ]);
    }
}
