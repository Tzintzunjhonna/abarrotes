<?php

namespace App\Http\Controllers\App\SalesManagement;

use App\Exports\ProductsExport;
use App\Http\Controllers\Controller;
use App\Models\Products\Products;
use App\Models\Products\ProductsHasCatSat;
use App\Models\Products\ProductsHasTaxes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;

class SalesManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function collection(Request $request)
    {
        try {
            $query = Products::query();

            if($request->name){
                $query = $query->whereRaw('LOWER(name) LIKE (?) ',["%{$request->name}%"]);
            }
            
            if($request->description){
                $query = $query->whereRaw('LOWER(description) LIKE (?) ',["%{$request->description}%"]);
            }

            if($request->barcode){
                $query = $query->whereRaw('LOWER(barcode) LIKE (?) ',["%{$request->barcode}%"]);
            }

            if($request->category_id){
                $query = $query->whereRaw('LOWER(category_id) LIKE (?) ',["%{$request->category_id}%"]);
            }
            
            if($request->name_provider){

                $query = $query->whereHas('has_provider', function ($query) use ($request) {

                    $query = $query->whereRaw('LOWER(name) LIKE (?) ', ["%{$request->name_provider}%"]);
                });
            }

            
            $list = $query->orderBy('id', 'desc')->paginate($request->pageSize);

            return response()->success($list, 'Se consulto correctamnete la lista de productos.');


        } catch (\Exception $exception) {
            log::debug($exception);
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            $code = Response::HTTP_INTERNAL_SERVER_ERROR;

            if ($exception->getCode()) {
                $code = $exception->getCode();
            }
            return response()->error('Error conusltar la lista de productos', $response, $code);
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
        dd($request->all());
        try {

            // $request->validate([],[]);

            // customer: null
            // is_invoice: false
            // options_sale: false
            // paid_with: 14.65
            // his_change: 0.01
            // card_payment_reference: 
            // voucher_payment_reference: 
            // products: [{"barcode":"7501557140162","name":"PRODUCTO 1","price":"12.00","discount":"2.00","quantity":1,"import":12,"stock":11,"is_with_tax":1,"has_taxes":[{"id":34,"products_id":10,"tax_settings_id":2,"created_at":"2024-12-05 20:11:05","updated_at":"2024-12-05 20:11:05","deleted_at":null,"tax_setting":{"id":2,"tipo_impuesto_id":3,"tipo_factor_id":1,"tasa_cuota_porcentage":"30.40","is_retencion":0,"is_traslado":1,"is_products_new":0,"is_active":1,"created_at":"2024-11-05 22:00:20","updated_at":"2024-11-05 22:00:20","deleted_at":null,"name":"IEPS 30.40 Traslado","has_tipo_impuesto":{"id":3,"codigo":"003","nombre":"IEPS","created_at":"2024-10-25T18:20:22.000000Z","updated_at":"2024-10-25T18:20:22.000000Z","deleted_at":null},"has_tipo_factor":{"id":1,"codigo":"Tasa","nombre":"Tasa","created_at":"2024-10-25T18:20:22.000000Z","updated_at":"2024-10-25T18:20:22.000000Z","deleted_at":null}}},{"id":35,"products_id":10,"tax_settings_id":12,"created_at":"2024-12-05 20:11:05","updated_at":"2024-12-05 20:11:05","deleted_at":null,"tax_setting":{"id":12,"tipo_impuesto_id":2,"tipo_factor_id":1,"tasa_cuota_porcentage":"16.00","is_retencion":0,"is_traslado":1,"is_products_new":0,"is_active":1,"created_at":"2024-11-12 17:30:15","updated_at":"2024-11-12 17:30:15","deleted_at":null,"name":"IVA TRADLADO 16 %","has_tipo_impuesto":{"id":2,"codigo":"002","nombre":"IVA","created_at":"2024-10-25T18:20:22.000000Z","updated_at":"2024-10-25T18:20:22.000000Z","deleted_at":null},"has_tipo_factor":{"id":1,"codigo":"Tasa","nombre":"Tasa","created_at":"2024-10-25T18:20:22.000000Z","updated_at":"2024-10-25T18:20:22.000000Z","deleted_at":null}}}]}]
            // amountImport: 12
            // amountTax: 4.64
            // amountDiscount: 2
            // amountTotal: 14.64

            $product = new Products();

            $product->name                  = $request->name;
            $product->description           = $request->description;
            $product->barcode               = $request->barcode;
            $product->price                 = $request->price;
            $product->discount              = $request->discount;
            $product->stock                 = $request->stock;
            $product->category_id           = $request->category_id;
            $product->provider_id           = $request->provider_id;
            $product->unit_of_measurement   = $request->unit_of_measurement;
            $product->revenue               = $request->revenue;
            $product->sale_price            = $request->sale_price;
            $product->wholesale_price       = $request->wholesale_price;
            $product->is_with_tax           = $request['is_with_tax'] === "true";
            $product->is_with_discount      = $request['is_with_discount'] === "true";

            $product->created_at = Carbon::now();
            $product->updated_at = Carbon::now();
            $product->save();

            $has_taxes = json_decode($request->has_taxes, true);

            //Guardar el IVA
            foreach ($has_taxes as $key => $value) {
                $products_has_taxes = new ProductsHasTaxes();
                $products_has_taxes->products_id        = $product->id;
                $products_has_taxes->tax_settings_id    = $value['id'];

                $products_has_taxes->created_at = Carbon::now();
                $products_has_taxes->updated_at = Carbon::now();
                $products_has_taxes->save();
            }

            //Guardar catalogo del SAT para cada producto
            $products_has_cat_sat = new ProductsHasCatSat();

            $products_has_cat_sat->products_id          = $product->id;
            $products_has_cat_sat->clave_producto_id    = $request->clave_producto_id;
            $products_has_cat_sat->clave_unidad_id      = $request->clave_unidad_id;

            $products_has_cat_sat->created_at = Carbon::now();
            $products_has_cat_sat->updated_at = Carbon::now();
            $products_has_cat_sat->save();


            DB::commit();
            

            return response()->success($product, 'Se dio de alta el producto.');
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
            log::debug($exception);
            return response()->error('Error al crear el producto.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
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
                'description' => 'required',
                'barcode' => 'required',
                'price' => 'required',
                'discount' => 'required',
                'stock' => 'required',
                'category_id' => 'required',
                'provider_id' => 'required',
                'unit_of_measurement' => 'required',
                'revenue' => 'required',
                'sale_price' => 'required',
                'wholesale_price' => 'required',
            ], [
                'name.required' => 'El nombre es obligatorio.',
                'description.required' => 'El descripción es obligatorio.',
                'barcode.required' => 'El código de barras es obligatorio.',
                'price.required' => 'El precio es obligatorio.',
                'discount.required' => 'El descuento es obligatorio.',
                'stock.required' => 'El stock es obligatorio.',
                'category_id.required' => 'La categoría es obligatorio.',
                'provider_id.required' => 'El proveedor es obligatorio.',
                'unit_of_measurement.required' => 'La unidad de medida es obligatorio.',
                'revenue.required' => 'La ganancia es obligatorio.',
                'sale_price.required' => 'El precio venta es obligatorio.',
                'wholesale_price.required' => 'El precio mayoreo es obligatorio.',
            ]);

            $product = Products::where('id', $id)->whereNull('deleted_at')->first();

            if ($product == null) {
                throw new \Exception('No se encuentra el producto.');
            }

            if ($product->barcode != $request->barcode && Products::where('barcode', $request->barcode)->exists()) {
                throw new \Exception('El código del producto ha sido registrado previamente.');
            }

            $product->name                  = $request->name;
            $product->description           = $request->description;
            $product->barcode               = $request->barcode;
            $product->price                 = $request->price;
            $product->discount              = $request->discount;
            $product->stock                 = $request->stock;
            $product->category_id           = $request->category_id;
            $product->provider_id           = $request->provider_id;
            $product->unit_of_measurement   = $request->unit_of_measurement;

            $product->revenue               = $request->revenue;
            $product->sale_price            = $request->sale_price;
            $product->wholesale_price       = $request->wholesale_price;
            $product->is_with_tax           = $request['is_with_tax'] === "true";
            $product->is_with_discount      = $request['is_with_discount'] === "true";

            $product->updated_at = Carbon::now();
            $product->save();

            $has_taxes = json_decode($request->has_taxes, true);

            foreach ($product->has_taxes as $key => $value) {
                $value->forceDelete();
            }

            //Guardar el IVA
            foreach ($has_taxes as $key => $value) {
                $products_has_taxes = new ProductsHasTaxes();
                $products_has_taxes->products_id        = $product->id;
                $products_has_taxes->tax_settings_id    = $value['id'];

                $products_has_taxes->created_at = Carbon::now();
                $products_has_taxes->updated_at = Carbon::now();
                $products_has_taxes->save();
            }

            //Guardar catalogo del SAT para cada producto
            
            if($product->has_cat_sat != null) {
                $product->has_cat_sat->forceDelete();
            }
            
            $products_has_cat_sat = new ProductsHasCatSat();

            $products_has_cat_sat->products_id          = $product->id;
            $products_has_cat_sat->clave_producto_id    = $request->clave_producto_id;
            $products_has_cat_sat->clave_unidad_id      = $request->clave_unidad_id;

            $products_has_cat_sat->created_at = Carbon::now();
            $products_has_cat_sat->updated_at = Carbon::now();
            $products_has_cat_sat->save();

            DB::commit();
            
            return response()->success($product, 'Se actualizó el producto.');
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($exception);
            return response()->error('Error al actualizar el producto.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {

            $products = Products::find($id);

            if($products == null){
                throw new \Exception('No se encuentra el producto.');
            }

            $products->delete();
            DB::commit();

            return response()->success($products, 'Se eliminó el producto.');
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($exception);
            return response()->error('Error al eliminar el producto.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Change status the specified resource from storage.
     */
    public function change_status(string $id)
    {
        DB::beginTransaction();
        try {

            $products = Products::find($id);

            if($products == null){
                throw new \Exception('No se encuentra el producto.');
            }

            $products->is_active = !$products->is_active;
            $products->save();

            DB::commit();

            return response()->success($products, 'Se cambio de estatus el producto.');
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($exception);
            return response()->error('Error al cambiar de estatus el producto.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Exportar reporte de productos
     */
    public function export_products(Request $request)
    {
        try {
            $fileNme    = 'productos_' . Carbon::now()->toDateString(). '.xlsx';
            return (new ProductsExport($request))->download($fileNme);

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
