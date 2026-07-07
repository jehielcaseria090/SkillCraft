<?php

namespace App\Http\Controllers;

use App\Models\LoginSession;

class LoginLogController extends Controller
{
    public function index()
    {
        $logs = LoginSession::with('user')->latest('login_at')->paginate(20);
        return view('admin.login-logs', compact('logs'));
    }
}
