<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request  $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:50',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|regex:/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,20}$/',
        ]);


        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->messages(),
            ], 422);
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        $token = $user->createToken($request->username);

        return response()->json([
            'message' => 'User successfully created',
            'user' => $user,
            'token' => $token->plainTextToken
        ], 201);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->messages(),
            ], 422);
        }

        $login = $request->input('login');
        $password = $request->input('password');

        $user = User::where('email', $login)->orWhere('username', $login)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return response()->json([
                'errors' => [
                    'login' => ['The provided credentials are incorrect.']
                ]
            ], 401);
        }

        $token = $user->createToken($user->username);

        return response()->json([
            'message' => 'User successfully connected',
            'user' => $user,
            'token' => $token->plainTextToken
        ], 200);
    }

    public function logout(Request  $request)
    {
        $request->user()->tokens()->delete();

        return [
            'message' => 'You are logged out.' 
        ];
    }
}
