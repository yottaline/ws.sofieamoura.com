<?php

namespace App\Http\Controllers;

use App\Models\Retailer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class RetailerController extends Controller
{
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

        // if (count(Retailer::where('retailer_phone', $phone)->get())) {
        //     echo json_encode(['status' => false, 'message' => __('Ce numéro de téléphone est déjà enregistré'),]);
        //     return;
        // }


        // if ($email &&  count(Retailer::where('retailer_email', $email)->get())) {
        //     echo json_encode(['status' => false, 'message' => __('cette adresse email est déjà enregistrée'),]);
        //     return;
        // }

        $param = [
            'retailer_code'         => uniqidReal(8),
            'retailer_fullName'     => $request->name . ',' . $request->first_name,
            'retailer_email'        => $email,
            'retailer_phone'        => $phone,
            'retailer_password'     => Hash::make('0000'),
            'retailer_company'      => $request->company,
            'retailer_country'      => $request->country,
            'retailer_province'     => '',
            'retailer_city'         => '',
            'retailer_zip'          => '',
            'retailer_address'      => '',
            'retailer_currency'     => 1,
            'retailer_created'      => Carbon::now()
        ];

        $result = Retailer::submit($param);
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

        // echo json_encode([
        //     'status' => boolval($request),
        //     'data'   => $result ? Retailer::fetch($result) : []
        // ]);
    }
}
