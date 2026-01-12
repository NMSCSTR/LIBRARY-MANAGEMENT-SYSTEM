<?php
namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Reservation::with(['user', 'book'])->get();
        return view('admin.reservations', compact('reservations'));
    }

    public function reject(Reservation $reservation)
    {
        $reservation->status = 'declined';
        $reservation->save();

        if ($reservation->copy) {
            $reservation->copy->status = 'available';
            $reservation->copy->save();
        }

        // Log rejection
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'reject_reservation',
            'description' => Auth::user()->name . " rejected reservation for '{$reservation->book->title}' (Reservation ID: {$reservation->id})",
        ]);

        return redirect()->route('reservations.index')
            ->with('success', 'Reservation declined and book copy set to available.');
    }

    public function approve(Reservation $reservation)
    {
        $reservation->status      = 'reserved';
        $reservation->reserved_at = now();
        $reservation->save();

        if ($reservation->copy) {
            $reservation->copy->status = 'reserved';
            $reservation->copy->save();
        }

        // Log approval
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'approve_reservation',
            'description' => Auth::user()->name . " approved reservation for '{$reservation->book->title}' (Reservation ID: {$reservation->id})",
        ]);

        return redirect()->route('reservations.index')
            ->with('success', 'Reservation approved successfully.');
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
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        //
    }
}
