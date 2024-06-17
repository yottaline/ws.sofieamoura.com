<?php

namespace App\Http\Controllers;

use App\Models\Ws_products_color;
use App\Models\Ws_products_size;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WsProductsSizeController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    public function load(Request $request)
    {
        $param = [['prodsize_product', '=', $request->product_id]];
        echo json_encode(Ws_products_size::fetch(0, $param));
    }
}
