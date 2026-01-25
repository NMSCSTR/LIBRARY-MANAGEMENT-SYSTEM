<?php
namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::with('books')->get();
        return view('admin.suppliers', compact('suppliers'));
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
            'name'           => 'required|string|max:255',
            'address'        => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email'          => 'nullable|email',
            'phone'          => 'nullable|string|max:20',
        ]);

        $supplier = Supplier::create($request->all());

        // Audit Log
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'create',
            'description' => "Created supplier '{$supplier->name}'",
        ]);

        // Detect AJAX Request
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'id' => $supplier->id,
                'name' => $supplier->name
            ]);
        }

        return redirect()->back()->with('success', 'Supplier added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        return view('admin.supplier-edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'address'        => 'nullable|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email'          => 'nullable|email',
            'phone'          => 'nullable|string|max:20',
        ]);

        $oldName = $supplier->name;

        $supplier->update($request->all());

        // Log update
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'update',
            'description' => "Updated supplier '{$oldName}' to '{$supplier->name}' (ID: {$supplier->id})",
        ]);

        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        $supplierName = $supplier->name;
        $supplier->delete();

        // Log deletion
        ActivityLog::create([
            'user_id'     => Auth::id(),
            'action'      => 'delete',
            'description' => "Deleted supplier '{$supplierName}' (ID: {$supplier->id})",
        ]);

        return redirect()->back()->with('success', 'Supplier deleted successfully!');
    }
}
