<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Builder;
class Season extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'season_name',
        'season_code',
        'season_current',
        'season_adv_payment',
        'season_adv_context',
        'season_delivery_1',
        'season_delivery_2',
        'season_start',
        'season_end',
        'season_lookbook',
        'season_visible'
    ];

    public static function fetch($id = 0, $params = null, $limit = null, $lastId = null)
    {
        $seasons = self::orderBy('season_id', 'DESC')->limit($limit);

        if (isset($params['q'])) {
            $seasons->where(function (Builder $query) use ($params) {
                $query->where('season_code', 'like', '%' . $params['q'] . '%')
                ->orWhere('season_code', $params['q'])
                ->orWhere('season_start', $params['q'])
                ->orWhere('season_end', $params['q'])
                ->orWhere('season_adv_payment', $params['q']);
            });

            unset($params['q']);
        }

        if($lastId) $seasons->where('season_id', '<', $lastId);

        if($params) $seasons->where($params);

        if($id) $seasons->where('season_id', $id);

        return $id ? $seasons->first() : $seasons->get();
    }


    public function getSeasonStartAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getSeasonEndAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }
}