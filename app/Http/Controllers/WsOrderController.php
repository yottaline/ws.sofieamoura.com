<?php

namespace App\Http\Controllers;

use App\Models\Retailer;
use App\Models\Ws_order;
use App\Models\Ws_orders_product;
use App\Models\Ws_products_size;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class WsOrderController extends Controller
{
    function __construct()
    {
        $this->middleware('auth');
    }

    function index()
    {
        return view('contents.orders.index');
    }

    function load(Request $request)
    {
        $param = $request->q ? ['q' => $request->q] : [];
        $limit = $request->limit;
        $lastId = $request->last_id;
        if ($request->date)   $param[] = ['order_created', 'like', '%' . $request->date . '%'];
        $param[] = ['order_retailer', auth()->user()->retailer_id];
        echo json_encode(Ws_order::fetch(0, $param, $limit, $lastId));
    }

    function add(Request $request)
    {
        $retailer = auth()->user()->retailer_id;

        $ids = explode(',', $request->sizes);
        $qtys = explode(',', $request->qtys);

        $products  = Ws_products_size::fetch(0, null, $ids);
        $order = Ws_order::fetch(0, [
            ['order_retailer', $retailer],
            ['order_status', '0'],
        ]);
        if (!count($order)) {
            $order = Ws_order::createOrder([
                'order_code'          => uniqidReal(10),
                'order_season'        => 1,
                'order_currency'      => 1,
                'order_type'          => 1,
                'order_created'       => Carbon::now(),
                'order_retailer'      => $retailer,
            ]);
            if (!$order) {
                echo json_encode(['status' => false, 'message' => 'Error on creating a new order']);
                return;
            }
        } else {
            $order = $order[0]->order_id;
        }

        $ordProducts  = Ws_orders_product::fetch(0, [['order_id', $order]])->toArray();
        $ordProductsSizes = count($ordProducts) ? array_column($ordProducts, 'prodsize_id') : [];

        $removeParam = $addParam = [];
        foreach ($products as $p) {
            $indx = array_search($p->prodsize_id, $ids);
            if (in_array($p->prodsize_id, $ordProductsSizes)) {
                $removeParam[] = $p->prodsize_id;
            }
            if ($qtys[$indx]) {
                $subtotal = $qtys[$indx] * $p->prodsize_wsp;
                $addParam[] = [
                    'ordprod_order' => $order,
                    'ordprod_product' => $p->product_id,
                    'ordprod_color' => $p->prodcolor_id,
                    'ordprod_size' => $p->prodsize_id,
                    'ordprod_price' => $p->prodsize_wsp,
                    'ordprod_request_qty' => $qtys[$indx],
                    'ordprod_served_qty' => $qtys[$indx],
                    'ordprod_subtotal' => $subtotal,
                    'ordprod_total' => $subtotal,
                ];
            }
        }
        $status = Ws_orders_product::addRemove($addParam, $order, $removeParam);
        if (!$status) {
            echo json_encode(['status' => false, 'message' => 'Error on adding products']);
            return;
        }
        $t = $this->updateOrderTotals($order);
        echo json_encode([
            'status' => true,
            'order' => Ws_order::fetch($order),
            'products' => Ws_orders_product::fetch(0, [['order_id', $order]]),
        ]);
    }

    function remove(Request $request)
    {
        $status = Ws_orders_product::remove($request->product);
        if ($status) {
            $t = $this->updateOrderTotals($request->order);
            echo json_encode([
                'status' => true,
                'order' => Ws_order::fetch($request->order),
                'products' => Ws_orders_product::fetch(0, [['order_id', $request->order]]),
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Error in removing the product!'
            ]);
        }
    }

    private function updateOrderTotals($id)
    {
        $ordSubtotal = $ordTotal = 0;
        $ordProducts  = Ws_orders_product::fetch(0, [['order_id', $id]]);
        foreach ($ordProducts as $product) {
            $ordSubtotal += $product->ordprod_subtotal;
            $ordTotal += $product->ordprod_total;
        }

        return Ws_order::updateOrder($id, [
            'order_subtotal' => $ordSubtotal,
            'order_total' => $ordTotal,
        ]);
    }

    function view($code)
    {
        $order = Ws_order::fetch(0, [['order_code', $code]])[0];
        $products = Ws_orders_product::fetch(0, [['ordprod_order', $order->order_id]]);

        return view('contents.orders.view', compact('order', 'products'));
    }

    function updateQty(Request $request)
    {

        if ($request->qty) {
            $product = Ws_orders_product::fetch($request->product);
            $subtotal = $request->qty * $product->prodsize_wsp;
            $status = Ws_orders_product::updateProduct($request->product, [
                'ordprod_price' => $product->prodsize_wsp,
                'ordprod_request_qty' => $request->qty,
                'ordprod_served_qty' => $request->qty,
                'ordprod_subtotal' => $subtotal,
                'ordprod_total' => $subtotal,
            ]);
        } else {
            $status = Ws_orders_product::remove($request->product);
        }

        if ($status) {
            $t = $this->updateOrderTotals($request->order);
            echo json_encode([
                'status' => true,
                'order' => Ws_order::fetch($request->order),
                'products' => Ws_orders_product::fetch(0, [['order_id', $request->order]]),
            ]);
        } else {
            echo json_encode([
                'status' => false,
                'message' => 'Error in removing the product!'
            ]);
        }
    }

    function updateStatus(Request $request)
    {
        $param = [
            'order_status' => $request->status,
            'order_note' => $request->note,
        ];
        if ($request->status == 2) $param['order_placed'] = Carbon::now();

        $result =  Ws_order::submit($request->order, $param);
        echo json_encode([
            'status'  => boolval($result),
            'data'    => $result ? Ws_order::fetch($request->order) : []
        ]);
    }
}
