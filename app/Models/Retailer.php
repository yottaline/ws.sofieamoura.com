<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Retailer extends Authenticatable
{
    use HasFactory, HasFactory, Notifiable;

    public $timestamps = false;
    public $primaryKey = 'retailer_id';

    protected $email = 'retailer_email';
    protected $fillable = [
        'retailer_code',
        'retailer_fullName',
        'retailer_email',
        'retailer_password',
        'retailer_phone',
        'retailer_company',
        'retailer_logo',
        'retailer_desc',
        'retailer_website',
        'retailer_country',
        'retailer_province',
        'retailer_city',
        'retailer_zip',
        'retailer_address',
        'retailer_shipAdd',
        'retailer_billAdd',
        'retailer_currency',
        'retailer_adv_payment',
        'retailer_approved',
        'retailer_approved_by',
        'retailer_blocked',
        'retailer_modified',
        'retailer_created'
    ];

    static function fetch($id = 0, $params = null)
    {
        $retailers = self::join('locations', 'retailer_country', 'location_id')
            ->join('currencies', 'retailer_currency', 'currency_id');

        if ($params) $retailers->where($params);
        if ($id) $retailers->where('retailer_id', $id);

        return $retailers->first();
    }

    static function submit($param, $id = null)
    {
        if ($id) return self::where('retailer_id', $id)->update($param) ? $id : false;
        $status = self::create($param);
        return $status ? $status->id : false;
    }
}
