<?php

namespace App\Http\Controllers\Zaions\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Zaions\User\UserDataResource;
use App\Models\Default\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Http\Requests\LoginRequest;
use Laravel\Fortify\Rules\Password;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'username' => [
                'required', 'string', 'max:255',
                Rule::unique(User::class),
            ],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => ['required', 'string', new Password, 'confirmed'],
        ]);
        $user = User::create([
            'unique_id' => uniqid(),
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $token = $user->createToken('auth');

        return response()->json([
            'success' => true,
            'errors' => [],
            'data' => [
                'user' => new UserDataResource($user),
                'token' => $token
            ],
            'message' => 'Request Completed Successfully!',
            'status' => 201
        ], 201);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {
                $token = $user->createToken('auth');

                return response()->json([
                    'success' => true,
                    'errors' => [],
                    'data' => [
                        'user' => new UserDataResource($user),
                        'token' => $token
                    ],
                    'message' => 'Request Completed Successfully!',
                    'status' => 201
                ], 201);
            } else {
                return response()->json([
                    'success' => false,
                    'errors' => [
                        'password' => ['Invalid Password']
                    ],
                    'data' => [],
                    'message' => 'Request Failed.',
                    'status' => 400
                ], 400);
            }
        } else {
            return response()->json([
                'success' => false,
                'errors' => [
                    'email' => ['No User found with this email.']
                ],
                'data' => [],
                'message' => 'Request Failed.',
                'status' => 400
            ], 400);
        }
    }

    public function logout(Request $request)
    {
        $user = User::where('id', $request->user()->id)->first();
        if ($user) {
            $user->tokens()->delete();

            return response()->json([
                'success' => true,
                'errors' => [],
                'data' => [],
                'message' => 'Request Completed Successfully!',
                'status' => 200
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'errors' => [],
                'data' => [],
                'message' => 'Request failed.',
                'status' => 400
            ], 400);
        }
    }

    public function verifyAuthState(Request $request)
    {
        return response()->json(['data' => true]);
    }
}
