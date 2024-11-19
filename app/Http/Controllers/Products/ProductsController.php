<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Configuration\TaxSettings;
use App\Models\Products\CategorieProducts;
use App\Models\Products\Products;
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
        $cat_category = CategorieProducts::all();

        return Inertia::render('Products/Index', [
            'cat_category' => $cat_category,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {       

        $cat_provider = Providers::all();
        $cat_category = CategorieProducts::all();
        $unit_of_measurement = UnitOfMeasurement::all();
        $cat_tax_settings = TaxSettings::where(TaxSettings::IS_ACTIVE, 1)->get();

        return Inertia::render('Products/Create', 
        [
            'cat_provider' => $cat_provider,
            'cat_category' => $cat_category,
            'unit_of_measurement' => $unit_of_measurement,
            'cat_tax_settings' => $cat_tax_settings,
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
        $cat_tax_settings = TaxSettings::where(TaxSettings::IS_ACTIVE, 1)->get();

        // Colocar seleccionado de tax para vista.
        $has_taxes = $item_info->has_taxes;

        foreach ($has_taxes as $key => $tax) {
            foreach ($cat_tax_settings as $key => $tax_setting) {
                if($tax->tax_settings_id == $tax_setting->id){
                    $tax_setting->select_tax = true;
                }
            }
        }
        

        $data = [
            'item_info'             => $item_info,
            'cat_provider'          => $cat_provider,
            'cat_category'          => $cat_category,
            'unit_of_measurement'   => $unit_of_measurement,
            'cat_tax_settings'      => $cat_tax_settings,
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
