<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/Users/Index', []);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles_cat = Role::all();

        return Inertia::render('Admin/Users/Create', 
        [
            'rolesCat' => $roles_cat,
        ]);
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

        $user = User::where('id', $id)->with('roles')->first();

        if($user == null){
            return redirect('/admin/usuarios');
        }

        $roles_cat = Role::all();
        
        // dd($user->roles);
        return Inertia::render('Admin/Users/Edit', 
            [
                'user' => $user,
                'rolesCat' => $roles_cat,
            ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
