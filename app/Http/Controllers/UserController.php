<?php

namespace App\Http\Controllers;

use http\Env\Response;
use App\Models\BlackList;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Subscription;

class UserController extends Controller
{

public function getUser(Request $request, $userid)
    {
        $user = User::all()->firstWhere('id',$userid);

        if (!$user) {
            $response = [
                'message' => 'User not found'
            ];
            return response()->json($response, 404);
        }

        return response()->json($user);
    }

    public function myprofile(Request $request){
    return $request->user();
    }

    public function profile_edit(Request $request){
    $user =$request->user();
    $request->validate([
        'email' => 'email|unique:users',
            'login' => 'unique:users',
            'password' => '',
            'current_password' => 'required_with:email,login,password'
    ]);
    $current_password = $request->input('current_password');
    if(!empty($current_password) and !password_verify($current_password,$user->password)){
        $response = ['message'=> 'Wrong current password'
        ];
        return response()->json($response,400);
    }
    else{
        $user -> update($request->all());
        $user ->save();
        $response= [
            'message' => 'Profile edit successfully',
            'data' => $user
        ];
        return response()->json($response,200);
    }
}








}
