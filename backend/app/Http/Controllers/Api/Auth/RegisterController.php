<?php

namespace App\Http\Controllers\Api\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:20',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|max:20|same:confirmation_password',
            'confirmation_password' => 'required|min:8|max:20'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status' => 403,
                $validator->errors(),
            ]);
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'role' => 'user',
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('my_social_media')->plainTextToken;

            return response()->json([
                'status' => 201,
                'data' => $user,
                'token' => $token,
            ], 201);
        }

    }
}