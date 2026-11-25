<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

use App\Mail\WelcomeMail;
use Illuminate\Http\Request;

class UserController extends Controller
{
  public function register(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);
      $users= User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        Mail::to($users->email)->send(new WelcomeMail($users));
           return response()->json(['message' => 'User registered successfully', 'User' => $users], 201);
    }
        public function login(Request $request){
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
      
        if(!Auth::attempt($request->only('email', 'password'))){
            return response()->json(['message' => 'Invalid email or password'], 401);
        }
          $user = User::where('email', $request->email)->first();
          $token=$user->createToken('auth_token')->plainTextToken;
            return response()->json(['message' => 'User logged in successfully','user' => $user, 'access_token' => $token], 201);
    }
}
