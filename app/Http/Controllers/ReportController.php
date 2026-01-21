<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->get('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->endOfMonth()->format('Y-m-d'));

        $borrowLogs = Borrow::with(['user', 'book', 'bookCopy'])
            ->whereBetween('borrow_date', [$startDate, $endDate])
            ->orderByDesc('borrow_date')
            ->get();

        $reservationLogs = Reservation::with(['user', 'book', 'copy'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderByDesc('created_at')
            ->get();

        return view('admin.reports', compact('borrowLogs', 'reservationLogs', 'startDate', 'endDate'));
    }
}
