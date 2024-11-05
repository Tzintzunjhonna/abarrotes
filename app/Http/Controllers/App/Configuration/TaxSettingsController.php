<?php

namespace App\Http\Controllers\App\Configuration;


use App\Http\Controllers\Controller;
use App\Models\Configuration\TaxSettings;
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
                'tipo_impuesto_id' => 'required',
                'tipo_factor_id' => 'required',
                'is_retencion' => 'required',
                'is_traslado' => 'required',
                'tasa_cuota_porcentage' => 'required',
                'is_products_new' => 'required',
            ], [
                'name.required' => 'El nombre del impuesto es obligatorio.',
                'tipo_impuesto_id.required' => 'El tipo de impuesto es obligatorio.',
                'tipo_factor_id.required' => 'El tipo de factor es obligatorio.',
                'is_retencion.required' => 'Si es retencion es obligatorio.',
                'is_traslado.required' => 'Si es traslado es obligatorio.',
                'tasa_cuota_porcentage.required' => 'El porcentaje de tasa o cuota es obligatoria es obligatorio.',
                'is_products_new.required' => 'Aplica para todos los productos nuevos es obligatorio.',
            ]);

            $request['is_retencion']    = $request['is_retencion'] === "true";
            $request['is_traslado']     = $request['is_traslado'] === "true";
            $request['is_products_new'] = $request['is_products_new'] === "true";

            $find_item = TaxSettings::where([
                ['is_retencion', $request['is_retencion']],
                ['is_traslado', $request['is_traslado']],
                ['tipo_impuesto_id', $request['tipo_impuesto_id']],
                ['tipo_factor_id', $request['tipo_factor_id']],
                ['tasa_cuota_porcentage', number_format($request['tasa_cuota_porcentage'], 2, '.')],
            ])->first();

            if($find_item != null){
                throw new \Exception('La configuración que seleccionó ya esta guardado.');
            }
            $new_item = new TaxSettings();

            $new_item->name                     = $request->name;
            $new_item->tipo_impuesto_id         = $request->tipo_impuesto_id;
            $new_item->tipo_factor_id           = $request->tipo_factor_id;
            $new_item->is_retencion             = $request->is_retencion;
            $new_item->is_traslado              = $request->is_traslado;
            $new_item->tasa_cuota_porcentage    = $request->tasa_cuota_porcentage;
            $new_item->is_products_new          = $request->is_products_new;
            $new_item->created_at = Carbon::now();
            $new_item->updated_at = Carbon::now();
            $new_item->save();
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
                'tipo_impuesto_id' => 'required',
                'tipo_factor_id' => 'required',
                'is_retencion' => 'required',
                'is_traslado' => 'required',
                'tasa_cuota_porcentage' => 'required',
                'is_products_new' => 'required',
            ], [
                'name.required' => 'El nombre del impuesto es obligatorio.',
                'tipo_impuesto_id.required' => 'El tipo de impuesto es obligatorio.',
                'tipo_factor_id.required' => 'El tipo de factor es obligatorio.',
                'is_retencion.required' => 'Si es retencion es obligatorio.',
                'is_traslado.required' => 'Si es traslado es obligatorio.',
                'tasa_cuota_porcentage.required' => 'El porcentaje de tasa o cuota es obligatoria es obligatorio.',
                'is_products_new.required' => 'Aplica para todos los productos nuevos es obligatorio.',
            ]);

            $request['is_retencion']    = $request['is_retencion'] === "true";
            $request['is_traslado']     = $request['is_traslado'] === "true";
            $request['is_products_new'] = $request['is_products_new'] === "true";

            $tax_settings = TaxSettings::find($id);

            if ($tax_settings == null) {
                throw new \Exception('No se encuentra la configuración del impuesto.');
            }

            $find_item = TaxSettings::where([
                ['is_retencion', $request['is_retencion']],
                ['is_traslado', $request['is_traslado']],
                ['tipo_impuesto_id', $request['tipo_impuesto_id']],
                ['tipo_factor_id', $request['tipo_factor_id']],
                ['tasa_cuota_porcentage', number_format($request['tasa_cuota_porcentage'], 2, '.')],
            ])->first();

            if ($find_item != null && $find_item->id != $tax_settings->id) {
                throw new \Exception('La configuración que seleccionó ya esta guardado.');
            }


            $tax_settings->name                     = $request->name;
            $tax_settings->tipo_impuesto_id         = $request->tipo_impuesto_id;
            $tax_settings->tipo_factor_id           = $request->tipo_factor_id;
            $tax_settings->is_retencion             = $request->is_retencion;
            $tax_settings->is_traslado              = $request->is_traslado;
            $tax_settings->tasa_cuota_porcentage    = $request->tasa_cuota_porcentage;
            $tax_settings->is_products_new          = $request->is_products_new;
            $tax_settings->created_at = Carbon::now();
            $tax_settings->updated_at = Carbon::now();
            $tax_settings->save();

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

            $tax_settings->delete();
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

}
