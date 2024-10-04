<?php

namespace App\Http\Controllers\App\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Log;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function collection(Request $request)
    {
        try {
            $query = Permission::query()->whereNull('deleted_at');

            if($request->name){
                $query = $query->whereRaw('LOWER(name) LIKE (?) ',["%{$request->name}%"]);
            }

            
            $list = $query->orderBy('id', 'desc')->paginate($request->pageSize);

            return response()->success($list, 'Se consulto correctamnete la lista de permisos.');


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
            return response()->error('Error conusltar la lista de permisos', $response, $code);
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
                'name' => 'required|string|max:255|unique:permisos,name',
                'guard_name' => 'required',
            ], [
                'name.required' => 'El nombre es obligatorio.',
                'guard_name.required' => 'El nombre del permiso es obligatorio.',
                'guard_name.unique' => 'El nombre del permiso ya se ha  registrado.',
            ]);


            $permission = new Permission();

            $permission->name         = $request->name;
            $permission->guard_name   = $request->guard_name;
            $permission->save();
            DB::commit();
            

            return response()->success($permission, 'Se dio de alta el permiso.');
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($response);
            return response()->error('Error al crear permiso.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
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

            $permission = Permission::where('id', $id)->whereNull('deleted_at')->first();

            if($permission->name != $request->name && Permission::where('name', $request->name)->exists()) {
                throw new \Exception('Ya existe un permiso con ese nombre.');
            }

            $permission->name         = $request->name;
            $permission->guard_name   = $request->guard_name;
            $permission->save();
            DB::commit();
            


            return response()->success($permission, 'Se actualizó el permiso.');
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($response);
            return response()->error('Error al crear permiso.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {

            $permission = Permission::where('id',$id)->whereNull('deleted_at')->first();

            if($permission == null){
                throw new \Exception('No se encuentra el permiso.');
            }
            $permission->deleted_at = Carbon::now();
            $permission->save();
            DB::commit();

            return response()->success($permission, 'Se eliminó el permiso.');
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($response);
            return response()->error('Error al crear permiso.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Assing the specified resource from storage.
     */
    public function assing_permissions(Request $request, string $id)
    {
        //
    }
}
