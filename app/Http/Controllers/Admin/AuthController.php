<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Yoeunes\Toastr\Facades\Toastr;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function show(){
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);
      
        if (Auth::guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();
            Toastr::success(translate('messages.welcome back!'));
            return redirect()->intended('/');
        }
        Toastr::error(translate('messages.The provided credentials do not match our records'));
        return back()->onlyInput('email');

    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}
