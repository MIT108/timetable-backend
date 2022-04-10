<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    public function listUsers(){
        $users = User::where('id' ,'<>', auth()->user()->id)->get();
        $response = [$users];
        return response($response, 200);
    }
}
