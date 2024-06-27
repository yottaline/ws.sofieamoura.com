<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Display the password reset link request view.
     */
    function create(): View
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle an incoming password reset link request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    function store(Request $request): RedirectResponse
    {
        $request->validate([
            'retailer_email' => ['required', 'email'],
        ]);

        $status = Password::broker('retailers')->sendResetLink(
            ['retailer_email' => $request->retailer_email]
        );

        return $status == Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withInput($request->only('retailer_email'))
            ->withErrors(['retailer_email' => __($status)]);
    }
}
