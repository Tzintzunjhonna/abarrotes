<?php

namespace App\Http\Controllers\SalesManagement;

use App\Http\Controllers\Controller;
use App\Models\Products\Products;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Products::where(Products::IS_ACTIVE, 1)->get();
        
        return Inertia::render('SalesManagement/Sales/Index', 
        [
            'cat_products' => $products
        ]);
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
