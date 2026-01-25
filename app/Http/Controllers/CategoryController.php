<?php
namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('books')->get();
        return view('admin.categories', compact('categories'));
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
        // Important: Handle both normal form and AJAX JSON body
        $data = $request->isJson() ? $request->json()->all() : $request->all();

        $validated = validator($data, [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ])->validate();

        $category = Category::create($validated);

        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'create',
            'description' => "Added category '{$category->name}'",
        ]);

        // This part bridges the View and the Controller
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'id' => $category->id,
                'name' => $category->name
            ], 201);
        }

        return redirect()->back()->with('success', 'Category added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return view('admin.edit-category', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $oldName = $category->name;

        $category->update($validated);

        // Log update
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'update',
            'description' => "Updated category '{$oldName}' to '{$category->name}'",
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $name = $category->name;
        $category->delete();

        // Log deletion
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'delete',
            'description' => "Deleted category '{$name}'",
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
