<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\Sat\Addresses\CatSatLocation;
use App\Models\Sat\Addresses\CatSatMunicipality;
use App\Models\Sat\Addresses\CatSatState;
use App\Models\Sat\Addresses\CatSatZipCode;
use App\Models\Sat\CatSatClaveProducto;
use App\Models\Sat\CatSatClaveUnidad;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Log;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class CataloguesSatController extends Controller
{
    /**
     * Codigo postal
     */
    public function search_zip_code($zip_code)
    {
        try {

            $code_zip_code = CatSatZipCode::where('codigo', $zip_code)->first();

            if ($code_zip_code == null) {
                throw new \Exception('No se encontró el código postal');
            }

            $code_state = CatSatState::where('codigo', $code_zip_code->codigo_estado)->get();
            
            if ($code_state->count() <= 0) {
                throw new \Exception('No se encontró el estado para el código postal');
            }

            $cat_municipality = CatSatMunicipality::where([
                ['codigo_estado', $code_zip_code->codigo_estado],
                ['codigo', $code_zip_code->codigo_municipio],
            ])->get();
            $cat_location = CatSatLocation::where('codigo_postal', $zip_code)->get();

            $cat_all = [
                'zip_code' => $code_zip_code,
                'state' => $code_state,
                'cat_municipality' => $cat_municipality,
                'cat_location' => $cat_location,
            ];

            return response()->success($cat_all, 'Se consulto correctamnete la lista de codigo postal.');


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
            return response()->error('Error conusltar la lista de codigo postal', $response, $code);
        }
    }
    /**
     * Clave de producto servicio
     */
    public function search_clave_producto_servicio(Request $request)
    {
        try {

            if ($request->search) {
                $data = CatSatClaveProducto::where([
                    ['nombre', 'like', '%' . $request->search . '%'],
                ])->select('id', 'nombre', 'codigo')->get();
            } else {
                $data = CatSatClaveProducto::select('id', 'nombre', 'codigo')->take(10)->get();
            }

            return response()->success($data, 'Se consulto correctamnete la lista de produstos y servicios.');


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
            return response()->error('Error conusltar la lista de produstos y servicios', $response, $code);
        }
    }

    public function search_clave_clave_unidad(Request $request)
    {
        try {
            if ($request->search) {
                $data = CatSatClaveUnidad::where([
                    ['nombre', 'like', '%' . $request->search . '%'],
                ])->select('id', 'nombre', 'codigo')->get();
            }elseif ($request->id) {
                $data = CatSatClaveUnidad::where([
                    ['id', '=', $request->id],
                ])->select('id', 'nombre', 'codigo')->get();
            } else {
                $data = CatSatClaveUnidad::select('id', 'nombre', 'codigo')->take(10)->get();
            }

            return response()->success($data, 'Se consulto correctamente la lista de clave unidad.');
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
            return response()->error('Error conusltar la lista de clave unidad', $response, $code);
        }
    }
}
