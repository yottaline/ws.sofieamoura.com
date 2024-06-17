<?php

namespace App\Http\Controllers;

use App\Models\Currency;
use App\Models\Location;
use App\Models\Retailer;
use App\Models\Season;
use App\Models\Ws_order;
use App\Models\Ws_orders_product;
use App\Models\Ws_product;
use App\Models\Ws_products_size;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class WsOrderController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        return view('contents.wsOrders.index');
    }

    function load(Request $request)
    {
        $param = $request->q ? ['q' => $request->q] : [];
        $limit = $request->limit;
        $lastId = $request->last_id;
        if ($request->date)   $param[] = ['order_created', 'like', '%' . $request->date . '%'];
        if ($request->r_name) $param[] = ['retailer_fullName', 'like', '%' . $request->r_name . '%'];

        echo json_encode(Ws_order::fetch(0, $param, $limit, $lastId));
    }

    function submit(Request $request)
    {
        $ids = explode(',', $request->sizes);
        $qty = explode(',', $request->qty);

        $retailer = Retailer::fetch(auth()->user()->retailer_id);
        $ordSubtotal = $orderTotalDisc = $ordTotal = 0;
        $products  = Ws_products_size::fetch(0, null, $ids);
        foreach ($products as $p) {
            $indx = array_search($p->prodsize_id, $ids);
            if ($indx !== false) {
                $subtotal = $qty[$indx] * $p->prodsize_wsp;
                // $total    = $subtotal * $disc[$indx] / 100;
                $orderProductParam[] = [
                    'ordprod_product'       => $p->product_id,
                    'ordprod_size'          => $p->prodsize_id,
                    'ordprod_price'         => $p->prodsize_wsp,
                    'ordprod_request_qty'   => $qty[$indx],
                    'ordprod_subtotal'      => $subtotal,
                    'ordprod_total'         => $subtotal,
                    'ordprod_discount'      => 0,
                    'ordprod_served_qty'    => $qty[$indx]
                ];
                $ordSubtotal    += $subtotal;
                $ordTotal       += $subtotal;
            }
        }

        $orderParam = [
            'order_code'          => uniqidReal(10),
            'order_season'        => 1,
            'order_shipping'      => 0,
            'order_subtotal'      => $ordSubtotal,
            'order_discount'      => 0,
            'order_total'         => $ordTotal,
            'order_currency'      => 1,
            'order_type'          => 1,
            'order_note'          => $request->note,
            'order_created'       => Carbon::now(),
            'order_proforma'      => uniqidReal(20),
            'order_proformatime'  => Carbon::now(),
            'order_invoice'       => uniqidReal(30),
            'order_invoicetime'   => Carbon::now(),
            'order_status'        => 0,
            'order_retailer'      => $retailer->retailer_id,
            'order_bill_country'  => $retailer->retailer_country,
            'order_bill_province' => $retailer->retailer_province,
            'order_bill_city'     => $retailer->retailer_city,
            'order_bill_zip'      => $retailer->retailer_zip,
            'order_bill_line1'    => $retailer->retailer_address,
            // 'order_bill_line2'    => $retailer->retailer_city,
            'order_bill_phone'    => $retailer->retailer_phone,
            'order_ship_country'  => $retailer->retailer_country,
            'order_ship_province' => $retailer->retailer_province,
            'order_ship_city'     => $retailer->retailer_city,
            'order_ship_zip'      => $retailer->retailer_zip,
            'order_ship_line1'    => $retailer->retailer_address,
            // 'order_ship_line2'    => $retailer->retailer_city,
            'order_ship_phone'    => $retailer->retailer_phone,
        ];

        $result = Ws_order::submit(0, $orderParam, $orderProductParam);
        if ($result['status']) $result['data'] = Ws_order::fetch($result['id']);
        echo json_encode($result);
    }

    function view($code)
    {
        $order = Ws_order::fetch($code);
        $products = Ws_orders_product::fetch(0, [['ordprod_order', $order->order_id]]);

        return view('contents.wsOrders.view', compact('order', 'products'));
    }
}
