<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Size extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'size_name',
        'size_order',
        'size_visible'
    ];

    public static function fetch($id = 0, $params = null, $limit = null, $lastId = null)
    {
        $sizes = self::orderBy('size_order', 'ASC')->limit($limit);

        if (isset($params['q'])) {
            $sizes->where(function (Builder $query) use ($params) {
                $query->where('size_order', 'like', '%' . $params['q'] . '%')
                    ->orWhere('size_name', $params['q']);
            });

            unset($params['q']);
        }

        if($lastId) $sizes->where('size_id', '<', $lastId);

        if($params) $sizes->where($params);

        if($id) $sizes->where('size_id', $id);

        return $id ? $sizes->first() : $sizes->get();
    }


}