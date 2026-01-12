<?php
namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

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
    public function show(Reservations $reservations)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservations $reservations)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reservations $reservations)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservations $reservations)
    {
        //
    }
}
