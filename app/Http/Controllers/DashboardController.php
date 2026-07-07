<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mission;
use App\Models\Strand;
use App\Models\Module;
use App\Models\Assessment;
use App\Models\LoginSession;
use App\Models\AcceptabilitySurvey;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalPlayers'   => User::where('role','!=','admin')->count(),
            'onlinePlayers'  => User::where('role','!=','admin')->where('updated_at','>=',now()->subMinutes(15))->count(),
            'totalMissions'  => Mission::count(),
            'totalStrands'   => Strand::count(),
            'totalModules'   => Module::count(),
            'recentLogins'   => LoginSession::with('user')->latest('login_at')->limit(8)->get(),
            'strands'        => Strand::withCount('modules')->get(),
            'missions'       => Mission::with('module.strand')->latest()->limit(10)->get(),
            'recentUsers'    => User::where('role','!=','admin')->latest()->limit(6)->get(),
            'totalSurveys'   => AcceptabilitySurvey::count(),
        ]);
    }
}
