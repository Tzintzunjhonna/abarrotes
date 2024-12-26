<?php

namespace App\Http\Controllers\SalesManagement;

use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\PaymentType;
use App\Models\Products\Products;
use App\Models\StatusSale;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SalesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexToCheckoutCounter()
    {
        $products = Products::where(Products::IS_ACTIVE, 1)->get();
        $customers = Customers::where(Customers::IS_ACTIVE, 1)->get();
        
        return Inertia::render('SalesManagement/Sales/Index', 
        [
            'cat_products' => $products,
            'cat_customer' => $customers,
        ]);
    }

    /**
     * Index para el historial de ventas.
     */
    public function indexToHistory()
    {
        $customers = Customers::where(Customers::IS_ACTIVE, 1)->get();
        $statusSale = StatusSale::whereNot(StatusSale::ID, StatusSale::TIMBRADO)->get();
        $paymentType = PaymentType::all();

        return Inertia::render(
            'SalesManagement/HistorySales/Index',
            [
                'cat_customer' => $customers,
                'cat_status_sale' => $statusSale,
                'cat_payment_type' => $paymentType,
            ]
        );
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
