<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    //
    public function test(){
        $response = [
            'data' => "test ok"
        ];
        return response($response, 200);
    }
    
    public function register(Request $request){
         $fields= $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);

        $token = $user->createToken('authenticationToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response($response, 200);
    }

    public function logout(Request $request){

        if (auth()->user()->tokens()->delete()) {
            $response = [
                'message' => 'logout successful'
            ];
            return response($response, 200);
        }else {
            $response = [
                'message' => 'logout error'
            ];
            return response($response, 422);

        }
    }


    public function login(Request $request){
        $fields= $request->validate([
           'email' => 'required|string',
           'password' => 'required|string'
       ]);

       //check email
       $user = User::where('email', $fields['email'])->first();

       //check password
       if(!$user || !Hash::check($fields['password'], $user->password)){
           return response([
               'message' =>'Bad credentials'
           ], 422);
       }

       $token = $user->createToken('authenticationToken')->plainTextToken;

       $response = [
           'user' => $user,
           'token' => $token
       ];

       return response($response, 201);
   }
}
