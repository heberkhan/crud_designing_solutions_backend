<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\AuthRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;

class AuthController extends Controller
{

    public function register(AuthRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email'=> $request->email,
            'password'=> Hash::make($request->password)
        ]);

        return response()->json([
            'status' => true,
            'message' => "User register successfully",
            'token' => $user->createToken('authToken')->accessToken
        ],201);
    }

    public function login(LoginRequest $request)
    {
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json([
                'status' => false,
                'errors' => ['Unauthorized']
            ],401);
        }

        $user = User::where('email', $request->email)->first();

        return response()->json([
            'status' => true,
            'message' => "User login successfully",
            'data' => $user,
            'token' => $user->createToken('authToken')->accessToken
        ],200);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => true,
            'message' => "User logout successfully",
        ],200);
    }
}
