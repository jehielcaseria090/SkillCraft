<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LoginSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    // POST /api/auth/register
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name'     => 'required|string|max:255',
            'last_name'      => 'required|string|max:255',
            'email'          => 'required|email|unique:users,email',
            'username'       => 'required|string|unique:users,username',
            'password'       => 'required|string|min:8|confirmed',
            'role'           => 'in:admin,teacher,student',
            'contact_number' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors'  => $validator->errors()
            ], 422);
        }

        $hashedPassword = Hash::make($request->password);

        $user = User::create([
            'first_name'            => $request->first_name,
            'last_name'             => $request->last_name,
            'name'                  => $request->first_name . ' ' . $request->last_name,
            'email'                 => $request->email,
            'username'              => $request->username,
            'password'              => $hashedPassword,
            'password_hash'         => $hashedPassword,
            'confirm_password_hash' => $hashedPassword,
            'role'                  => $request->role ?? 'student',
            'contact_number'        => $request->contact_number,
        ]);

        $token = $user->createToken('skillcraft_unity')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully.',
            'data'    => ['user' => $this->formatUser($user), 'token' => $token],
        ], 201);
    }

    // POST /api/auth/login
    // Unity sends: { "username": "...", "password": "..." }
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Username and password are required.',
            ], 422);
        }

        $user = User::where('username', $request->username)->first();

        $passwordValid = $user && (
            Hash::check($request->password, $user->password) ||
            Hash::check($request->password, $user->password_hash)
        );

        if (!$passwordValid) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid username or password.',
            ], 401);
        }

        // Log login session
        LoginSession::create([
            'user_id'       => $user->user_id,
            'email'         => $user->email,
            'password_hash' => $user->password_hash ?? $user->password,
            'login_at'      => now(),
            'ip_address'    => $request->ip(),
            'user_agent'    => $request->userAgent(),
        ]);

        $user->tokens()->where('name', 'skillcraft_unity')->delete();
        $token = $user->createToken('skillcraft_unity')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful.',
            'data'    => ['user' => $this->formatUser($user), 'token' => $token],
        ]);
    }

    // POST /api/auth/logout
    public function logout(Request $request)
    {
        $session = LoginSession::where('user_id', $request->user()->user_id)
                               ->whereNull('logout_at')
                               ->latest('login_at')
                               ->first();
        if ($session) {
            $session->update(['logout_at' => now()]);
        }

        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully.'
        ]);
    }

    // GET /api/auth/me
    public function me(Request $request)
    {
        return response()->json([
            'success' => true,
            'data'    => $this->formatUser($request->user()),
        ]);
    }

    // FIXED: All null values replaced with empty strings
    // JsonUtility in Unity CANNOT parse null — it crashes with parse error
    private function formatUser(User $user): array
    {
        return [
            'user_id'         => $user->user_id,
            'first_name'      => $user->first_name   ?? '',
            'last_name'       => $user->last_name    ?? '',
            'email'           => $user->email        ?? '',
            'username'        => $user->username     ?? '',
            'role'            => $user->role         ?? '',
            'profile_picture' => $user->profile_picture ?? '', // FIXED: was null
            'contact_number'  => $user->contact_number  ?? '', // FIXED: was null
            'created_at'      => $user->created_at
                                 ? $user->created_at->toDateTimeString()
                                 : '',                          // FIXED: simplified format
        ];
    }
}
