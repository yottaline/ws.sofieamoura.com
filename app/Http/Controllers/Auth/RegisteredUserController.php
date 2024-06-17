<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Retailer;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
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
            // 'password' => ['required',],
        ]);

        $id    = $request->retailer_id;
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
            'retailer_code'         => uniqidReal(8),
            'retailer_fullName'     => $request->name,
            'retailer_email'        => $email,
            'retailer_phone'        => $phone,
            'retailer_password'     => Hash::make('0000'),
            'retailer_company'      => $request->company,
            'retailer_country'      => $request->country,
            'retailer_province'     => $request->province,
            'retailer_city'         => $request->city,
            'retailer_address'      => $request->address,
            'retailer_currency'     => 1,
            'retailer_created'      => Carbon::now()
        ];

        $result = Retailer::submit($param, $id);
        if ($result) {
            $message = "New Retailer Registered:\n"
                . "Name: " . $request->name . "\n"
                . "Company: " . $request->company . "\n"
                . "Email: " . $request->email . "\n"
                . "Phone: " . $request->phone . "\n"
                . "Country: " . $request->country . "\n"
                . "City: " . $request->city;

            $this->telegramService->sendMessage($message, url("https://dash.sofieamoura.com//retailers/edit_approved/{$result}"));
        }
        echo json_encode([
            'status' => boolval($request),
            'data'   => $result ? Retailer::fetch($result) : []
        ]);
    }
}
