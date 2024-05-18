<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:8|max:20',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 403,
                $validator->errors(),
            ]);
        }

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password, 'role' => 'user'])) {
            $user = Auth::user();
            if ($user->tokens()) {
                $user->tokens()->delete();
            }
            $token = $user->createToken('my_social_media')->plainTextToken;

            return response()->json([
                'status' => '200',
                'token' => $token,
                'data' => $user,
            ], 200);
        } else {
            return response()->json([
                'status' => 403,
                $validator->errors(),
                'email' => 'Email or password incorrect!',
            ]);
        }
    }

    public function logout(Request $request)
    {
        $user = $request->user();
        if($user->tokens()){
            $user->tokens()->delete();
        }
        return response()->json([
            'status' => 200,
            'message' => 'logout success'
        ]);
    }
}