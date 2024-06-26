<?php

namespace App\Http\Controllers;

use App\Models\Retailer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Retailer_address;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;


class RetailerController extends Controller
{

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function showForgetPasswordForm()
    {
        return view('auth.forgetPassword');
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function submitForgetPasswordForm(Request $request)
    {
        $retailer = Retailer::where('retailer_email', $request->retailer_email)->first();
        if (!$retailer) return back()->with('message', 'You not have account');

        $token =  uniqidReal(20);
        DB::table('password_reset_tokens')->insert([
            'email' => $request->retailer_email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('email.forgetPassword', ['token' => $token], function ($message) use ($request) {
            $message->to($request->retailer_email);
            $message->subject('Reset Password');
        });

        return back()->with('message', 'We have e-mailed your password reset link!');
    }
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function showResetPasswordForm($token)
    {
        //    $retailer = DB::table('password_reset_tokens')->where('token', $token);
        return view('auth.reset-password', ['token' => $token]);
    }

    function request(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email'],
        ]);


        $email = $request->email;
        $phone = $request->phone ?? '';

        if (($email &&  count(Retailer::where('retailer_email', $email)->get())) || count(Retailer::where('retailer_phone', $phone)->get())) {
            return redirect('https://sofieamoura.com/request_sent.php');
        }

        $param = [
            'retailer_code'         => uniqidReal(8),
            'retailer_fullName'     => $request->name . ',' . $request->first_name,
            'retailer_email'        => $email,
            'retailer_phone'        => $phone,
            'retailer_password'     => Hash::make('0000'),
            'retailer_company'      => $request->company,
            'retailer_store'        => $request->store,
            'retailer_vat'          => $request->vat,
            'retailer_country'      => $request->country,
            'retailer_province'     => '',
            'retailer_city'         => $request->city,
            'retailer_zip'          => '',
            'retailer_website'      => $request->website,
            'retailer_billAddress'  => $request->billing,
            'retailer_shipAddress'  => $request->delivery,
            'retailer_currency'     => 1,
            'retailer_created'      => Carbon::now()
        ];

        $result = Retailer::submit($param);
        $addresses = [[
            'address_retailer'  => $result,
            'address_type'      => 1,
            'address_country'   => $request->country,
            'address_province'  => '',
            'address_city'      => $request->city,
            'address_zip'       => '',
            'address_line1'     => $request->billing,
            'address_line2'     => '',
            'address_phone'     => $phone,
            'address_note'      => '',
        ], [
            'address_retailer'  => $result,
            'address_type'    => 2,
            'address_country' => $request->country,
            'address_province' => '',
            'address_city'    => $request->city,
            'address_zip'     => '',
            'address_line1'   => $request->delivery,
            'address_line2'   => '',
            'address_phone'   => $phone,
            'address_note'    => '',
        ]];
        Retailer_address::insert($addresses);
        $message = "Nouveau détaillant enregistré:\n"
            . "Nom: {$request->name}\n"
            . "Prénom: {$request->first_name}\n"
            . "Email: {$request->email}\n"
            . "Téléphone: {$request->phone}\n"
            . "Dénomination sociale: {$request->company}\n"
            . "Nom de la boutique: {$request->store}\n"
            . "Adresse de facturation: {$request->billing}\n"
            . "Adresse de livraison: {$request->delivery}\n"
            . "TVA: {$request->vat}\n"
            . "Site web: {$request->website}";

        Http::post("https://api.telegram.org/bot7222495229:AAEJqA6pUj9xZIQDQ7AgsN_9O3rMNkk4dfg/sendMessage", [
            'chat_id' => '-4232852781',
            'text' => $message,
            'reply_markup' => json_encode([
                'inline_keyboard' => [[
                    [
                        'text' => 'Accepter la requête',
                        'url' =>  "https://dash.sofieamoura.com/retailers/edit_approved?id={$result}",
                    ]
                ]]
            ])
        ]);

        return redirect('https://sofieamoura.com/request_sent.php');
    }
}
