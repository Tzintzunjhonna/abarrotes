<?php

namespace App\Http\Controllers\App\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Log;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function collection(Request $request)
    {
        try {
            $query = User::with('roles');

            if($request->name){
                $query = $query->whereRaw('LOWER(name) LIKE (?) ',["%{$request->name}%"]);
            }
            if($request->email){
                $query = $query->whereRaw('LOWER(email) LIKE (?) ',["%{$request->email}%"]);
            }

            
            $list = $query->orderBy('id', 'desc')->paginate($request->pageSize);

            return response()->success($list, 'Se consulto correctamnete la lista de usuarios.');


        } catch (\Exception $exception) {
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;

            if ($exception->getCode()) {
                $code = $exception->getCode();
            }
            return response()->error('Error conusltar la lista de usuarios', $response, $code);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required',
                'role' => 'required',
            ], [
                'name.required' => 'El nombre es obligatorio.',
                'email.required' => 'El correo es obligatorio.',
                'password.required' => 'La contraseña es obligatorio.',
                'role.required' => 'El role es obligatorio.',
            ]);


            $user = new User();

            $user->name         = $request->name;
            $user->email        = $request->email;
            $user->password     = Hash::make($request->password);
            $user->save();

            $role = Role::find($request->role);
            $user->assignRole($role->name);
            DB::commit();
            

            return response()->success($user, 'Se dio de alta el usuario.');
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($response);
            return response()->error('Error al crear usuario.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    { 
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email',
                'role' => 'required',
            ], [
                'name.required' => 'El nombre es obligatorio.',
                'email.required' => 'El correo es obligatorio.',
                'role.required' => 'El role es obligatorio.',
            ]);
            $user = User::find($id);
            $role = $user->role;
            
            if($request->email != $user->email){
                $find_users = Users::where('email', $request->email)->first();
                if ($find_users != null) {
                    throw new \Exception('Ya existe usuario con ese correo.');
                }
            }
            
            $user->name         = $request->name;
            $user->email        = $request->email;
            if($request->password){
                $user->password = Hash::make($request->password);
            }
            $user->save();
            if ($request->role != $role) {
                $user->syncRoles([]);
                $role = Role::find($request->role);
                $user->assignRole($role->name);
            }
            DB::commit();
            
            return response()->success($user, 'Se actualizó el usuario.');
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($response);
            return response()->error('Error al crear usuario.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {

            $user = User::find($id);

            if($user == null){
                throw new \Exception('No se encuentra el usuario.');
            }

            $user->delete();
            DB::commit();

            return response()->success($user, 'Se eliminó el usuario.');
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($response);
            return response()->error('Error al crear usuario.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
