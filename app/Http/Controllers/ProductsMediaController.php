<?php

namespace App\Http\Controllers;

use App\Models\Products_media;
use Illuminate\Http\Request;

class ProductsMediaController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    function load(Request $request)
    {
        $param[] = ['media_product', $request->product_id];
        echo json_encode(Products_media::fetch(0, $param));
    }
}
