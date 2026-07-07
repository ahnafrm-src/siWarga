<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    //
    public function login(Request $request) {
        $login = Auth::attempt(['email' => $request->email, 'password' => $request->password]);
        if(!$login){
            return response()->json([
                'success' => false,
                'message' => "Gagal Login",
                'data' => null,
            ]);
        };
        
        $token =  Auth::user()->createToken('login')->plainTextToken;

        return response()->json([
                'success' => true,
                'message' => "Berhasil Login",
                'data' => $token,
            ]);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => "Berhasil Logout",
            'data' => null,
        ]);
    }
}
