<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\Sat\Addresses\CatSatCountry;
use App\Models\Sat\CatSatExportacion;
use App\Models\Sat\CatSatFormaPago;
use App\Models\Sat\CatSatMetodoPago;
use App\Models\Sat\CatSatRegimenFiscal;
use App\Models\Sat\CatSatUsoCfdi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class CustomersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Customers/Index', []);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {       
        $uso_cdfi = CatSatUsoCfdi::all();
        $regimen_fiscal = CatSatRegimenFiscal::all();
        $metodo_pago = CatSatMetodoPago::all();
        $forma_pago = CatSatFormaPago::all();
        $tipo_exportacion = CatSatExportacion::all();
        $paises = CatSatCountry::all();

        return Inertia::render('Customers/Create', 
        [
            'cat_uso_cdfi'          => $uso_cdfi,
            'cat_regimen_fiscal'    => $regimen_fiscal,
            'cat_metodo_pago'       => $metodo_pago,
            'cat_forma_pago'        => $forma_pago,
            'cat_tipo_exportacion'  => $tipo_exportacion,
            'cat_paises'            => $paises,
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

        $item_info = Customers::find($id);

        if($item_info == null){
            return redirect('/admin/proveedores');
        }

        return Inertia::render('Customers/Edit', 
            [
                'item_info' => $item_info,
            ]);
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
