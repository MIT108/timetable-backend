<?php

namespace App\Http\Controllers;

use App\Mail\MyMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    //
    public function create(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|string',
            'role_id' => 'required|integer'
        ]);

        $password = Str::random(8);

        while ($this->checkPassword($password)) {
            $password = Str::random(8);
        }

        $fields += ['password' => bcrypt($password)];


        $details = [
            'title' => 'Password for timetable application',
            'body' => $password
        ];

        $response = [];

        if ($this->checkEmail($fields['email'])) {

            try {

                Mail::to($fields['email'])->send(new MyMail($details));

                try {
                    $user = User::create($fields);
                    $response = [
                        "data" => $user,
                        "message" => "user created successfully"
                    ];
                } catch (\Throwable $th) {
                    $response = [
                        'error' => $th->getMessage(),
                        'message' => "could not create the account"
                    ];
                }
            } catch (\Throwable $th) {
                $response = [
                    'error' => $th->getMessage(),
                    'message' => "could not send email"
                ];
            }
        } else {
            $response = [
                'error' => "this email already exists",
                'message' => "this email already exists",
            ];
        }

        return response($response, 200);
    }

    public function checkPassword($password)
    {
        if (User::where('password', '=', bcrypt($password))->count() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function checkEmail($email)
    {
        if (User::where('email', '=', $email)->count() > 0) {
            return false;
        } else {
            return true;
        }
    }
    public function listUsers()
    {
        $users = User::where('id', '<>', auth()->user()->id)->get();
        $response = [$users];
        return response($response, 200);
    }

    public function deleteUser(Request $request){
        if ($request->route('id') == 1) {
            $response = [
                'error' => 'cant delete the administrator account',
                'message' => 'cant delete the administrator account',
            ];
        }else{
            if(User::where('id', '=', $request->route('id'))->delete()) {
                $response = [
                    'message' => 'User deleted successfully'
                ];
            }else {
                $response = [
                    'message' => 'User not found',
                    'error' => 'User not found'
                ];
            }

        }

        return response($response, 200);
    }
}
