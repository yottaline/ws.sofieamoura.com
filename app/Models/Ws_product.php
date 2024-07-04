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
        $products = self::join('seasons', 'product_season', 'season_id')
            ->join('categories', 'product_category', 'category_id')
            ->join('ws_products_colors', 'product_id', 'prodcolor_product')
            ->join('ws_products_sizes', 'product_id', 'prodsize_product')
            ->join('products_media', 'prodcolor_media', 'media_id')
            ->orderBy('prodcolor_order', 'ASC')
            ->orderBy('product_id', 'ASC')
            ->where('prodcolor_published', '1')
            ->limit($limit)->offset($offset)->groupBy('prodcolor_slug');

        if (isset($params['q'])) {
            $products->where(function (Builder $query) use ($params) {
                $query->where('product_code', $params['q'])
                    ->orWhere('product_ref', $params['q'])
                    ->orWhere('product_desc', 'like', "%{$params['q']}%")
                    ->orWhere('product_name', 'like', "%{$params['q']}%");
                // ->orWhere('season_name', $params['q'])
                // ->orWhere('category_name', $params['q']);
            });
            unset($params['q']);
        }

        if ($params) $products->where($params);
        if ($id) $products->where('product_id', $id);
        if ($ids) $products->whereIn('product_id', $ids);

        return ($id || $limit == 1) ? $products->first() : $products->get();
    }
}
