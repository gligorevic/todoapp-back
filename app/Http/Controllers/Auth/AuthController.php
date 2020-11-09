<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;

class AuthController extends Controller
{
    public function login() {
        $credentials = request(['email', 'password']);

        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return response()->json(['token' => $token, 'user' => auth()->user()]);
    }

    public function logout()
    {
        try {
            auth()->logout();
            return response()->json(['message' => 'Successfully logged out']);
        } catch (TokenExpiredException $e) {
            return response()->json(["error" => $e->getMessage()], 401);
        }
    }

    public function refresh()
    {
        try {
            return response()->json(auth()->refresh());
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => $e->getMessage()], 401);
        }

    }

    public function register(Request $request)
    {
        $request->validate([
        'email' => 'required|unique:users|email|max:255',
        'name' => 'required',
        'password' => 'required'
        ]);

        $user = new User;
        $user->name = $request->get("name");
        $user->email = $request->get("email");
        $user->password = Hash::make($request->get("password"));
        $user->save();

        $token = auth()->login($user);
        return response()->json(['token' => $token, 'user' => $user]);
    }
}
