<?php

namespace App\Http\Controllers\App;

use App\Exports\CustomersExport;
use App\Http\Controllers\Controller;
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

            return response()->success($list, 'Se consulto correctamnete la lista de clientes.');


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
            return response()->error('Error conusltar la lista de clientes', $response, $code);
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
                'name'  => 'required',
                'business_name'  => 'required',
                'address'  => 'required',
                'phone'  => 'required',
                'email'  => 'required',
                'name_contact'  => 'required',
                'uso_cdfi_id'  => 'required',
                'regimen_fiscal_id'  => 'required',
                'metodo_pago_id'  => 'required',
                'forma_pago_id'  => 'required',
                'tipo_exportacion_id'  => 'required',
                'zip_code'  => 'required',
                'pais_id'  => 'required',
                'codigo_postal_id'  => 'required',
                'estado_id'  => 'required',
                'municipio_id'  => 'required',
                'localidad_id'  => 'required',
                'street'  => 'required',
                'number'  => 'required',
            ], [
                'name.required' => 'El campo nombre es requerido',
                'business_name.required' => 'El campo nombre de la empresa es requerido',
                'address.required' => 'El campo dirección es requerido',
                'phone.required' => 'El campo teléfono es requerido',
                'email.required' => 'El campo correo electrónico es requerido',
                'name_contact.required' => 'El campo nombre del contacto es requerido',
                'uso_cdfi_id.required' => 'El campo uso de CFDI es requerido',
                'regimen_fiscal_id.required' => 'El campo régimen fiscal es requerido',
                'metodo_pago_id.required' => 'El campo método de pago es requerido',
                'forma_pago_id.required' => 'El campo forma de pago es requerido',
                'tipo_exportacion_id.required' => 'El campo tipo de exportación es requerido',
                'zip_code.required' => 'El campo código postal es requerido',
                'pais_id.required' => 'El campo país es requerido',
                'codigo_postal_id.required' => 'El campo código postal es requerido',
                'estado_id.required' => 'El campo estado es requerido',
                'municipio_id.required' => 'El campo municipio es requerido',
                'localidad_id.required' => 'El campo localidad es requerido',
                'street.required' => 'El campo calle es requerido',
                'number.required' => 'El campo número es requerido',

            ]);

            $customer = new Customers();

            $customer->name                 = $request->name;
            $customer->rfc                  = $request->rfc;
            $customer->business_name        = $request->business_name;
            $customer->address              = $request->address;
            $customer->phone                = $request->phone;
            $customer->email                = $request->email;
            $customer->name_contact         = $request->name_contact;

            if ($request->file('photo_input') != null) {

                if (File::exists(public_path() . $customer->path_logo)) {
                    File::delete(public_path() . $customer->path_logo);
                }
                // OBTENER ARCHIVO
                $file = $request->file('photo_input');
                $name = 'customer_' . time() . '.' . $file->getClientOriginalExtension();
                $path = public_path() . '/uploads/customer/';

                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                //CARGAR ARCHIVO
                $file->move($path, $name);
                $customer->path_logo = '/uploads/customer/' . $name;
            }

            $customer->created_at = Carbon::now();
            $customer->updated_at = Carbon::now();
            $customer->save();

            $customer_has_billing = new CustomersHasBilling();

            $customer_has_billing->customer_id          = $customer->id;
            $customer_has_billing->uso_cdfi_id          = $request->uso_cdfi_id;
            $customer_has_billing->regimen_fiscal_id    = $request->regimen_fiscal_id;
            $customer_has_billing->metodo_pago_id       = $request->metodo_pago_id;
            $customer_has_billing->forma_pago_id        = $request->forma_pago_id;
            $customer_has_billing->tipo_exportacion_id  = $request->tipo_exportacion_id;
            $customer_has_billing->zip_code             = $request->zip_code;
            $customer_has_billing->created_at = Carbon::now();
            $customer_has_billing->updated_at = Carbon::now();
            $customer_has_billing->save();

            $customers_has_address = new CustomersHasAddress();

            $customers_has_address->customer_id          = $customer->id;
            $customers_has_address->pais_id              = $request->pais_id;
            $customers_has_address->codigo_postal_id     = $request->codigo_postal_id;
            $customers_has_address->estado_id            = $request->estado_id;
            $customers_has_address->municipio_id         = $request->municipio_id;
            $customers_has_address->localidad_id         = $request->localidad_id;
            $customers_has_address->street               = $request->street;
            $customers_has_address->number               = $request->number;
            $customers_has_address->created_at = Carbon::now();
            $customers_has_address->updated_at = Carbon::now();
            $customers_has_address->save();

            DB::commit();
            

            return response()->success($customer, 'Se dio de alta el cliente.');
        } catch (ValidationException $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($response);
            return response()->error($exception->validator->errors(), $response, Response::HTTP_UNPROCESSABLE_ENTITY);
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
                'name'  => 'required',
                'business_name'  => 'required',
                'address'  => 'required',
                'phone'  => 'required',
                'email'  => 'required',
                'name_contact'  => 'required',
                'uso_cdfi_id'  => 'required',
                'regimen_fiscal_id'  => 'required',
                'metodo_pago_id'  => 'required',
                'forma_pago_id'  => 'required',
                'tipo_exportacion_id'  => 'required',
                'zip_code'  => 'required',
                'pais_id'  => 'required',
                'codigo_postal_id'  => 'required',
                'estado_id'  => 'required',
                'municipio_id'  => 'required',
                'localidad_id'  => 'required',
                'street'  => 'required',
                'number'  => 'required',
            ], [
                'name.required' => 'El campo nombre es requerido',
                'business_name.required' => 'El campo nombre de la empresa es requerido',
                'address.required' => 'El campo dirección es requerido',
                'phone.required' => 'El campo teléfono es requerido',
                'email.required' => 'El campo correo electrónico es requerido',
                'name_contact.required' => 'El campo nombre del contacto es requerido',
                'uso_cdfi_id.required' => 'El campo uso de CFDI es requerido',
                'regimen_fiscal_id.required' => 'El campo régimen fiscal es requerido',
                'metodo_pago_id.required' => 'El campo método de pago es requerido',
                'forma_pago_id.required' => 'El campo forma de pago es requerido',
                'tipo_exportacion_id.required' => 'El campo tipo de exportación es requerido',
                'zip_code.required' => 'El campo código postal es requerido',
                'pais_id.required' => 'El campo país es requerido',
                'codigo_postal_id.required' => 'El campo código postal es requerido',
                'estado_id.required' => 'El campo estado es requerido',
                'municipio_id.required' => 'El campo municipio es requerido',
                'localidad_id.required' => 'El campo localidad es requerido',
                'street.required' => 'El campo calle es requerido',
                'number.required' => 'El campo número es requerido',

            ]);

            $customer = Customers::find($id);

            if ($customer == null) {
                throw new \Exception('No se encuentra el cliente.');
            }

            $customer->name                 = $request->name;
            $customer->rfc                  = $request->rfc;
            $customer->business_name        = $request->business_name;
            $customer->address              = $request->address;
            $customer->phone                = $request->phone;
            $customer->email                = $request->email;
            $customer->name_contact         = $request->name_contact;

            if ($request->file('photo_input') != null) {

                if (File::exists(public_path() . $customer->path_logo)) {
                    File::delete(public_path() . $customer->path_logo);
                }
                // OBTENER ARCHIVO
                $file = $request->file('photo_input');
                $name = 'customer_' . time() . '.' . $file->getClientOriginalExtension();
                $path = public_path() . '/uploads/customer/';

                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                //CARGAR ARCHIVO
                $file->move($path, $name);
                $customer->path_logo = '/uploads/customer/' . $name;
            }
            $customer->updated_at = Carbon::now();
            $customer->save();


            $customer_has_billing = CustomersHasBilling::where('customer_id', $customer->id)->first();

            if($customer_has_billing == null){
                $customer_has_billing = new CustomersHasBilling();
            }

            $customer_has_billing->uso_cdfi_id          = $request->uso_cdfi_id;
            $customer_has_billing->regimen_fiscal_id    = $request->regimen_fiscal_id;
            $customer_has_billing->metodo_pago_id       = $request->metodo_pago_id;
            $customer_has_billing->forma_pago_id        = $request->forma_pago_id;
            $customer_has_billing->tipo_exportacion_id  = $request->tipo_exportacion_id;
            $customer_has_billing->zip_code             = $request->zip_code;
            $customer_has_billing->created_at = Carbon::now();
            $customer_has_billing->updated_at = Carbon::now();
            $customer_has_billing->save();

            $customers_has_address = CustomersHasAddress::where('customer_id', $customer->id)->first();

            if ($customers_has_address == null) {
                $customers_has_address = new CustomersHasAddress();
            }

            $customers_has_address->customer_id          = $customer->id;
            $customers_has_address->pais_id              = $request->pais_id;
            $customers_has_address->codigo_postal_id     = $request->codigo_postal_id;
            $customers_has_address->estado_id            = $request->estado_id;
            $customers_has_address->municipio_id         = $request->municipio_id;
            $customers_has_address->localidad_id         = $request->localidad_id;
            $customers_has_address->street               = $request->street;
            $customers_has_address->number               = $request->number;
            $customers_has_address->created_at = Carbon::now();
            $customers_has_address->updated_at = Carbon::now();
            $customers_has_address->save();

            DB::commit();


            return response()->success($customer, 'Se actualizó el cliente.');
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
            return response()->error('Error al crear cliente.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {

            $customer = Customers::find($id);

            if($customer == null){
                throw new \Exception('No se encuentra el cliente.');
            }

            $customer->delete();
            DB::commit();

            return response()->success($customer, 'Se eliminó el cliente.');
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

            $customer = Customers::find($id);

            if($customer == null){
                throw new \Exception('No se encuentra el cliente.');
            }

            $customer->is_active = !$customer->is_active;
            $customer->save();

            DB::commit();

            return response()->success($customer, 'Se cambio de estatus el cliente.');
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

    /**
     * Exportar reporte de productos
     */
    public function export_customers(Request $request)
    {
        try {
            $fileNme    = 'clientes_' . Carbon::now()->toDateString(). '.xlsx';
            return (new CustomersExport($request))->download($fileNme);

        } catch (\Exception $exception) {
            
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($exception);
            return response()->error('Error al cambiar de estatus el producto.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
