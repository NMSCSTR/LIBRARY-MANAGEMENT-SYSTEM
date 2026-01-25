<?php
namespace App\Http\Controllers;

use App\Models\Borrow;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // public function index(Request $request)
    // {
    //     $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
    //     $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

    //     $borrowLogs = Borrow::with(['user', 'book', 'bookCopy'])
    //         ->whereBetween('borrow_date', [$startDate, $endDate])
    //         ->orderByDesc('borrow_date')
    //         ->get();

    //     $reservationLogs = Reservation::with(['user', 'book', 'copy'])
    //         ->whereBetween('created_at', [$startDate, $endDate])
    //         ->orderByDesc('created_at')
    //         ->get();

    //     return view('admin.reports', compact('borrowLogs', 'reservationLogs', 'startDate', 'endDate'));
    // }

    public function index(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate   = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        // 1. Existing Logs
        $borrowLogs = Borrow::with(['user', 'book', 'bookCopy'])
            ->whereBetween('borrow_date', [$startDate, $endDate])->orderByDesc('borrow_date')->get();

        // 2. Top Borrows (Aggregated)
        $topBooks = Book::withCount(['borrows' => function ($q) use ($startDate, $endDate) {
            $q->whereBetween('borrow_date', [$startDate, $endDate]);
        }])
            ->orderBy('borrows_count', 'desc')
            ->take(5)->get();

        // 3. Damaged Books
        $damagedBooks = BookCopy::with('book')->where('status', 'damaged')->get();

        // 4. Borrowers with Penalties (Overdue)
        // Based on your Borrow model accessor, we check for 'borrowed' status past due date
        $penaltyBorrows = Borrow::with(['user', 'book'])
            ->where('status', 'borrowed')
            ->where('due_date', '<', now('Asia/Manila'))
            ->get();

        return view('admin.reports', compact(
            'borrowLogs',
            'topBooks',
            'damagedBooks',
            'penaltyBorrows',
            'startDate',
            'endDate'
        ));
    }
}
