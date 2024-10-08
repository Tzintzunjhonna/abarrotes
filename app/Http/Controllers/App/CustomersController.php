<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Log;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function collection(Request $request)
    {
        try {
            $query = Customers::query();

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
                'name' => 'required',
                'business_name' => 'required',
                'address' => 'required',
                'phone' => 'required',
                'email' => 'required',
                'name_contact' => 'required',
            ], [
                'name.required' => 'El nombre es obligatorio.',
                'business_name.required' => 'La razón social es obligatoria.',
                'address.required' => 'La dirección es obligatoria.',
                'phone.required' => 'El número de teléfono es obligatorio.',
                'email.required' => 'El email es obligatorio.',
                'name_contact.required' => 'El nombre de contacto es obligatorio.',
            ]);

            $providers = new Customers();

            $providers->name              = $request->name;
            $providers->business_name     = $request->business_name;
            $providers->address           = $request->address;
            $providers->phone             = $request->phone;
            $providers->email             = $request->email;
            $providers->name_contact      = $request->name_contact;

            $providers->save();

            if ($request->file('photo_input') != null) {

                if (File::exists(public_path() . $providers->path_logo)) {
                    File::delete(public_path() . $providers->path_logo);
                }
                // OBTENER ARCHIVO
                $file = $request->file('photo_input');
                $name = 'provider_' . time() . '.' . $file->getClientOriginalExtension();
                $path = public_path() . '/uploads/providers/';

                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                //CARGAR ARCHIVO
                $file->move($path, $name);
                $providers->path_logo = '/uploads/providers/' . $name;
            }

            DB::commit();
            

            return response()->success($providers, 'Se dio de alta el cliente.');
        } catch (ValidationException $e) {
            return response()->json([
                'errors' => 'Validation failed.',
                'message' => $e->validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($response);
            return response()->error('Error al crear cliente.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
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
                'name' => 'required',
                'business_name' => 'required',
                'address' => 'required',
                'phone' => 'required',
                'email' => 'required',
                'name_contact' => 'required',
            ], [
                'name.required' => 'El nombre es obligatorio.',
                'business_name.required' => 'La razón social es obligatoria.',
                'address.required' => 'La dirección es obligatoria.',
                'phone.required' => 'El número de teléfono es obligatorio.',
                'email.required' => 'El email es obligatorio.',
                'name_contact.required' => 'El nombre de contacto es obligatorio.',
            ]);

            $providers = Customers::find($id);

            if($providers == null){
                throw new \Exception('No se encuentra el cliente.');
            }

            $providers->name              = $request->name;
            $providers->business_name     = $request->business_name;
            $providers->address           = $request->address;
            $providers->phone             = $request->phone;
            $providers->email             = $request->email;
            $providers->name_contact      = $request->name_contact;

            

            if ($request->file('photo_input') != null) {

                if (File::exists(public_path() . $providers->path_logo)) {
                    File::delete(public_path() . $providers->path_logo);
                }
                // OBTENER ARCHIVO
                $file = $request->file('photo_input');
                $name = 'provider_' . time() . '.' . $file->getClientOriginalExtension();
                $path = public_path() . '/uploads/providers/';

                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                //CARGAR ARCHIVO
                $file->move($path, $name);
                $providers->path_logo = '/uploads/providers/' . $name;
                
            }
            $providers->save();

            DB::commit();
            
            return response()->success($providers, 'Se actualizó el cliente.');
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($response);
            return response()->error('Error al actualizar cliente.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {

            $provider = Customers::find($id);

            if($provider == null){
                throw new \Exception('No se encuentra el cliente.');
            }

            $provider->delete();
            DB::commit();

            return response()->success($provider, 'Se eliminó el cliente.');
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($response);
            return response()->error('Error al eliminar cliente.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Change status the specified resource from storage.
     */
    public function change_status(string $id)
    {
        DB::beginTransaction();
        try {

            $provider = Customers::find($id);

            if($provider == null){
                throw new \Exception('No se encuentra el cliente.');
            }

            $provider->is_active = !$provider->is_active;
            $provider->save();

            DB::commit();

            return response()->success($provider, 'Se cambio de estatus el cliente.');
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($response);
            return response()->error('Error al cambiar de estatus cliente.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
