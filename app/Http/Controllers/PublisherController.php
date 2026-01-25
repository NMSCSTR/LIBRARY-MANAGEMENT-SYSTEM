<?php
namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Publisher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $publishers = Publisher::withCount('books')->get();
        return view('admin.publishers', compact('publishers'));
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
            'name'           => 'required|string|max:255',
            'address'        => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email'          => 'nullable|email|max:255',
            'phone'          => 'nullable|string|max:20',
        ]);

        $publisher = Publisher::create($validated);

        // Audit Log
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'create',
            'description' => "Added publisher '{$publisher->name}' ",
        ]);

        // Detect AJAX Request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'id' => $publisher->id,
                'name' => $publisher->name
            ]);
        }

        return redirect()->back()->with('success', 'Publisher added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Publisher $publisher)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Publisher $publisher)
    {
        return view('admin.publisher-edit', compact('publisher'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publisher $publisher)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
            'address'        => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email'          => 'nullable|email|max:255',
            'phone'          => 'nullable|string|max:20',
        ]);

        $oldName = $publisher->name;
        $publisher->update($validated);

        // Log update
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'update',
            'description' => "Updated publisher '{$oldName}' (ID: {$publisher->id})",
        ]);

        return redirect()->route('publishers.index')->with('success', 'Publisher updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publisher $publisher)
    {
        $name = $publisher->name;
        $publisher->delete();

        // Log deletion
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'delete',
            'description' => "Deleted publisher '{$name}' (ID: {$publisher->id})",
        ]);

        return redirect()->back()->with('success', 'Publisher deleted successfully.');
    }
}
