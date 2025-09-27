<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount('users')->paginate(10);
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|in:student,teacher,instructor,librarian,admin,donor|unique:roles',
        ]);

        Role::create($request->all());
        return redirect()->route('roles.index')->with('success', 'Role Created successfully.');
    }

    public function show(Role $role)
    {

    }

    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => 'required|in:student,librarian,admin,donor|unique:roles,name,' . $role->id,
        ]);

        $role->update($request->all());
        return redirect()->route('roles.index')->with('success', 'Role updated successfully.');
    }

     public function destroy(Role $role)
    {
        if ($role->users()->exists()) {
            return redirect()->route('roles.index')->with('error', 'Cannot delete role with associated users.');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Role deleted successfully.');
    }


}
