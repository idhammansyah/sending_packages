<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;

class BarangController extends Controller
{
   public function v_barang()
   {
        if (!session('jwt_token')) {
            return redirect()->route('login')->withErrors(['auth' => 'Silakan login dulu']);
        }

        // ambil user dari session
        $user = session('user');
        $barangs = Barang::all();

        return view('barang.index', compact('user', 'barangs'));
   }

    public function store(Request $request)
    {
        Barang::create($request->all());
        return redirect()->back()->with('success', 'Barang berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $barang = Barang::findOrFail($id);
        $barang->update($request->all());
        return redirect()->back()->with('success', 'Barang berhasil diupdate');
    }

    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();
        return redirect()->back()->with('success', 'Barang berhasil dihapus');
    }
}
