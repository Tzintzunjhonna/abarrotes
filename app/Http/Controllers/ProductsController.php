<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CategorieProducts;
use App\Models\Products;
use App\Models\Providers;
use App\Models\UnitOfMeasurement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Products/Index', []);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {       

        $cat_provider = Providers::all();
        $cat_category = CategorieProducts::all();
        $unit_of_measurement = UnitOfMeasurement::all();

        return Inertia::render('Products/Create', 
        [
            'cat_provider' => $cat_provider,
            'cat_category' => $cat_category,
            'unit_of_measurement' => $unit_of_measurement,
        ]);
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
        $id = base64_decode($token);

        $item_info = Products::find($id);

        if($item_info == null){
            return redirect('/admin/productos');
        }

        $cat_provider = Providers::all();
        $cat_category = CategorieProducts::all();
        $unit_of_measurement = UnitOfMeasurement::all();
        
        $data = [
            'item_info'             => $item_info,
            'cat_provider'          => $cat_provider,
            'cat_category'          => $cat_category,
            'unit_of_measurement'   => $unit_of_measurement,
        ];
        
        return Inertia::render('Products/Edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
