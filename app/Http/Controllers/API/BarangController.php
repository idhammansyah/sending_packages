<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\Barang;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\JwtToken;
use App\Http\Controllers\Controller;

class BarangController extends Controller
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

    public function list_barang(Request $request)
    {
        $user = $this->checkToken($request);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Silakan login dulu'
            ], 401);
        }

        $barangs = Barang::all();

        return response()->json([
            'status' => true,
            'code'   => 200,
            'message' => 'success',
            'user'   => $user,
            'barang'   => $barangs
        ]);
    }


    public function store(Request $request)
    {
        $user = $this->checkToken($request);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Silakan login dulu'
            ], 401);
        }

        $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori'    => 'required|string|max:255',
            'harga'       => 'required|numeric',
        ]);

        Barang::create($request->all());

        return response()->json([
            'status' => true,
            'code'   => 200,
            'message' => 'Berhasil memasukkan barang baru!'
        ], 200);
    }

    public function updateBarang(Request $request, $id)
    {
        $user = $this->checkToken($request);
        if (!$user) {
            return response()->json([
                'status' => false,
                'code'   => 401, // 401 lebih tepat untuk unauthorized
                'message'=> 'Token invalid atau silakan login dulu!'
            ], 401);
        }

        // 2️⃣ Validasi input
        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'kategori'    => 'required|string|max:255',
            'harga'       => 'required|numeric',
        ]);

        // 3️⃣ Cari barang, jika tidak ada otomatis 404
        $barang = Barang::findOrFail($id);

        // 4️⃣ Update data
        $barang->update($validated);

        // 5️⃣ Response sukses
        return response()->json([
            'status'  => true,
            'code'    => 200,
            'message' => 'Berhasil mengupdate barang!',
            'data'    => $barang
        ], 200);
    }

    public function destroy(Request $request, $id)
    {
        $user = $this->checkToken($request);
        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Silakan login dulu'
            ], 401);
        }

        $barang = Barang::findOrFail($id);
        $barang->delete();

        return response()->json([
            'status' => true,
            'code'  => 200,
            'message' => 'Berhasil menghapus barang!'
        ], 401);
    }
}
