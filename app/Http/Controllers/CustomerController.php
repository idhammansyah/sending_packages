<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class CustomerController extends Controller
{
    public function v_customer()
   {
        if (!session('jwt_token')) {
            return redirect()->route('login')->withErrors(['auth' => 'Silakan login dulu']);
        }

        // ambil user dari session
        $user = session('user');
        $customer = User::where('role', 'customer')->get();

        return view('customer.index', compact('user', 'customer'));
   }

    public function store(Request $request)
    {
        User::create($request->all());
        return redirect()->back()->with('success', 'User berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $User = User::findOrFail($id);
        $User->update($request->all());
        return redirect()->back()->with('success', 'User berhasil diupdate');
    }

    public function destroy($id)
    {
        $User = User::findOrFail($id);
        $User->delete();
        return redirect()->back()->with('success', 'User berhasil dihapus');
    }
}
