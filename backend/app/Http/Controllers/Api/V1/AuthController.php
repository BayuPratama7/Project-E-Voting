<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\User;
use App\Pemilih;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * Admin login
     */
    public function adminLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $credentials = $request->only('username', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            
            if (!$user->isActive()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Account is inactive'
                ], 401);
            }

            $token = $user->createToken('AdminAuth')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'username' => $user->username,
                        'role' => $user->role,
                    ],
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials'
        ], 401);
    }

    /**
     * Pemilih login
     */
    public function pemilihLogin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nim' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $pemilih = Pemilih::where('nim', $request->nim)->first();

        if ($pemilih && Hash::check($request->password, $pemilih->password)) {
            if ($pemilih->status !== 'active') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Account is inactive'
                ], 401);
            }

            $token = $pemilih->createToken('PemilihAuth')->plainTextToken;

            return response()->json([
                'status' => 'success',
                'message' => 'Login successful',
                'data' => [
                    'pemilih' => [
                        'id' => $pemilih->id,
                        'nim' => $pemilih->nim,
                        'nama' => $pemilih->nama,
                        'email' => $pemilih->email,
                        'kelas' => $pemilih->kelas,
                        'semester' => $pemilih->semester,
                        'has_voted' => $pemilih->has_voted,
                    ],
                    'token' => $token,
                    'token_type' => 'Bearer'
                ]
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials'
        ], 401);
    }

    /**
     * Logout user (both admin and pemilih)
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Logged out successfully'
        ], 200);
    }

    /**
     * Get authenticated user data
     */
    public function me(Request $request)
    {
        $user = $request->user();
        
        if ($user instanceof User) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'user_type' => 'admin',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'username' => $user->username,
                        'role' => $user->role,
                    ]
                ]
            ], 200);
        } elseif ($user instanceof Pemilih) {
            return response()->json([
                'status' => 'success',
                'data' => [
                    'user_type' => 'pemilih',
                    'pemilih' => [
                        'id' => $user->id,
                        'nim' => $user->nim,
                        'nama' => $user->nama,
                        'email' => $user->email,
                        'kelas' => $user->kelas,
                        'semester' => $user->semester,
                        'has_voted' => $user->has_voted,
                    ]
                ]
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'User not found'
        ], 404);
    }

    /**
     * Check authentication status
     */
    public function check(Request $request)
    {
        if ($request->user()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Authenticated',
                'authenticated' => true
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Not authenticated',
            'authenticated' => false
        ], 401);
    }
}
