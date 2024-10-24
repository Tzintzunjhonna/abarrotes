<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use App\Models\Products;
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


class ProductsController extends Controller
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
        try {

            $request->validate([
                'name' => 'required',
                'description' => 'required',
                'barcode' => 'required|unique:products,barcode',
                'price' => 'required',
                'discount' => 'required',
                'stock' => 'required',
                'category_id' => 'required',
                'provider_id' => 'required',
                'unit_of_measurement' => 'required',
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
                'barcode.unique' => 'El código del producto ha sido registrado previamente.',
            ]);

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

            $product->name       = $request->name;
            $product->created_at = Carbon::now();
            $product->updated_at = Carbon::now();
            $product->save();
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
            log::debug($response);
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
            $product->updated_at = Carbon::now();
            $product->save();

            DB::commit();
            
            return response()->success($product, 'Se actualizó el producto.');
        } catch (\Exception $exception) {
            DB::rollBack();
            $response = [
                "file"    => $exception->getFile(),
                "line"    => $exception->getLine(),
                "message" => $exception->getMessage(),
            ];
            log::debug($response);
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
            log::debug($response);
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
            log::debug($response);
            return response()->error('Error al cambiar de estatus el producto.', $response, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
