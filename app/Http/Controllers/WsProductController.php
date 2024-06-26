<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ws_product;
use App\Models\Ws_products_size;
use App\Models\Products_media;

class WsProductController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    function load(Request $request)
    {
        $params = $request->q ? ['q' => $request->q] : [];
        echo json_encode(Ws_product::fetch(0, $params, $request->limit, $request->offset));
    }

    function sizesAndMedia(Request $request)
    {
        $param = [['prodcolor_slug', $request->slug]];
        echo json_encode([
            'sizes' => Ws_products_size::fetch(0, $param),
            'media' => Products_media::fetch(0, $param),
        ]);
    }
}
