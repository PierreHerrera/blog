<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'unique:roles,name'],
        ]);

        /* Cualquiera de las dos opciones */
        // $rol = Role::create($request->all()); 
        $rol = Role::create([
            'name' => $request->name
        ]);

        session()->flash('swal',[
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'La rol se creó correctamente.',
        ]);

        return redirect()->route('admin.roles.edit', $rol);

    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        return view('admin.roles.show', compact('role'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => ['required', 'unique:roles,name,'. $role->id],
        ]);

        $role->update($request->all());

        session()->flash('swal',[
            'icon' => 'success',
            'title' => '¡Bien hecho!',
            'text' => 'La rol se actualizó correctamente.',
        ]);

        return redirect()->route('admin.roles.edit', $role);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        //
    }
}
