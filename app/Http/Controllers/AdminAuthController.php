<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminAuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin_logged_in')) return redirect()->route('dashboard');
        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required',
        ]);

        $user = User::where('username', $request->username)
                    ->where('role', 'admin')
                    ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['username' => 'Invalid username or password.']);
        }

        session([
            'admin_logged_in' => true,
            'admin_id'        => $user->user_id,
            'admin_name'      => $user->first_name . ' ' . $user->last_name,
            'admin_email'     => $user->email,
            'admin_role'      => $user->role,
            'admin_picture'   => $user->profile_picture,
        ]);

        return redirect()->route('dashboard');
    }

    public function showRegister()
    {
        return view('admin.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name'  => 'required|string|max:100',
            'email'      => 'required|email|unique:users,email',
            'username'   => 'required|string|unique:users,username',
            'password'   => 'required|min:8|confirmed',
        ]);

        User::create([
            'name'                  => $request->first_name . ' ' . $request->last_name,
            'first_name'            => $request->first_name,
            'last_name'             => $request->last_name,
            'email'                 => $request->email,
            'username'              => $request->username,
            'password'              => Hash::make($request->password),
            'password_hash'         => Hash::make($request->password),
            'confirm_password_hash' => Hash::make($request->password),
            'role'                  => 'admin',
        ]);

        return redirect()->route('admin.login')
                         ->with('success', 'Admin account created! Please login.');
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('admin.login');
    }
}
