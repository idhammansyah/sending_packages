<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use App\Models\JwtToken;

class AuthController extends Controller
{
    // REGISTER
    public function register(Request $request){
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'],
        ]);

        $token = JWTAuth::fromUser($user);

        $jwtToken = JwtToken::create([
            'id_user' => $user->id,
            'token' => $token,
            'created_at' => now(),
            'place_added' => $_SERVER['HTTP_USER_AGENT'],
            'updated_at' => now(),
        ]);

        return response()->json([
            'code'  => 200,
            'message' => 'success create user',
            'user'  => $user,
            'token' => $token,
            'jwtToken' => $jwtToken
        ]);
    }

    public function login(Request $request){
        $credentials = $request->only('email', 'password');

        if (! $token = JWTAuth::attempt($credentials)) {
            return response()->json([
                'status' => false,
                'message' => 'Email atau password salah'
            ], 401);
        }

        // Ambil user aktif
        $user = auth()->user();

        // Cek apakah user sudah punya token di DB
        $existingToken = JwtToken::where('id_user', $user->id)->first();

        if ($existingToken) {
            $token = $existingToken->token; // pakai token lama
        } else {
            JwtToken::create([
                'id_user'     => $user->id,
                'token'       => $token,
                'place_added' => $request->header('User-Agent'),
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'Login berhasil',
            'code'    => 200,
            'token'   => $token,
            'user'    => $user
        ]);
    }


   public function logout(Request $request){
        try {
            $token = JWTAuth::getToken();

            if (!$token) {
                return response()->json([
                    'status' => false,
                    'message' => 'Token tidak ditemukan'
                ], 400);
            }

            $user = JWTAuth::authenticate($token);

            // cek di DB apakah token ini memang terdaftar
            $exists = JwtToken::where('id_user', $user->id)
                    ->where('token', $token)
                    ->exists();

            if (!$exists) {
                return response()->json([
                    'status' => false,
                    'message' => 'Token tidak valid / tidak terdaftar'
                ], 401);
            }

            // blacklist token (invalidate)
            JWTAuth::invalidate($token);

            // hapus token dari DB juga biar clean
            JwtToken::where('id_user', $user->id)
                ->where('token', $token)
                ->delete();

            return response()->json([
                'status' => true,
                'message' => 'Logout berhasil',
                'code' => 200
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal logout: ' . $e->getMessage(),
                'code' => 500
            ], 500);
        }
    }


}
