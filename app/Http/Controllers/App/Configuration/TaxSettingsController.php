<?php

namespace App\Http\Controllers\App\Configuration;


use App\Http\Controllers\Controller;
use App\Models\Configuration\TaxSettings;
use App\Models\Configuration\TaxSettingsHasRecord;
use App\Models\Products\Products;
use App\Models\Products\ProductsHasTaxes;
use App\Models\Sat\CatSatImpuesto;
use App\Models\Sat\CatSatTasaOCuota;
use App\Models\Sat\CatSatTipoFactor;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Log;
use Illuminate\Validation\ValidationException;



class TaxSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function collection(Request $request)
    {
        try {
            $query = TaxSettings::query();

            if($request->name){
                $query = $query->whereRaw('LOWER(name) LIKE (?) ',["%{$request->name}%"]);
            }
            if($request->tipo_impuesto_id){
                $query = $query->whereRaw('LOWER(tipo_impuesto_id) LIKE (?) ',["%{$request->tipo_impuesto_id}%"]);
            }
            
            if($request->percentage){
                $query = $query->whereRaw('LOWER(tasa_cuota_porcentage) LIKE (?) ',["%{$request->percentage}%"]);
            }
            
            
            $list = $query->orderBy('id', 'desc')->paginate($request->pageSize);

            return response()->success($list, 'Se consulto correctamnete la lista de configuración de impuestos.');


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
            return response()->error('Error conusltar la lista de configuración de impuestos', $response, $code);
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
            ], [
                'name.required' => 'El nombre del impuesto es obligatorio.',
            ]);

            $find_item = TaxSettings::where([
                ['name', $request['name']],
            ])->first();

            if($find_item != null){
                throw new \Exception('El nombre que ingreso ya existe en la base de datos.');
            }

            $request['is_products_new'] = $request['is_products_new'] === "true";
            

            $new_item = new TaxSettings();

            $new_item->name                     = $request->name;
            $new_item->is_products_new          = $request->is_products_new;
            $new_item->created_at = Carbon::now();
            $new_item->updated_at = Carbon::now();
            $new_item->save();

            $impuestos_iva = json_decode($request->input('impuestos_iva'), true);
            $impuestos_isr = json_decode($request->input('impuestos_isr'), true);
            $impuestos_ieps = json_decode($request->input('impuestos_ieps'), true);

            if ($impuestos_iva != null) {

                foreach ($impuestos_iva as $key => $tax) {
                    $tax = (object)$tax;

                    $tipoImpuesto = (object) $tax->tipo_impuesto_id;
                    $tipoFactor   = (object) $tax->tipo_factor_id;

                    $new_record = new TaxSettingsHasRecord();
                    $new_record->tax_settings_id       = $new_item->id;
                    $new_record->tipo_impuesto_id      = $tipoImpuesto->id;
                    $new_record->tipo_factor_id        = $tipoFactor->id;
                    $new_record->tasa_cuota_porcentage = number_format($tax->tasa_cuota_porcentage, 2, '.', '');

                    $new_record->is_traslado = true;

                    $new_record->created_at = now();
                    $new_record->updated_at = now();
                    $new_record->save();
                }
            }
            if ($impuestos_isr != null) {

                foreach ($impuestos_isr as $key => $tax) {
                    $tax = (object)$tax;

                    $tipoImpuesto = (object) $tax->tipo_impuesto_id;
                    $tipoFactor   = (object) $tax->tipo_factor_id;

                    $new_record = new TaxSettingsHasRecord();
                    $new_record->tax_settings_id       = $new_item->id;
                    $new_record->tipo_impuesto_id      = $tipoImpuesto->id;
                    $new_record->tipo_factor_id        = $tipoFactor->id;
                    $new_record->tasa_cuota_porcentage = number_format($tax->tasa_cuota_porcentage, 2, '.', '');

                    $new_record->is_retencion = true;

                    $new_record->created_at = now();
                    $new_record->updated_at = now();
                    $new_record->save();
                }
            }
            if ($impuestos_ieps != null) {
                foreach ($impuestos_ieps as $key => $tax) {
                    $tax = (object)$tax;

                    $tipoImpuesto = (object) $tax->tipo_impuesto_id;
                    $tipoFactor   = (object) $tax->tipo_factor_id;

                    $new_record = new TaxSettingsHasRecord();
                    $new_record->tax_settings_id       = $new_item->id;
                    $new_record->tipo_impuesto_id      = $tipoImpuesto->id;
                    $new_record->tipo_factor_id        = $tipoFactor->id;
                    $new_record->tasa_cuota_porcentage = number_format($tax->tasa_cuota_porcentage, 2, '.', '');

                    $new_record->is_ieps = true;

                    $new_record->created_at = now();
                    $new_record->updated_at = now();
                    $new_record->save();
                }
            }


            DB::commit();


            return response()->success($new_item, 'Se dio de alta la configuración del impuesto.');
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
            return response()->error('Error al crear la configuración del impuesto.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
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
            ], [
                'name.required' => 'El nombre del impuesto es obligatorio.',
            ]);

            $tax_settings = TaxSettings::find($id);

            if ($tax_settings == null) {
                throw new \Exception('No se encuentra la configuración del impuesto.');
            }

            $request['is_products_new'] = $request['is_products_new'] === "true";

            if($tax_settings->name != $request->name){

                $find_item = TaxSettings::where([
                    ['name', $request['name']],
                ])->first();

                if ($find_item != null) {
                    throw new \Exception('El nombre que ingreso ya existe en la base de datos.');
                }

            }


            $tax_settings->name                     = $request->name;
            $tax_settings->is_products_new          = $request->is_products_new;
            $tax_settings->created_at = Carbon::now();
            $tax_settings->updated_at = Carbon::now();
            $tax_settings->save();

            // Eliminar los impuestos anteriores
            $tax_settings->has_taxes()->delete();

            $impuestos_iva = json_decode($request->input('impuestos_iva'), true);
            $impuestos_isr = json_decode($request->input('impuestos_isr'), true);
            $impuestos_ieps = json_decode($request->input('impuestos_ieps'), true);

            if($impuestos_iva != null){

                foreach ($impuestos_iva as $key => $tax) {
                    $tax = (object)$tax;

                    $tipoImpuesto = (object) $tax->tipo_impuesto_id;
                    $tipoFactor   = (object) $tax->tipo_factor_id;

                    $new_record = new TaxSettingsHasRecord();
                    $new_record->tax_settings_id       = $tax_settings->id;
                    $new_record->tipo_impuesto_id      = $tipoImpuesto->id;
                    $new_record->tipo_factor_id        = $tipoFactor->id;
                    $new_record->tasa_cuota_porcentage = number_format($tax->tasa_cuota_porcentage, 2, '.', '');

                    $new_record->is_traslado = true;

                    $new_record->created_at = now();
                    $new_record->updated_at = now();
                    $new_record->save();
                }
            }
            if($impuestos_isr != null){

                foreach ($impuestos_isr as $key => $tax) {
                    $tax = (object)$tax;

                    $tipoImpuesto = (object) $tax->tipo_impuesto_id;
                    $tipoFactor   = (object) $tax->tipo_factor_id;

                    $new_record = new TaxSettingsHasRecord();
                    $new_record->tax_settings_id       = $tax_settings->id;
                    $new_record->tipo_impuesto_id      = $tipoImpuesto->id;
                    $new_record->tipo_factor_id        = $tipoFactor->id;
                    $new_record->tasa_cuota_porcentage = number_format($tax->tasa_cuota_porcentage, 2, '.', '');

                    $new_record->is_retencion = true;

                    $new_record->created_at = now();
                    $new_record->updated_at = now();
                    $new_record->save();
                }
            }
            if($impuestos_ieps != null){
                foreach ($impuestos_ieps as $key => $tax) {
                    $tax = (object)$tax;

                    $tipoImpuesto = (object) $tax->tipo_impuesto_id;
                    $tipoFactor   = (object) $tax->tipo_factor_id;

                    $new_record = new TaxSettingsHasRecord();
                    $new_record->tax_settings_id       = $tax_settings->id;
                    $new_record->tipo_impuesto_id      = $tipoImpuesto->id;
                    $new_record->tipo_factor_id        = $tipoFactor->id;
                    $new_record->tasa_cuota_porcentage = number_format($tax->tasa_cuota_porcentage, 2, '.', '');

                    $new_record->is_ieps = true;

                    $new_record->created_at = now();
                    $new_record->updated_at = now();
                    $new_record->save();
                }

            }

            // $update_product = self::updateProductsTax($tax_settings);

            // if($update_product['code'] != 200){
            //     return response()->error('Error al actualizar la configuración del impuesto.', $update_product, Response::HTTP_INTERNAL_SERVER_ERROR);
            // }
            
            DB::commit();
            
            return response()->success($tax_settings, 'Se actualizó la configuración del impuesto.');
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($response);
            return response()->error('Error al actualizar la configuración del impuesto.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {

            $tax_settings = TaxSettings::find($id);

            if($tax_settings == null){
                throw new \Exception('No se encuentra la configuración del impuesto.');
            }

            $tax_settings->has_taxes()->delete();
            
            $tax_settings->delete();

            $products_with_taxes = ProductsHasTaxes::where(ProductsHasTaxes::TAX_SETTINGS_ID, $tax_settings->id);

            $products_ids = $products_with_taxes->pluck(ProductsHasTaxes::PRODUCTS_ID);

            $products_with_taxes->delete();

            $products = Products::whereIn(Products::ID, $products_ids)->get();

            foreach ($products as $key => $product) {

                $has_taxes_id  = $product->has_taxes->pluck(ProductsHasTaxes::TAX_SETTINGS_ID);

                $tax_settings = TaxSettings::whereIn(TaxSettings::ID, $has_taxes_id)->get();

                $price = $product->price;
                if ($product->is_with_discount == 1) {
                    $price = $product->price - $product->discount;
                }
                if ($product->id == 10) {
                    $price_product = self::onPriceWithTaxs($tax_settings, $price);

                    if ($price_product == null) {
                        throw new \Exception('Ocurrio un error al generar el precio total de un producto.');
                    }

                    $product->sale_price = $price_product;
                    $product->updated_at = Carbon::now();
                    $product->save();
                }
            }

            DB::commit();

            return response()->success($tax_settings, 'Se eliminó la configuración del impuesto.');
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($response);
            return response()->error('Error al eliminar la configuración del impuesto.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Change status the specified resource from storage.
     */
    public function change_status(string $id)
    {
        DB::beginTransaction();
        try {

            $tax_settings = TaxSettings::find($id);

            if($tax_settings == null){
                throw new \Exception('No se encuentra la configuración del impuesto.');
            }

            $tax_settings->is_active = !$tax_settings->is_active;
            $tax_settings->save();

            DB::commit();

            return response()->success($tax_settings, 'Se cambio de estatus la configuración del impuesto.');
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($response);
            return response()->error('Error al cambiar de estatus la configuración del impuesto.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Busqueda de porcentaje para tasa o cuota
     */
    public function info_tasa_cuota(Request $request)
    {
        try {

            $catalogue_rango = collect();
            $catalogue_fijo = collect();

            $is_retencion = $request->is_retencion == 'false' ? 'No' : 'Sí';
            $is_traslado = $request->is_traslado == 'false' ? 'No' : 'Sí';

            $tipo_impuesto = CatSatImpuesto::find($request->tipo_impuesto_id);
            $tipo_factor = CatSatTipoFactor::find($request->tipo_factor_id);

            $cat_sat_tasa_cuota = CatSatTasaOCuota::where([
                ['impuesto', $tipo_impuesto->nombre],
                ['factor', $tipo_factor->nombre],
                ['retencion', $is_retencion],
                ['traslado', $is_traslado],
            ])->get();

            if ($cat_sat_tasa_cuota->count() <= 0) {
                throw new \Exception('De acuerdo al catalogó del SAT c_TasaOCuota, no existe impuesto para esta selección.');
            }

            foreach ($cat_sat_tasa_cuota as $key => $tasa_cuota) {

                switch ($tasa_cuota->rango_o_fijo) {
                    case CatSatTasaOCuota::RANGO:
                        $valor_maximo = $tasa_cuota->valor_maximo * 100;
                        $new_range = self::generate_catalogue_range($valor_maximo);

                        foreach ($new_range as $item) {
                            if (!$catalogue_rango->contains('codigo', $item['codigo'])) {
                                $catalogue_rango->push($item);
                            }
                        }
                        break;

                    case CatSatTasaOCuota::FIJO:
                        $codigo_fijo = number_format($tasa_cuota->valor_maximo * 100, 6, '.', '');

                        if (!$catalogue_fijo->contains('codigo', $codigo_fijo)) {
                            $catalogue_fijo->push(['codigo' => $codigo_fijo]);
                        }
                        break;

                    default:
                        break;
                }
            }

            $catalogue_tasa_cuota = $catalogue_rango->merge($catalogue_fijo)->unique('codigo')->values();
            

            return response()->success($catalogue_tasa_cuota, 'Se cambio de estatus la configuración del impuesto.');
        } catch (\Exception $exception) {
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($response);
            return response()->error('Error al cambiar de estatus la configuración del impuesto.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Generar catalogo de tasa o cuota
     */
    public function generate_catalogue_range($max)
    {
        return collect(range(0, $max))
            ->map(function ($numero) {
                $data = [
                    'codigo' => number_format($numero, 6, '.', ''),
                ];
                return $data;
            });
    }
    
    /**
     * Actualizar productos de acuerdo al impuesto editado
     */
    public function updateProductsTax(TaxSettings $tax_setting)
    {
        try {

            $products_with_taxes = Products::whereHas('has_taxes', function ($query) use ($tax_setting) {

                $query->where(ProductsHasTaxes::TAX_SETTINGS_ID, $tax_setting->id);
            })->get();
            

            // ! NO OLVIDES QUE EL IMPORTE SEA CERO

            foreach ($products_with_taxes as $key => $product) {

                $has_taxes_id  = $product->has_taxes->pluck(ProductsHasTaxes::TAX_SETTINGS_ID);

                $tax_settings = TaxSettings::whereIn(TaxSettings::ID, $has_taxes_id)->get();

                $price = $product->price;
                if($product->is_with_discount == 1){
                    $price = $product->price - $product->discount;
                }
                if($product->id == 10){
                    $price_product = self::onPriceWithTaxs($tax_settings, $price);

                    if ($price_product == null) {
                        throw new \Exception('Ocurrio un error al generar el precio total de un producto.');
                    }

                    $product->sale_price = $price_product;
                    $product->updated_at = Carbon::now();
                    $product->save();
                }

            }

            $response = [
                "code"    => 200,
                "message" => 'Se actualizaron los productos de forma correcta.',
            ];
            return $response;
        } catch (\Exception $exception) {
            $response = [
                "code"    => 500,
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            return $response;
        }
    }

    /**
     * Retorna el precio de un producto con Impuesto
     */
    public function onPriceWithTaxs($tax_settings, $price)
    {
        try {
            
            $price_with_tax = $price;
            
            foreach ($tax_settings as $key => $tax) {
                $tasa_cuota_porcentage = $tax->tasa_cuota_porcentage / 100;
                $tasa_cuota = $tasa_cuota_porcentage * $price;

                if ($tax->is_traslado == 1 && $tax->is_retencion === 0) {
                    
                    $price_with_tax += $tasa_cuota;
                }
                if ($tax->is_traslado == 0 && $tax->is_retencion === 1) {
                    
                    $price_with_tax -= $tasa_cuota;
                }
            }

            return $price_with_tax;
            
        } catch (\Exception $exception) {
            log::debug($exception);
            return null;
        }
    }

}
