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
            'data' => $user,
            'token' => $token,
            'message' => 'registration successful'
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
                'message' =>'Bad credentials',
                'error' =>'Bad credentials'
           ], 200);
       }

       $token = $user->createToken('authenticationToken')->plainTextToken;

       $response = [
           'data' => $user,
           'token' => $token,
           'message' => 'login successful'
       ];

       return response($response, 200);
   }

   public function changePassword(Request $request){
       $fields = $request->validate([
           'old_password' => 'required|string',
           'new_password' => 'required|string'
       ]);

       $user_id =  auth()->user()["id"];
       $fields += ['user_id' => $user_id];

       $user = User::find($fields['user_id']);

       $response = [];

       if (Hash::check($fields['old_password'], $user->password)){
           try {
               $user->password = bcrypt($fields['new_password']);
               $user->save();

               $response = [
                   'data' => $user,
                   'message' => 'Password changed successfully'
               ];
           } catch (\Throwable $th) {
               $response = [
                   'error' => $th->getMessage,
                   'message' => 'could not change password'
               ];
           }

       }else{
           $response = [
               'error' => 'the old password is invalid',
               'message' => 'the old password is invalid'
           ];
       }

       return response($response, 200);
   }
}
