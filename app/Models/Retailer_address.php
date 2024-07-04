<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Exception;

class Retailer_address extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'address_retailer',
        'address_type',
        'address_country',
        'address_province',
        'address_city',
        'address_zip',
        'address_line1',
        'address_line2',
        'address_phone',
        'address_note'
    ];

    static function fetch($id = 0, $params = null)
    {
        $retailer_addresses = self::join('retailers', 'address_retailer', 'retailer_id');

        if ($params) $retailer_addresses->where($params);
        if ($id) $retailer_addresses->where('address_id', $id);

        return $id ? $retailer_addresses->first() : $retailer_addresses->get();
    }

    static function submit($param, $id = null)
    {
        if ($id) {
            try {
                DB::beginTransaction();
                self::where('address_id', $id)->update($param);
                DB::commit();
                return true;
            } catch (Exception $e) {
                DB::rollBack();
                // throw $e;
                return false;
            }
        }

        $status = self::create($param);
        return $status ? $status->address_id : false;
    }
}
