<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Models\{User};
use App\Resources\UserResource;

class UserController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    public function profile()
    {
        $user = auth()->user();
        return new UserResource($user);
    }

    public function profileUpdate(Request $req)
    {
        $req->validate([
            'name' => 'bail|required',
            'email' => 'bail|nullable|email',
        ]);

        $user = auth()->user();
        $user->fill( $req->only(['name','email']) );

        if($user->save())
            return response()->json([
                'message'=>'Your profile has been updated successfully',
                'user'=> new UserResource($user)
            ]);
        else
            return response()->json(['message'=>"Profile can not be updated"], 501);
    }

    public function passwordUpdate(Request $req)
    {
        $req->validate([
            'current_password' => 'bail|required',
            'new_password' => 'bail|required|min:6|confirmed',
        ]);

        $user = auth()->user();
        $hasher = app('hash');
        if($hasher->check($req->current_password, $user->password))
        {
            $user->password = bcrypt($req->new_password);

            try {
                $user->save();
                return response()->json(['message'=>'Your password has been updated successfully']);
            } catch(Exception $e) {
                return response()->json(['message'=>"Password can not be updated"], 501);
            }
        }
        else
        {
            return response()->json(['message'=>"Incorrent current password"], 403);
        }
    }

}
