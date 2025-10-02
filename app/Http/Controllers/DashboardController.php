<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // cek apakah ada session jwt_token
        if (!session('jwt_token')) {
            return redirect()->route('login')->withErrors(['auth' => 'Silakan login dulu']);
        }

        // ambil user dari session
        $user = session('user');

        return view('dashboard.index', compact('user'));
    }
}
