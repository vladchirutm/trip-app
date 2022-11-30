<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    use HttpResponses;

    public function login(LoginUserRequest $request): \Illuminate\Http\JsonResponse
    {
        $request->validated($request->only(['email', 'password']));

        if (RateLimiter::remaining($request->ip(), 5)) {
            if(!Auth::attempt($request->only(['email', 'password']))) {
                RateLimiter::hit($request->ip());
                return $this->error('', 'Credentials do not match', Response::HTTP_UNAUTHORIZED);
            }
        }
        else{
            return $this->error('', 'Too many attempts', Response::HTTP_TOO_MANY_REQUESTS);
        }

        RateLimiter::clear($request->ip());

        $user = User::where('email', $request->get('email'))->first();

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

    public function register(StoreUserRequest $request): \Illuminate\Http\JsonResponse
    {
        $request->validated($request->only(['name', 'email', 'password']));

        $user = User::create([
            'name' => $request->get('name'),
            'email' => $request->get('email'),
            'password' => Hash::make($request->get('password')),
        ]);

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token')->plainTextToken
        ]);
    }

    public function logout(): \Illuminate\Http\JsonResponse
    {
        Auth::user()->currentAccessToken()->delete();

        return $this->success([
            'message' => 'You have successfully been logged out and your token has been removed'
        ]);
    }
}
