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
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request)
    {
        $retailer =  $request->user();
        return view('profile.edit', [
            'user' => Retailer_address::fetch(0, [['retailer_id', $retailer->retailer_id]]),
            'countries' => Location::fetch(0, [['location_visible', 1]]),
            'currencies' => Currency::fetch(0, [['currency_visible', 1]]),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        return $request;
        $id    = $request->id;
        $email = $request->email;
        $phone = $request->phone;


        if(count(Retailer::fetch(0, [['retailer_id', '!=', $id], ['retailer_phone', $phone]])))
        {
            echo json_encode(['status' => false, 'message' => __('Phone number already exists'),]);
            return;
        }

        if($email &&  count(Retailer::fetch(0, [['retailer_id', '!=', $id], ['retailer_email', $email]])))
        {
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
            'retailer_password'     => Hash::make('0000')
        ];


        $logo = $request->file('logo');
        if($logo)
        {
            $logoName = $this->uniqidReal(rand(4, 18));
            $logo->move('images/retailers/', $logoName);
            $param['retailer_logo'] = $logoName;
        }

        if($id){
            $record = Retailer::fetch($id);
            if ($logo && $record->retailer_logo) {
                File::delete('images/retailers/' . $record->retailer_logo);
            }
        }

        $result = Retailer::submit($param, $id);

        if($result){
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
            Retailer_address::submit($paramAddress, null);
        };
        echo json_encode([
            'status' => boolval($result),
            'data'   => $result ? Retailer::fetch($result) : []
        ]);
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}