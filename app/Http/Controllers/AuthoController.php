<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuthoController extends Controller
{
public function register(Request $request)
    {
        $request->validate([
            'email' => 'email|unique:users',
            'login' => 'required|unique:users',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
        ]);

        $reg = $request->all();
        $reg['password'] = bcrypt($reg['password']);
        $user = new User($reg);
        $user->api_token= Str::random(20);
        $user->save();

        $response = [
            'message' => 'Successful registration',
            'data'    => ['Token' => $user->api_token],
        ];
        return response()->json($response, 201);
    }

    public function auth(Request $request){
        $request->validate([
        'login' => 'required',
        'password'=>'required',
        ]);
        $data = $request->only('login','password');
        if (!Auth::attempt($data)){
            $response =[
                'message'=>'Wrong login or password'
            ];
            return response()->json($response,400);
        }
        $user = Auth::user();
        $response = [
            'message' => 'Successful authorization',
            'data'=> ['token'=> $user->api_token],
        ];
        return response()->json($response,201);
    }

}
