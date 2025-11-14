<?php

namespace App\Http\Controllers;

use App\Models\Borrow;
use Illuminate\Http\Request;

class BorrowController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $borrows = Borrow::with(['user', 'book'])->get();
        return view('admin.borrows', compact('borrows'));
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
    public function show(Borrows $borrows)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Borrows $borrows)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Borrows $borrows)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrows $borrows)
    {
        //
    }
}
