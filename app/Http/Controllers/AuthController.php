<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Repositories\AuthRepository;

class AuthController extends Controller
{
    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    /**
     * Registering a new user.
     */
    public function register(RegisterRequest $request)
    {
        // using form request for validation (RegisterRequest)
        $user = $this->authRepository->register($request);
        $this->authRepository->assignRole($user);
        // $token = $user->createToken('auth_token')->plainTextToken;
        //uncomment above line to generate token while registering
        return response()->json([
            'message' => 'success',
            'description' => 'user successfully registered.',
        ]);
    }
    /**
     * Logging in a user.
     */
    public function login(LoginRequest $request)
    {
        // using form request for validation (LoginRequest)
        $token = $this->authRepository->login($request);
        return response()->json([
            'message' => 'success',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
    /**
     * Logging out the user.
     */
    public function logout()
    {
        $this->authRepository->logout();
        return response()->json([
            'message' => 'success',
            'description' => 'user logged out successfully.',
        ]);
    }
    
}
