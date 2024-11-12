<?php

namespace App\Http\Controllers\Configuration;

use App\Http\Controllers\Controller;
use App\Models\Configuration\TaxSettings;
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
        $cat_impuesto = CatSatImpuesto::all();
        
        return Inertia::render('Configuration/Taxes/Index', 
        [
            'cat_impuesto' => $cat_impuesto
        ]);
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

        $cat_sat_impuesto = CatSatImpuesto::all();
        $cat_sat_tipo_factor = CatSatTipoFactor::all();

        $item_info = TaxSettings::find($id);

        if($item_info == null){
            return redirect('/admin/categorias-de-producto');
        }
        $cat_tasa_cuota_prop = collect();
        $cat_tasa_cuota_prop->push(['codigo'=> $item_info->tasa_cuota_porcentage]);
        
        $data = [
            'item_info' => $item_info,
            'cat_sat_impuesto' => $cat_sat_impuesto,
            'cat_sat_tipo_factor' => $cat_sat_tipo_factor,
            'cat_tasa_cuota_prop' => $cat_tasa_cuota_prop,
        ];

        session()->flash('message', 'Recuerda que al editar un registro, se modificaran todos los productos que tienen aplicado el Impuesto.');
        session()->flash('status', 301);

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
