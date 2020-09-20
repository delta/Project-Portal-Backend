<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;


class AuthController extends Controller
{
    public function register(Request $request)
    {

        $data = $request->validate([
            'name' => 'required|max:255',
            'email' => 'email|required|unique:users,email',
            'password' => 'required|confirmed',
            'roll_number' => 'required|digits:9|unique:users,roll_number',
            'github_handle' => 'max:255'
        ]);

        $user = new User;
        $user->name = $data->name;
        $user->email = $data->email;
        $user->passord = bcrypt($data->password);
        $user->roll_number = $data->roll_number;
        $user->github_handle = $data->github_handle;

        \DB::transaction(function () use ($user) {
            $user->save();
        });

        if ($user->exists) {
            return response()->json([
                'message' => 'Registration Successful'
            ], 200);
        } else {
            return response()->json([
                'message' => 'User could not be created'
            ], 503);
        }
    }

    public function login(Request $request)
    {

        $loginData = $request->validate([
            'email' => 'email|required',
            'password' => 'required'
        ]);

        if (!auth()->attempt($loginData)) {
            return response()->json(['message' => 'Invalid Credentials'], 401);
        }

        $accessToken = auth()->user()->createToken('authToken')->accessToken;

        return response(['message' => 'Login Successful', 'user' => auth()->user(), 'access_token' => $accessToken]);
    }
}
