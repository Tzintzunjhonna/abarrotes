<?php

namespace App\Http\Controllers\App\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyHasAddress;
use App\Models\Customers;
use App\Models\CustomersHasAddress;
use App\Models\CustomersHasBilling;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Log;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function collection(Request $request)
    {
        //
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        DB::beginTransaction();
        try {

            $request->validate([
                'rfc'  => 'required',
                'business_name'  => 'required',
                'zip_code'  => 'required',
                'pais_id'  => 'required',
                'codigo_postal_id'  => 'required',
                'estado_id'  => 'required',
                'municipio_id'  => 'required',
                'localidad_id'  => 'required',
                'street'  => 'required',
                'number'  => 'required',
            ], [
                'rfc.required' => 'El campo rfc es requerido',
                'business_name.required' => 'El campo nombre de la empresa es requerido',
                'zip_code.required' => 'El campo código postal es requerido',
                'pais_id.required' => 'El campo país es requerido',
                'codigo_postal_id.required' => 'El campo código postal es requerido',
                'estado_id.required' => 'El campo estado es requerido',
                'municipio_id.required' => 'El campo municipio es requerido',
                'localidad_id.required' => 'El campo localidad es requerido',
                'street.required' => 'El campo calle es requerido',
                'number.required' => 'El campo número es requerido',

            ]);

            $company = Company::first();

            if ($company == null) {
                $company = new Company();
            }

            $company->rfc                   = $request->rfc;
            $company->business_name         = $request->business_name;
            $company->zip_code              = $request->zip_code;

            if ($request->file('photo_input') != null) {

                if (File::exists(public_path() . $company->path_logo)) {
                    File::delete(public_path() . $company->path_logo);
                }
                // OBTENER ARCHIVO
                $file = $request->file('photo_input');
                $name = 'company_' . time() . '.' . $file->getClientOriginalExtension();
                $path = public_path() . '/uploads/company/';

                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                //CARGAR ARCHIVO
                $file->move($path, $name);
                $company->path_logo = '/uploads/company/' . $name;
            }
            $company->updated_at = Carbon::now();
            $company->save();

            $company_has_address = CompanyHasAddress::where('company_id', $company->id)->first();

            if ($company_has_address == null) {
                $company_has_address = new CompanyHasAddress();
            }

            $company_has_address->company_id          = $company->id;
            $company_has_address->pais_id              = $request->pais_id;
            $company_has_address->codigo_postal_id     = $request->codigo_postal_id;
            $company_has_address->estado_id            = $request->estado_id;
            $company_has_address->municipio_id         = $request->municipio_id;
            $company_has_address->localidad_id         = $request->localidad_id;
            $company_has_address->street               = $request->street;
            $company_has_address->number               = $request->number;
            $company_has_address->created_at = Carbon::now();
            $company_has_address->updated_at = Carbon::now();
            $company_has_address->save();

            DB::commit();


            return response()->success($company, 'Se actualizó la empresa.');
        } catch (ValidationException $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($response);
            return response()->error($exception->validator->errors(), $response, Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($response);
            return response()->error('Error al crear empresa.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
       //
    }
    /**
     * Change status the specified resource from storage.
     */
    public function change_status(string $id)
    {
       //
    }
}
