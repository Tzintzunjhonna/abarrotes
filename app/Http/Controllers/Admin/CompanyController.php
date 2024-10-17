<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Sat\Addresses\CatSatCountry;
use App\Models\Sat\CatSatExportacion;
use App\Models\Sat\CatSatFormaPago;
use App\Models\Sat\CatSatMetodoPago;
use App\Models\Sat\CatSatRegimenFiscal;
use App\Models\Sat\CatSatUsoCfdi;


class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $paises = CatSatCountry::all();
        $item_info = Company::first();
        
        // dd($item_info);
        $data = [
            'item_info'             => $item_info,
            'cat_paises'            => $paises,
        ];

        return Inertia::render('Admin/Company/Index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
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
        //
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
