<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Retailer;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Services\TelegramService;

class RegisteredUserController extends Controller
{
    protected $telegramService;

    public function __construct(TelegramService $telegramService)
    {
        $this->telegramService = $telegramService;
    }
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email'],
        ]);

        $email = $request->email;
        $phone = $request->phone ?? '';

        if (count(Retailer::where('retailer_phone', $phone)->get())) {
            echo json_encode(['status' => false, 'message' => __('This phone number is already registered'),]);
            return;
        }


        if ($email &&  count(Retailer::where('retailer_email', $email)->get())) {
            echo json_encode(['status' => false, 'message' => __('This email address is already registered'),]);
            return;
        }

        $param = [
            'retailer_code'         => uniqidReal(8),
            'retailer_fullName'     => $request->name,
            'retailer_email'        => $email,
            'retailer_phone'        => $phone,
            'retailer_password'     => Hash::make('0000'),
            'retailer_company'      => $request->company,
            'retailer_country'      => $request->country,
            'retailer_province'     => $request->province ?? '',
            'retailer_city'         => $request->city,
            'retailer_zip'         => $request->zip ?? '',
            'retailer_address'      => $request->address ?? '',
            'retailer_currency'     => 1,
            'retailer_created'      => Carbon::now()
        ];

        $result = Retailer::submit($param);
        $message = "New Retailer Registered:\n"
            . "Name: {$request->name}\n"
            . "Company: {$request->company}\n"
            . "Email: {$request->email}\n"
            . "Phone: {$request->phone}\n"
            . "Country: {$request->country}\n"
            . "City: {$request->city}";

        Http::post("https://api.telegram.org/bot7222495229:AAEJqA6pUj9xZIQDQ7AgsN_9O3rMNkk4dfg/sendMessage", [
            'chat_id' => '-4232852781',
            'text' => $message,
            'reply_markup' => json_encode([
                'inline_keyboard' => [[
                    [
                        'text' => 'Approved Request',
                        'url' =>  "https://dash.sofieamoura.com/retailers/edit_approved?id={$result}",
                    ]
                ]]
            ])
        ]);

        echo json_encode([
            'status' => boolval($request),
            'data'   => $result ? Retailer::fetch($result) : []
        ]);
    }
}
