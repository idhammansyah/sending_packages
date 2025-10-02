<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DriverController extends Controller
{
       public function v_User()
   {
        if (!session('jwt_token')) {
        return redirect()->route('login')->withErrors(['auth' => 'Silakan login dulu']);
        }

        $user = session('user');
        $driver = User::where('role', 'driver')->get();

        return view('driver.index', compact('user', 'driver'));
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
