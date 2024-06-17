<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ws_product;

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
}
