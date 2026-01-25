<?php
namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $authors = Author::all();
        return view('admin.authors', compact('authors'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // return view('admin.authors-create');
    }

    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //     ]);

    //     $author = Author::create([
    //         'name' => $request->name,
    //     ]);

    //     // Log creation
    //     ActivityLog::create([
    //         'user_id'     => Auth::id(),
    //         'action'      => 'create',
    //         'description' => 'Created author: ' . $author->name,
    //     ]);

    //     return redirect()->route('authors.index')->with('success', 'Author created successfully.');
    // }

    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string|max:255|unique:authors,name',
        ]);

        $author = Author::create(['name' => $request->name]);

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'create',
            'description' => 'Created author: ' . $author->name,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'id'   => $author->id,
                'name' => $author->name,
            ], 201);
        }

        return redirect()->back()->with('success', 'Author added!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $author = Author::findOrFail($id);
        return view('admin.authors-edit', compact('author'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $author  = Author::findOrFail($id);
        $oldName = $author->name;

        $author->update([
            'name' => $request->name,
        ]);

        // Log update
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'update',
            'description' => "Updated author: '$oldName' to '{$author->name}'",
        ]);

        return redirect()->route('authors.index')->with('success', 'Author updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $author     = Author::findOrFail($id);
        $authorName = $author->name;
        $author->delete();

        // Log deletion
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'delete',
            'description' => 'Deleted author: ' . $authorName,
        ]);

        return redirect()->route('authors.index')->with('success', 'Author deleted successfully.');
    }
}
