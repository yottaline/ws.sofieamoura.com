<?php

namespace App\Http\Controllers;

use App\Models\Ws_order;
use App\Models\Ws_orders_product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        $order = Ws_order::fetch(0, [
            ['order_retailer', auth()->user()->retailer_id],
            ['order_status', '0'],
        ]);

        $order = count($order) ? $order[0] : [];
        $orderProducts = $order ? Ws_orders_product::fetch(0, [['ordprod_order', $order->order_id]]) : [];
        return view('home', compact('order', 'orderProducts'));
    }
}
