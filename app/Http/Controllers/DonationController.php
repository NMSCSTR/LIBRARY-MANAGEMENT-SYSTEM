<?php
namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\User;
use App\Models\Author;
use App\Models\Publisher;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authors = Author::all();
        $publishers = Publisher::all();
        $donations = Donation::with('donor')->get();
        return view('admin.donations', compact('donations', 'publishers','authors'));

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
        $request->validate([
            'donor_id'       => 'required|exists:users,id',
            'book_title'     => 'required|string|max:255',
            'author_id'      => 'required|exists:authors,id',
            'publisher_id'    => 'nullable|exists:publishers,id',
            'year_published' => 'nullable|integer',
            'quantity'       => 'required|integer|min:1',
            'status'         => 'required|string',
        ]);

        Donation::create($request->all());

        return redirect()->route('donations.index')->with('success', 'Donation added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Donation $donation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Donation $donation)
    {
        $users = User::all();
        return view('admin.donation-edit', compact('donation', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Donation $donation)
    {
        $validatedData = $request->validate([
            'donor_id'       => 'required|exists:users,id',
            'book_title'     => 'required|string|max:255',
            'author'         => 'required|string|max:255',
            'publisher'      => 'nullable|string|max:255',
            'year_published' => 'required|integer|min:1900|max:' . date('Y'),
            'quantity'       => 'required|integer|min:1',
            'status'         => 'required|in:pending,accepted,rejected',
        ]);

        $donation->update($validatedData);

        return redirect()->route('donations.index')->with('success', 'Donation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Donation $donation)
    {
        $donation->delete();
        return redirect()->route('donations.index')->with('success', 'Donation deleted.');
    }
}
