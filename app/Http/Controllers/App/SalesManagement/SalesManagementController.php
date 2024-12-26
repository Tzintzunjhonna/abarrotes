<?php

namespace App\Http\Controllers\App\SalesManagement;

use App\Exports\SalesHistoryExport;
use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\Products\Products;
use App\Models\Products\ProductsHasCatSat;
use App\Models\Products\ProductsHasTaxes;
use App\Models\SalesManagement\Sale;
use App\Models\SalesManagement\SalesDetail;
use App\Models\SalesManagement\SalesDetailHasTax;
use App\Models\StatusSale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Log;
use Illuminate\Validation\ValidationException;

class SalesManagementController extends Controller
{
    /**
     * Colection de ventas realziadas
     */
    public function history_sales_collection(Request $request)
    {
        try {
            $query = Sale::query();

            if($request->ticket_no){
                $query = $query->where(Sale::TICKET_NO, 'LIKE', "%$request->ticket_no%");
            }
            if($request->status_sale_id){
                $query = $query->where(Sale::STATUS_SALE_ID, $request->status_sale_id);
            }
            if($request->payment_type_id){
                $query = $query->where(Sale::PAYMENT_TYPE_ID, $request->payment_type_id);
            }

            if ($request->customer) {

                $query = $query->whereHas('customer', function ($query) use ($request) {

                    $query = $query->whereRaw('LOWER(business_name) LIKE (?) ', ["%{$request->customer}%"]);
                });
            }
            
            $list = $query->orderBy('id', 'desc')->paginate($request->pageSize);

            return response()->success($list, 'Se consulto correctamnete la lista de ventas.');


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
            return response()->error('Error conusltar la lista de ventas', $response, $code);
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
                'products'          => 'required',
                'amountImport'      => 'required',
                'amountTax'         => 'required',
                'amountDiscount'    => 'required',
                'amountTotal'       => 'required',
                'type_payment'      => 'required',
            ], [
                'products.required'         => 'Los productos son obligatorios.',
                'amountImport.required'     => 'El importe es obligatorio.',
                'amountTax.required'        => 'El total de impuesto es obligatorio.',
                'amountDiscount.required'   => 'El descuento es obligatorio.',
                'amountTotal.required'      => 'El total es obligatorio.',
                'type_payment.required'     => 'El tipo de cobro es obligatorio.',
            ]);


            $date = Carbon::now();
            $date_ticket = $date->format('Ymd');
            
            $find_sale = Sale::where(Sale::TICKET_NO, 'LIKE', "%$date_ticket%")->orderBy(Sale::ID, 'desc')->first();

            if ($find_sale != null) {
                $conteo = (int)substr($find_sale->ticket_no, 8) + 1;
            } else {
                $conteo = 1;
            }

            // Formateamos el conteo con 3 dígitos
            $conteoFormato = str_pad($conteo, 3, '0', STR_PAD_LEFT);

            // Generamos el número completo de ticket
            $ticket_no = $date_ticket . $conteoFormato;

            $new_sale = new Sale();

            $new_sale->customer_id               = ($request->customer == 'null' || $request->customer == null) ? null : $request->customer;
            $new_sale->ticket_no                 = $ticket_no;
            $new_sale->date_sale                 = Carbon::now();
            $new_sale->total                     = $request->amountTotal;
            $new_sale->sub_total                 = $request->amountImport;
            $new_sale->total_discount            = $request->amountDiscount;
            $new_sale->total_taxt                = $request->amountTax;
            $new_sale->mount_pay                 = $request->paid_with;
            $new_sale->change                    = $request->his_change ? $request->his_change : 0;
            $new_sale->payment_type_id           = $request->type_payment;
            $new_sale->status_sale_id            = StatusSale::COMPLETADO;
            $new_sale->comentarios               = 'Nota';
            $new_sale->is_with_invoice           = $request['is_invoice'] === "true";
            $new_sale->card_payment_reference    = $request->card_payment_reference;
            $new_sale->voucher_payment_reference = $request->voucher_payment_reference;

            $new_sale->created_at = Carbon::now();
            $new_sale->updated_at = Carbon::now();
            $new_sale->save();

            $products = json_decode($request->products, true);

            foreach ($products as $key => $product) {

                $find_product = Products::find($product['id']);
                
                if($find_product == null){
                    throw new \Exception('No se encuentra el producto en la base de datos: Código de producto: '. $product['barcode']);
                }

                $new_sale_detail = new SalesDetail();
                $new_sale_detail->venta_id       = $new_sale->id;
                $new_sale_detail->producto_id    = $find_product->id;
                $new_sale_detail->name           = $find_product->name;
                $new_sale_detail->description    = $find_product->description;
                $new_sale_detail->barcode        = $find_product->barcode;
                $new_sale_detail->cantidad       = $product['quantity'];
                $new_sale_detail->precio_unitario= $product['price'];
                $new_sale_detail->descuento      = $product['discount'];
                $new_sale_detail->importe        = $product['import'];
                $new_sale_detail->is_with_tax    = $find_product->is_with_tax;

                $new_sale_detail->created_at = Carbon::now();
                $new_sale_detail->updated_at = Carbon::now();
                $new_sale_detail->save();


                if($find_product->is_with_tax == true){

                    $has_taxes = $find_product->has_taxes;

                    foreach ($has_taxes as $key => $tax) {

                        $tax_new = new SalesDetailHasTax();

                        $tax_new->sales_detail_id        = $new_sale_detail->id;
                        $tax_new->tipo_impuesto_id       = $tax->tax_setting->tipo_impuesto_id;
                        $tax_new->tipo_factor_id         = $tax->tax_setting->tipo_factor_id;
                        $tax_new->tasa_cuota_porcentage  = $tax->tax_setting->tasa_cuota_porcentage;
                        $tax_new->is_retencion           = $tax->tax_setting->is_retencion;
                        $tax_new->is_traslado            = $tax->tax_setting->is_traslado;

                        $tax_new->created_at = Carbon::now();
                        $tax_new->updated_at = Carbon::now();
                        $tax_new->save();
                    }
                }
            }

            DB::commit();
            
            return response()->success($new_sale, 'Se realizó la venta.');
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
            return response()->error('Error al crear la venta.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Asignar cliente a venta
     */
    public function assing_customer_to_sale(string $id_sale, string $id_customer, Request $request)
    {

        DB::beginTransaction();
        try {


            $id_sale = base64_decode($id_sale);
            $id_customer = base64_decode($id_customer);

            $sale_info = Sale::find($id_sale);

            $customer_info = Customers::find($id_customer);

            if ($sale_info == null) {
                throw new \Exception('No se encuentra la venta.');
            }

            if ($customer_info == null) {
                throw new \Exception('No se encontró el cliente.');
            }


            $sale_info->customer_id = $customer_info->id;
            $sale_info->is_with_invoice = $request['is_with_invoice'] === "true";
            $sale_info->save();

            DB::commit();

            return response()->success($sale_info, 'Se agregó el cliente a la venta.');
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
                throw new \Exception('No se encuentra la venta.');
            }

            if ($product->barcode != $request->barcode && Products::where('barcode', $request->barcode)->exists()) {
                throw new \Exception('El código dla venta ha sido registrado previamente.');
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
            
            return response()->success($product, 'Se actualizó la venta.');
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($exception);
            return response()->error('Error al actualizar la venta.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
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
                throw new \Exception('No se encuentra la venta.');
            }

            $products->delete();
            DB::commit();

            return response()->success($products, 'Se eliminó la venta.');
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($exception);
            return response()->error('Error al eliminar la venta.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Change status the specified resource from storage.
     */
    public function change_status(string $id_sale, string $id_status, Request $request)
    {
        DB::beginTransaction();
        try {

            $sale_info = Sale::find($id_sale);

            if($sale_info == null){
                throw new \Exception('No se encuentra la venta.');
            }

            $sale_info->status_sale_id = $id_status;
            $sale_info->comentarios = $request->reason_for_cancellation;
            $sale_info->save();

            DB::commit();

            return response()->success($sale_info, 'Se cambio de estatus la venta.');
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($exception);
            return response()->error('Error al generar el reporte de las ventas.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Exportar reporte de ventas realizadas
     */
    public function export_sales(Request $request)
    {
        try {
            $fileNme    = 'Ventas_' . Carbon::now()->toDateString(). '.xlsx';
            return (new SalesHistoryExport($request))->download($fileNme);

        } catch (\Exception $exception) {
            
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($exception);
            return response()->error('Error al cambiar de estatus la venta.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
