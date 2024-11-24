<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginUserRequest;
use App\Http\Requests\Auth\StoreUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    //
    public function registration(StoreUserRequest $request)
    {
        User::create($request->all());
        return response()->json([
            "success" => true,
            "message" => "User created successfully",
        ], 201);
    }
    public function login(LoginUserRequest $request)
    {
        $attempt = Auth::attempt($request->only('email', 'password'));
        if (!$attempt) {
            return response()->json([
                "success" => false,
                "message" => "Email or password is incorrect"
            ], 401);
        }

        $user = Auth::user();

        if ($user->is_librarian){
            $token = $user->createToken("$user->name's librarian token", ["librarian"])->plainTextToken;
        }
        else{
            $token = $user->createToken("$user->name's basic token", ["student"])->plainTextToken;
        }

        return response()->json([
            "success" => true,
            "message" => "User logged in successfully",
            "token" => $token,
        ]);
    }
    public function logout()
    {
        Auth::user()->currentAccessToken()->delete();
        return response()->json([
            "success" => true,
            "message" => "User logged out successfully"
        ]);
    }
}
