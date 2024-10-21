<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\CategorieProducts;
use App\Models\Providers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Log;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class CategoriesProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function collection(Request $request)
    {
        try {
            $query = CategorieProducts::query();

            if($request->name){
                $query = $query->whereRaw('LOWER(name) LIKE (?) ',["%{$request->name}%"]);
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
            ], [
                'name.required' => 'El nombre es obligatorio.',
            ]);

            $categorie_products = new CategorieProducts();

            $categorie_products->name       = $request->name;
            $categorie_products->created_at = Carbon::now();
            $categorie_products->updated_at = Carbon::now();
            $categorie_products->save();
            DB::commit();
            

            return response()->success($categorie_products, 'Se dio de alta la categoría de producto.');
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
            return response()->error('Error al crear la categoría de producto.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
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
                'name.required' => 'El nombre es obligatorio.',
            ]);

            $categorie_products = CategorieProducts::find($id);

            if($categorie_products == null){
                throw new \Exception('No se encuentra la categoría de producto.');
            }

            $categorie_products->name       = $request->name;
            $categorie_products->updated_at = Carbon::now();
            $categorie_products->save();

            DB::commit();
            
            return response()->success($categorie_products, 'Se actualizó la categoría de producto.');
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($response);
            return response()->error('Error al actualizar la categoría de producto.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {

            $categorie_products = CategorieProducts::find($id);

            if($categorie_products == null){
                throw new \Exception('No se encuentra la categoría de producto.');
            }

            $categorie_products->delete();
            DB::commit();

            return response()->success($categorie_products, 'Se eliminó la categoría de producto.');
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($response);
            return response()->error('Error al eliminar la categoría de producto.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    /**
     * Change status the specified resource from storage.
     */
    public function change_status(string $id)
    {
        DB::beginTransaction();
        try {

            $categorie_products = CategorieProducts::find($id);

            if($categorie_products == null){
                throw new \Exception('No se encuentra la categoría de producto.');
            }

            $categorie_products->is_active = !$categorie_products->is_active;
            $categorie_products->save();

            DB::commit();

            return response()->success($categorie_products, 'Se cambio de estatus la categoría de producto.');
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($response);
            return response()->error('Error al cambiar de estatus la categoría de producto.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
