<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    function load()
    {
        $params[] = ['location_visible', 1];
        echo json_encode(Location::fetch(0, $params));
    }
}
