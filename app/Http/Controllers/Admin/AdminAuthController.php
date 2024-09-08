<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showloginform()
    {
        return inertia('Admin.Auth.login');
    }

    public function login(Request $request)
    {
       if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
           return redirect()->route('admin.index');
       };
       return redirect()->route('admin.login')->with('error','Invalid Credentials');
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        return redirect()->route('admin.login');
    }
}
