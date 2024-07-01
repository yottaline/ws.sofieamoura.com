<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ws_orders_product extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'ordprod_order',
        'ordprod_product',
        'ordprod_size',
        'ordprod_request_qty',
        'ordprod_served_qty',
        'ordprod_total',
        'ordprod_price',
        'ordprod_subtotal',
        'ordprod_discount',
    ];

    static function fetch($id = 0, $params = null, $sizes = null)
    {
        $products = self::join('ws_orders', 'ordprod_order', 'order_id')
            ->join('ws_products', 'ordprod_product', 'product_id')
            ->join('ws_products_sizes', 'ordprod_size', 'prodsize_id')
            ->join('sizes', 'prodsize_size', 'size_id')
            ->join('ws_products_colors', 'prodcolor_ref', 'prodsize_color')
            ->join('products_media', 'prodcolor_media', 'media_id');

        if ($params) $products->where($params);
        if ($id) $products->where('ordprod_id', $id);
        if ($sizes) $products->whereIn('prodsize_id', $sizes);

        return $id ? $products->first() : $products->get();
    }

    static function addRemove($addParam, $order = null, $removeParam = null)
    {
        try {
            DB::beginTransaction();
            if ($order && $removeParam)
                self::whereIn('ordprod_size', $removeParam)->where('ordprod_order', $order)->delete();
            self::insert($addParam);
            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    static function remove($id)
    {
        return self::where('ordprod_id', $id)->delete();
    }

    static function updateProduct($id, $param)
    {
        return self::where('ordprod_id', $id)->update($param);
    }
}
