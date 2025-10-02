<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\JwtToken;

class DriverController extends Controller
{
    private function checkToken(Request $request)
    {
        $token = JWTAuth::getToken();

        try {
            $user = JWTAuth::setToken($token)->authenticate();

            if (!$user) return false;

            // cek token di DB
            $exists = JwtToken::where('id_user', $user->id)
                        ->where('token', $token)
                        ->exists();

            if (!$exists) return false;

            return $user;

        } catch (\Exception $e) {
            return false;
        }
    }

    public function list_driver(Request $request)
    {
        $user = $this->checkToken($request);
        if (!$user) {
            return response()->json([
                'status' => false,
                'code'   => 401, // 401 lebih tepat untuk unauthorized
                'message'=> 'Token invalid atau silakan login dulu!'
            ], 401);
        }

        $drivers = User::where('role', 'driver')->get();
        return response()->json([
            'status' => true,
            'code'   => 200,
            'message' => 'success',
            'drivers'   => $drivers
        ]);
    }

    public function add_driver(Request $request)
    {
        $user = $this->checkToken($request);
        if (!$user) {
            return response()->json([
                'status' => false,
                'code'   => 401, // 401 lebih tepat untuk unauthorized
                'message'=> 'Token invalid atau silakan login dulu!'
            ], 401);
        }

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

        return response()->json([
            'status' => true,
            'code'   => 200,
            'message' => 'success',
            'user'   => $user
        ]);
    }

    public function update_driver(Request $request, $id)
    {
        $user = $this->checkToken($request);
        if (!$user) {
            return response()->json([
                'status' => false,
                'code'   => 401,
                'message'=> 'Token invalid atau silakan login dulu!'
            ], 401);
        }

        $driver = User::where('role', 'driver')->findOrFail($id);

        $data = $request->validate([
            'name'     => 'required|string|max:255',
        ]);

        // if (!empty($data['password'])) {
        //     $data['password'] = Hash::make($data['password']);
        // } else {
        //     unset($data['password']);
        // }

        $driver->update($data);

        return response()->json([
            'status' => true,
            'code'   => 200,
            'message'=> 'Driver berhasil diupdate!',
            'driver' => $driver
        ]);
    }

    public function delete_driver(Request $request, $id)
    {
        $user = $this->checkToken($request);
        if (!$user) {
            return response()->json([
                'status' => false,
                'code'   => 401,
                'message'=> 'Token invalid atau silakan login dulu!'
            ], 401);
        }

        $driver = User::where('role', 'driver')->findOrFail($id);
        $driver->delete();

        return response()->json([
            'status' => true,
            'code'   => 200,
            'message'=> 'Driver berhasil dihapus!'
        ]);
    }
}
