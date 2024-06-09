<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $currencies = Currency::fetch(0, [['currency_visible', 1]]);
        return view('contents.settings.index', compact('currencies'));
    }

    public function load()
    {
        $params[] = ['location_visible', 1];
        echo json_encode(Location::fetch(0, $params));
    }
}