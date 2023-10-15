<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AuthRepository
{
    public function register($request){
        // registering new user 
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);
        return $user;
    }

    public function assignRole($user){
        $role = Role::where('name','user')->first();
        //assigning role
        $user->assignRole($role);
    }

    public function login($request){
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['message' => 'Invalid login details'], 401);
        }

        $user = User::where('email', $request['email'])->firstOrFail();
        // creating new token
        $token = $user->createToken('auth_token')->plainTextToken;
        return $token;

    }

    public function logout(){
        $user = user();
        $user->tokens()->delete();
        // deleting all bearer tokens linked with this user.
        // $user()->currentAccessToken()->delete();
        // if we want to delete just latest token
        
    }
}