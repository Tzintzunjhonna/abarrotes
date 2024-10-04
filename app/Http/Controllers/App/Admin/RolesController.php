<?php

namespace App\Http\Controllers\App\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Log;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function collection(Request $request)
    {
        try {
            $query = Role::query()->whereNull('deleted_at');

            if($request->name){
                $query = $query->whereRaw('LOWER(name) LIKE (?) ',["%{$request->name}%"]);
            }

            
            $list = $query->orderBy('id', 'desc')->paginate($request->pageSize);

            return response()->success($list, 'Se consulto correctamnete la lista de roles.');


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
            return response()->error('Error conusltar la lista de roles', $response, $code);
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
                'name' => 'required|string|max:255|unique:roles,name',
                'guard_name' => 'required',
            ], [
                'name.required' => 'El nombre es obligatorio.',
                'guard_name.required' => 'El nombre del permiso es obligatorio.',
                'guard_name.unique' => 'El nombre del permiso ya se ha  registrado.',
            ]);


            $user = new Role();

            $user->name         = $request->name;
            $user->guard_name   = $request->guard_name;
            $user->save();
            DB::commit();
            

            return response()->success($user, 'Se dio de alta el rol.');
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($response);
            return response()->error('Error al crear rol.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
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
                'guard_name' => 'required',
            ], [
                'name.required' => 'El nombre es obligatorio.',
                'guard_name.required' => 'El nombre del permiso es obligatorio.',
            ]);

            $role = Role::where('id', $id)->whereNull('deleted_at')->first();

            if($role->name != $request->name && Role::where('name', $request->name)->exists()) {
                throw new \Exception('Ya existe un rol con ese nombre.');
            }

            $role->name         = $request->name;
            $role->guard_name   = $request->guard_name;
            $role->save();
            DB::commit();
            


            return response()->success($role, 'Se actualizó el rol.');
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($response);
            return response()->error('Error al crear rol.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {

            $role = Role::where('id',$id)->whereNull('deleted_at')->first();

            if($role == null){
                throw new \Exception('No se encuentra el rol.');
            }
            $role->deleted_at = Carbon::now();
            $role->save();
            DB::commit();

            return response()->success($role, 'Se eliminó el rol.');
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($response);
            return response()->error('Error al crear rol.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Assing the specified resource from storage.
     */
    public function assing_permissions(Request $request, string $id)
    {
        DB::beginTransaction();
        try {

            $role = Role::where('id',$id)->whereNull('deleted_at')->first();

            if($role == null){
                throw new \Exception('No se encuentra el rol.');
            }

            if (!empty($request->siPermisos)) {
                $role->syncPermissions($request->siPermisos);
            }

            if (!empty($request->noPermisos)) {
                $role->revokePermissionTo($request->noPermisos);
            }

            DB::commit();

            return response()->success($role, 'Se asignaron los permisos al rol.');
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($response);
            return response()->error('Error al crear rol.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
