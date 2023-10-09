<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function createAccount(Request $request)
    {

        $attr = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6'
        ]);

        $user = User::create([
            'name' => $attr['name'],
            'password' => bcrypt($attr['password']),
            'email' => $attr['email']
        ]);

        return response()->json([
            'status' => true,
            'message' => 'User created successfully',
            'token' => $user->createToken('tokens')->plainTextToken
        ], 200);

    }

    public function signin(Request $request)
    {
        $attr = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:6'
        ]);

        if(!Auth::attempt($attr)){
            return $this->error('Credentials does not match', 401);
        }

        return response()->json([
            'token' => auth()->user()->createToken('API Token')->plainTextToken
        ]);
    }

    public function signout()
    {
        auth()->user()->tokens()->delete();

        return [
            'message' => 'Tokens Revoked'
        ];
    }
}

