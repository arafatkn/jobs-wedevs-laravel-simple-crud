<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Resources\UserResource;

class AuthController extends Controller
{
    protected $data=[];

    function __construct()
    {
        //parent::__construct();
    }

    public function login(Request $req)
    {
    	$req->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if( !auth()->attempt($req->only(['email','password'])) )
            return response()->json([
                'message'=> 'Email or Password is incorrect'
            ], 401);

        $user = auth()->user();
        $token = $user->createToken($req->get('device_id', time().rand(0,999)))->plainTextToken;

        return response()->json([
            'message'=>'You have been logged in successfully...',
            'user'=>new UserResource($user),
            'token'=>$token
        ]);

    }

    public function register(Request $req)
    {
        $req->validate([
            'email' => 'required|email|unique:users',
            'name' => 'required|max:256',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = new User();
        $user->name = $req->name;
        $user->email = $req->email;
        $user->password = bcrypt($req->password);

        if($user->save()) {
            $token = $user->createToken($req->get('device_id', time().rand(0,999)))->plainTextToken;
            return response()->json([
                'message'=>'You have been registerred successfully...',
                'user'=>new UserResource($user),
                'token'=>$token
            ]);
        }

        return response()->json(['message'=>'Something is wrong. Please try again.'], 501);
    }

    public function logout(Request $req)
    {
        try {
            auth()->user()->currentAccessToken()->delete();
            return response()->json(['message'=>'You have been logged out successfully.']);
        } catch(\Exception $e) {
            return response()->json([
                'message'=>'Unable to log out', 
                'error'=> $e->getMessage()
            ], 500);
        }  
    }

}
