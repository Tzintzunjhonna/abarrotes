<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/Roles/Index', []);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Roles/Create', []);
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
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $token)
    {
        $id = base64_decode($token);
        // $ids = explode(',', $ids);

        $role = Role::find($id);

        if($role == null){
            return redirect('/admin/roles');
        }
        
        // dd($user->roles);
        return Inertia::render('Admin/Roles/Edit', 
            [
                'role' => $role
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function assing(Request $request, $token)
    {
        $id = base64_decode($token);

        $role = Role::find($id);

        if ($role == null) {
            return redirect('/admin/roles');
        }

        $lista_permission = Permission::whereNull('deleted_at')->where('guard_name', $role->guard_name)->get();

        foreach ($lista_permission as $i => $item) {
            $roles = $item->roles->where('id', $id)->first();
            $lista_permission[$i]->rol = (isset($roles)) ? true : false;
        }
        
        return Inertia::render(
            'Admin/Roles/Assign',
            [
                'role' => $role,
                'lista_permission' => $lista_permission
            ]
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
