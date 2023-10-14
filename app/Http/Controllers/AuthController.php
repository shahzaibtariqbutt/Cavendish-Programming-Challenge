<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use App\Repositories\AuthRepository;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $authRepository;

    public function __construct(AuthRepository $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authRepository->register($request);
        $this->authRepository->assignRole($user);
        // $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            'message' => 'success',
            'description' => 'user successfully registered.',
        ]);
    }

    public function login(LoginRequest $request)
    {
        $token = $this->authRepository->login($request);
        return response()->json([
            'message' => 'success',
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    public function logout()
    {
        $this->authRepository->logout();
        return response()->json([
            'message' => 'success',
            'description' => 'user logged out successfully.',
        ]);
    }
    
}
