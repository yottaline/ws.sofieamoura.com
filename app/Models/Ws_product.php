<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class Ws_product extends Model
{
    use HasFactory;
    protected $primaryKey = 'product_id';
    public $timestamps = false;

    protected $fillable = [];

    static function fetch($id = 0, $params = null, $limit = 24, $offset = 0, $ids = 0)
    {
        $ws_products = self::join('seasons', 'product_season', 'season_id')
            ->join('categories', 'product_category', 'category_id')
            ->leftJoin('ws_products_colors', 'product_id', 'prodcolor_product')
            ->leftJoin('products_media', 'prodcolor_media', 'media_id')
            ->orderBy('prodcolor_order', 'ASC')
            ->orderBy('product_id', 'ASC')
            ->limit($limit)->offset($offset)->groupBy('product_id');

        if (isset($params['q'])) {
            $ws_products->where(function (Builder $query) use ($params) {
                $query->where('product_code', $params['q'])
                    ->orWhere('product_ref', $params['q'])
                    ->orWhere('product_desc', 'like', "%{$params['q']}%")
                    ->orWhere('product_name', 'like', "%{$params['q']}%");
                // ->orWhere('season_name', $params['q'])
                // ->orWhere('category_name', $params['q']);
            });
            unset($params['q']);
        }

        if ($params) $ws_products->where($params);
        if ($id) $ws_products->where('product_id', $id);
        if ($ids) $ws_products->whereIn('product_id', $ids);

        return ($id || $limit == 1) ? $ws_products->first() : $ws_products->get();
    }
}
