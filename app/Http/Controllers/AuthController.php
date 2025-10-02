<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\User;
use App\Models\JwtToken;

class AuthController extends Controller
{
  public function v_login()
  {
    $data = [
      'title' => 'Login Page'
    ];
    return view('auth.login', compact('data'));
  }

  public function v_register()
  {
    $data = [
      'title' => 'Register Page'
    ];
    return view('auth.register', compact('data'));
  }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'role'     => 'required',
        ]);

        $user = User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'role'     => $data['role'] ?? 'admin',
        ]);

        $token = JWTAuth::fromUser($user);

        $create = JwtToken::create([
            'id_user'     => $user->id,
            'token'       => $token,
            'place_added' => $request->header('User-Agent'),
            'updated_at' => now(),
        ]);

        // redirect ke dashboard dengan flash message
        return redirect()->route('login')->with('success', 'Registrasi berhasil');
    }

  public function login(Request $request)
  {
      $credentials = $request->only('email', 'password');

      if (! $token = JWTAuth::attempt($credentials)) {
          return redirect()->back()
              ->withErrors(['login' => 'Email atau password salah'])
              ->withInput();
      }

      $user = auth()->user();

      // cek token di DB
      $existingToken = JwtToken::where('id_user', $user->id)->first();

      if ($existingToken) {
          $token = $existingToken->token;
      } else {
          JwtToken::create([
              'id_user'     => $user->id,
              'token'       => $token,
              'place_added' => $request->header('User-Agent'),
          ]);
      }

      // set session untuk web
      session([
          'jwt_token' => $token,
          'user'      => $user
      ]);

      return redirect()->route('dashboard')->with('success', 'Login berhasil, selamat datang ' . $user->name);
  }

 public function logout(Request $request)
 {
    $user = $request->session()->get('user');
    $token = $request->session()->get('jwt_token');

    if ($user && $token) {
        // hapus token dari DB
        JwtToken::where('id_user', $user->id)
        ->where('token', $token)
        ->delete();
    }

    // hapus session
    $request->session()->forget(['jwt_token', 'user']);
    $request->session()->flush();

    return redirect()->route('login')->with('success', 'Logout berhasil!');
 }


}
