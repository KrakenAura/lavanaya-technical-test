<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => [
                'required',
                'email',
            ],
            'password' => [
                'required',
            ],
        ]);


        if (! Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }


        $user = User::where(
            'email',
            $request->email
        )->first();


        $token = $user->createToken(
            'api-token'
        )->plainTextToken;


        return response()->json([
            'message' => 'Login successful',
            'token' => $token,
            'user' => $user,
        ]);
    }


    public function logout(Request $request)
    {
        $request->user()
            ->currentAccessToken()
            ->delete();


        return response()->json([
            'message' => 'Logout successful',
        ]);
    }
}
