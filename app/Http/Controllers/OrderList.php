<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use App\Models\Transaction;
// use App\

class OrderList extends Controller
{
    public function index()
    {
        $user = session('user');

        if ($user['role'] === 'admin') {
            $orders = Transaction::all();
        } else {
            $orders = Transaction::where('id_user', $user['id'])->get();
        }

        $barang = Barang::all();

        return view('order.index', compact('orders', 'barang', 'user'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'id_barang' => 'required|array',
            'id_barang.*' => 'integer|exists:tbl_barang,id',
        ]);

        $user = session('user');
        $total = 0;

        foreach ($request->id_barang as $idBarang) {
            $barang = Barang::findOrFail($idBarang);
            $total += $barang->harga;
        }

        Transaction::create([
            'id_driver'       => null,
            'id_barang'       => implode(',', $request->id_barang),
            'status_transact' => 'pending',
            'status_payment'  => 'siap dibayar',
            'total_pembayaran'=> $total,
            'created_at'      => now(),
            'created_by'      => $user['id'],
            'is_deleted'      => 0
        ]);

        return redirect()->route('order')->with('success', 'Pesanan berhasil ditambahkan!');
    }

    // payment
    public function payment(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer|exists:tbl_transaction,id',
        ]);

        $order = Transaction::findOrFail($request->order_id);
        $order->update([
            'status_payment' => 'sudah dibayar',
            'status_transaction' => 'menginfokan driver',
            'updated_at' => now(),
        ]);

        return redirect()->route('order')->with('success', 'Pembayaran berhasil!');
    }

}
