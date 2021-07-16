<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    //Register API
    public function register(Request $request)
    {
        //validation
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'phone_no' => 'required',
            'password' => 'required|confirmed',
        ]);

        //create user data +save
        $user = new User();

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_no = $request->phone_no;
        $user->password = bcrypt($request->password);
        $user->save();
        //save response

        return response()->json([
            'status' => 1,
            'message' => 'user registered successfully!'
        ], 200);
    }

    //Login API
    public function login(Request $request)
    {
        //validation 
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);
        //verify user + token
        if (!$token = auth()->attempt([
            'email' => $request->email,
            'password' => $request->password
        ])) {

            return response()->json([
                'status' => 0,
                'message' => 'invalid credentials'
            ]);
        }

        //send response
        return response()->json([
            'status' => 1,
            'message' => 'login successfully',
            'access_token' => $token
        ]);
    }

    //Profile API
    public function profile()
    {
        $user_data = auth()->user();

        return response()->json([
            'status' => 1,
            'message' => 'user profile data',
            'data' => $user_data
        ]);
    }

    //Logout API
    public function logout()
    {
        auth()->logout();

        return response()->json([
            'status' => 1,
            'message' => 'user logged out'
        ]);
    }
}
