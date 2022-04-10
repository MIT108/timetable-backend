<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Auth\Events\Verified;

class EmailVerificationController extends Controller
{
    //
    public function sendVerificationEmail(Request $request){
        // dd($request);
        if($request->user()->hasVerifiedEmail()){

            $response = [
                'status' => true
            ];

            return response($response, 200);
        }

        $request->user()->sendEmailVerificationNotification();

        $response = [
            'status' => false
        ];

        return response($response, 200);
    }

    public function verify(EmailVerificationRequest $request){
        if ($request->user()->hasVerifiedEmail()) {

            $response = [
                'message' => 'Email already verified'
            ];

            return response($response, 422);
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        $user = User::where('id' ,'=', auth()->user()->id)->get();
        $response = [
            'status' => true,
            'user' => $user
        ];

        return response($response, 200);
    }
}
