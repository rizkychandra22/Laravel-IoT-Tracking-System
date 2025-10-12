<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        return view('login');
    }
    
    function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ],[
            'username.required' => 'Username harus diisi',
            'password.required' => 'Password harus diisi'
        ]);

        $datalogin = $request->only('username', 'password');

        if (Auth::attempt($datalogin)) {
            if (Auth::user()->role == 'Admin') {
            return redirect()->route('indexAdmin');
            } elseif (Auth::user()->role == 'User') {
                return redirect()->route('indexUser');
            } 
        }
        return redirect()->back()->withErrors([
            'loginError' => 'Username atau Password yang dimasukan tidak sesuai'
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }
}
