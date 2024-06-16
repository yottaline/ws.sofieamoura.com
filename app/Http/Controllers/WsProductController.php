<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ws_product;
use App\Models\Season;
use App\Models\Size;
use App\Models\Category;

class WsProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        // return view('')
    }

    function load(Request $request)
    {
        $params = $request->q ? ['q' => $request->q] : [];
        $limit  = $request->limit;
        $offset = $request->offset;

        if($request->season) $params[] = ['product_season', $request->season];
        if($request->p_name) $params[] = ['product_name', $request->p_name];
        if($request->color) $params[]  = ['prodcolor_name', $request->color];

        echo json_encode(Ws_product::fetch(0, $params, $limit, $offset));
    }

    function view(String $ref)
    {
        $seasons = Season::fetch(0, [['season_visible', 1]]);
        $sizes = Size::fetch(0, [['size_visible', 1]]);
        $categories = Category::fetch(0, [['category_visible', 1]]);
        $data = Ws_product::fetch(0, [['product_ref', $ref]], 1);
        return view('contents.wsProducts.view', compact('data', 'seasons', 'categories', 'sizes'));
    }

}