<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request) 
    {
        $fields = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed',
            'role' => 'required|in:admin,editor,viewer',
        ]);

        //$user = User::create($fields);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);
    
        $token = $user->createToken('auth_token')->plainTextToken;
        //$token = $user->createToken($request->name);

 /*       return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];*/
        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);

    }

    public function login(Request $request) 
    {
        $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return [
                'message' => 'Le credenziali di accesso sono errate.'
            ];
        }
        $token = $user->createToken($user->name);

        return [
            'user' => $user,
            'token' => $token->plainTextToken
        ];        
    }

    public function logout(Request $request) 
    {
       $request->user()->tokens()->delete();
       
        return [
            'message' => 'Ti sei disconnesso/a.'
        ];
    }
}
