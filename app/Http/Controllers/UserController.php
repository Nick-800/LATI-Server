<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function register(Request $request)
    {
        $input = $request->validate([
            'name' => ['string', 'required'],
            'phone' => ['required', 'string', 'unique:users,phone', 'min:9', 'max:10'],
            'gender' => ['required', 'in:male,female'],
            'DOB' => ['required', 'date'],
            'password' => ['required', 'string'],
            'avatar_url' => ['nullable']
        ]);
        User::create([
            'name' => $input['name'],
            'phone' => $input['phone'],
            'gender' => $input['gender'],
            'DOB' => $input['DOB'],
            'avatar_url' => $input['avatar_url'] ?? null,
            'password' => Hash::make($input['password'])
        ]);

        return response()->json([
            'data' => 'user registered successfully'
        ]);
    }

    public function login(Request $request)
    {
        $credentials = request(['phone', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

    public function logout()
    {
        auth('api')->logout(true);

        return response()->json(['data' => 'Successfully logged out']);

    }

    public function user()
    {
        $user = auth()->user();

        return response()->json([
            'data' => $user
        ]);
    }

    public function refresh()
    {
        $token = auth()->refresh();

        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);

    }
}


