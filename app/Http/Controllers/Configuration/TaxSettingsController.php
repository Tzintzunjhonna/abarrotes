<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use App\Models\CategorieProducts;
use App\Models\Sat\CatSatImpuesto;
use App\Models\Sat\CatSatTipoFactor;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TaxSettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Configuration/Taxes/Index', []);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

        $cat_sat_impuesto = CatSatImpuesto::all();
        $cat_sat_tipo_factor = CatSatTipoFactor::all();

        return Inertia::render('Configuration/Taxes/Create', 
        [
            'cat_sat_impuesto' => $cat_sat_impuesto,
            'cat_sat_tipo_factor' => $cat_sat_tipo_factor,
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
        // $ids = explode(',', $ids);

        $item_info = CategorieProducts::find($id);

        if($item_info == null){
            return redirect('/admin/categorias-de-producto');
        }

        
        $data = [
            'item_info'             => $item_info,
        ];
        
        return Inertia::render('Configuration/Taxes/Edit', $data);
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
