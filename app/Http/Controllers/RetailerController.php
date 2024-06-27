<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Retailer;
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
        if(!$retailer) return back()->with('message', 'You not have account');
        
          $token =  uniqidReal(20);
          DB::table('password_reset_tokens')->insert([
              'email' => $request->retailer_email,
              'token' => $token,
              'created_at' => Carbon::now()
            ]);

          Mail::send('email.forgetPassword', ['token' => $token], function($message) use($request){
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
      public function showResetPasswordForm($token) {
    //    $retailer = DB::table('password_reset_tokens')->where('token', $token);
         return view('auth.reset-password', ['token' => $token]);
      }
}