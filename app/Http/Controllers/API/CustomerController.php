<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\JwtToken;

class CustomerController extends Controller
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

    public function list_customer(Request $request)
    {
        $user = $this->checkToken($request);
        if (!$user) {
            return response()->json([
                'status' => false,
                'code'   => 401,
                'message'=> 'Token invalid atau silakan login dulu!'
            ], 401);
        }

        $customers = User::where('role', 'customer')->get();
        return response()->json([
            'status' => true,
            'code'   => 200,
            'message'=> 'success',
            'customers'   => $customers
        ]);
    }

    public function add_customer(Request $request)
    {
        $user = $this->checkToken($request);
        if (!$user) {
            return response()->json([
                'status' => false,
                'code'   => 401,
                'message'=> 'Token invalid atau silakan login dulu!'
            ], 401);
        }

        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $customer = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => 'customer', // role fix customer
        ]);

        return response()->json([
            'status' => true,
            'code'   => 200,
            'message' => 'success',
            'customer'   => $customer
        ]);
    }

    public function update_customer(Request $request, $id)
    {
        $user = $this->checkToken($request);
        if (!$user) {
            return response()->json([
                'status' => false,
                'code'   => 401,
                'message'=> 'Token invalid atau silakan login dulu!'
            ], 401);
        }

        $customer = User::where('role', 'customer')->findOrFail($id);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            // kalau mau bisa update password tinggal tambahin validasi disini
        ]);

        $customer->update($data);

        return response()->json([
            'status' => true,
            'code'   => 200,
            'message'=> 'Customer berhasil diupdate!',
            'customer' => $customer
        ]);
    }

    public function delete_customer(Request $request, $id)
    {
        $user = $this->checkToken($request);
        if (!$user) {
            return response()->json([
                'status' => false,
                'code'   => 401,
                'message'=> 'Token invalid atau silakan login dulu!'
            ], 401);
        }

        $customer = User::where('role', 'customer')->findOrFail($id);
        $customer->delete();

        return response()->json([
            'status' => true,
            'code'   => 200,
            'message'=> 'Customer berhasil dihapus!'
        ]);
    }
}
