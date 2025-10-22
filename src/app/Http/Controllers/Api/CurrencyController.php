<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    // List all currencies
    public function index()
    {
        $currencies = Currency::all();
        return response()->json($currencies);
    }

    // Show a single currency
    public function show(Currency $currency)
    {
        return response()->json($currency);
    }

    // Optional: you could add refresh or sync methods here if needed
}
