<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products_media extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [];

    static function fetch($id = 0, $params = null)
    {
        $product_medias = self::join('ws_products', 'media_product', 'product_id')
            ->join('ws_products_colors', 'media_color', 'prodcolor_ref')
            ->orderBy('media_order');

        if ($params) $product_medias->where($params);
        if ($id) $product_medias->where('media_id', $id);

        return $id ? $product_medias->first() : $product_medias->get();
    }
}
