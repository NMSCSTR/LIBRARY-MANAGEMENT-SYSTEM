<?php
namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Author;
use App\Models\Donation;
use App\Models\Publisher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DonationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authors    = Author::all();
        $publishers = Publisher::all();
        $donations  = Donation::with(['donor', 'author', 'publisher'])->get();
        return view('admin.donations', compact('donations', 'publishers', 'authors'));
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
        $validated = $request->validate([
            'donor_id'       => 'required|exists:users,id',
            'book_title'     => 'required|string|max:255',
            'author_id'      => 'required|exists:authors,id',
            'publisher_id'   => 'nullable|exists:publishers,id',
            'year_published' => 'nullable|integer',
            'quantity'       => 'required|integer|min:1',
            'status'         => 'required|string',
        ]);

        $donation = Donation::create($validated);

        // Log creation
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'create',
            'description' => "Added donation '{$donation->book_title}' by donor ID {$donation->donor_id}",
        ]);

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
        $authors    = Author::all();
        $publishers = Publisher::all();
        $users      = User::all();
        return view('admin.donation-edit', compact('donation', 'authors', 'publishers', 'users'));
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

        $oldTitle = $donation->book_title;
        $donation->update($validatedData);

        // Log update
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'update',
            'description' => "Updated donation '{$oldTitle}' (ID: {$donation->id})",
        ]);

        return redirect()->route('donations.index')->with('success', 'Donation updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Donation $donation)
    {
        $title = $donation->book_title;
        $donation->delete();

        // Log deletion
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'delete',
            'description' => "Deleted donation '{$title}' (ID: {$donation->id})",
        ]);

        return redirect()->route('donations.index')->with('success', 'Donation deleted.');
    }
}
