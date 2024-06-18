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

        $id    = $request->retailer_id;
        $email = $request->email;
        $phone = $request->phone;

        // return $request;
        if(count(Retailer::where('retailer_phone', $phone)->get()))
        {
            echo json_encode(['status' => false, 'message' => __('Phone number already exists'),]);
            return;
        }

        if($email &&  count(Retailer::where('retailer_email', $email)->get()))
        {
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
        if($result)
        {
            $message = "New Retailer Registered:\n";
            $message .= "Name: " . $request->name . "\n";
            $message .= "Phone: " . $request->phone;

            $data = [
                'id' => $result,
            ];

            $this->telegramService->sendMessage($message, Http::put("https://dash.sofieamoura.com/retailers/edit_approved", $data));
        }
        echo json_encode([
            'status' => boolval($request),
            'data'   => $result ? Retailer::fetch($result) : []
        ]);
    }
}