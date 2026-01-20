<?php
namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['user', 'book', 'copy'])->orderByDesc('created_at')->get();
        return view('admin.reservations', compact('reservations'));
    }

    public function reject(Reservation $reservation)
    {
        $reservation->update(['status' => 'declined']);

        if ($reservation->copy) {
            $reservation->copy->update(['status' => 'available']);
        }

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'reject_reservation',
            'description' => Auth::user()->name . " rejected reservation for '{$reservation->book->title}' (Copy #{$reservation->copy->copy_number})",
        ]);

        return redirect()->back()->with('success', 'Reservation declined and inventory released.');
    }

    public function approve(Reservation $reservation)
    {
        $reservation->update([
            'status' => 'reserved',
            'reserved_at' => now('Asia/Manila')
        ]);

        if ($reservation->copy) {
            $reservation->copy->update(['status' => 'reserved']);
        }

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'approve_reservation',
            'description' => Auth::user()->name . " approved reservation for '{$reservation->book->title}' (Copy #{$reservation->copy->copy_number})",
        ]);

        return redirect()->back()->with('success', 'Reservation approved successfully.');
    }
}
