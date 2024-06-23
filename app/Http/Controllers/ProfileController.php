<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Location;
use App\Models\Retailer;
use App\Models\Retailer_address;
use App\Models\Currency;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Services\TelegramService;

class ProfileController extends Controller
{

    protected $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }
    // function __construct()
    // {
    //     $this->middleware('auth');
    // }

    function index()
    {
        $retailer = Retailer::fetch(auth()->user()->retailer_id);
        return view('profile.index', compact('retailer'));
    }

    function update(Request $request)
    {
        return $request;
        $id    = $request->id;
        $email = $request->email;
        $phone = $request->phone;


        if (count(Retailer::fetch(0, [['retailer_id', '!=', $id], ['retailer_phone', $phone]]))) {
            echo json_encode(['status' => false, 'message' => __('Phone number already exists'),]);
            return;
        }

        if ($email &&  count(Retailer::fetch(0, [['retailer_id', '!=', $id], ['retailer_email', $email]]))) {
            echo json_encode(['status' => false, 'message' => __('Email already exists'),]);
            return;
        }

        $param = [
            'retailer_fullName'     => $request->name,
            'retailer_email'        => $email,
            'retailer_phone'        => $phone,
            'retailer_company'      => $request->company,
            'retailer_desc'         => $request?->desc,
            'retailer_website'      => $request?->website,
            'retailer_country'      => $request->country,
            'retailer_province'     => $request->province,
            'retailer_city'         => $request->city,
            'retailer_address'      => $request?->address,
            'retailer_currency'     => $request->currency,
            'retailer_adv_payment'  => $request?->payment,
            'retailer_modified'     => Carbon::now(),
            'retailer_password'     => Hash::make($request->password ?? '0000'),
        ];

        $logo = $request->file('logo');
        if ($logo) {
            $logoName = $this->uniqidReal(rand(4, 18));
            $logo->move('images/retailers/', $logoName);
            $param['retailer_logo'] = $logoName;
        }

        if ($id) {
            $record = Retailer::fetch($id);
            if ($logo && $record->retailer_logo) {
                File::delete('images/retailers/' . $record->retailer_logo);
            }
        }

        $result = Retailer::submit($param, $id);

        if ($result) {
            $paramAddress = [
                'address_retailer'  => $result,
                'address_country'   => $request->currency,
                'address_province'  => $request->province,
                'address_city'      => $request->city,
                'address_zip'       => $request->zip,
                'address_line1'     => $request->address,
                'address_line2'     => $request->address,
                'address_phone'     => $phone,
                'address_note'      => $request->address,
            ];
            Retailer_address::submit($paramAddress);
        };
        echo json_encode([
            'status' => boolval($result),
            'data'   => $result ? Retailer::fetch($result) : []
        ]);
    }

}