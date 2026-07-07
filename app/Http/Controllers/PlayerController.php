<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Assessment;
use Illuminate\Http\Request;

class PlayerController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role','!=','admin');
        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('first_name','like','%'.$request->search.'%')
                  ->orWhere('last_name','like','%'.$request->search.'%')
                  ->orWhere('email','like','%'.$request->search.'%');
            });
        }
        if ($request->role) $query->where('role', $request->role);
        $players = $query->latest()->paginate(15);
        return view('admin.players', compact('players'));
    }

    public function show($id)
    {
        $player = User::findOrFail($id);
        $assessments = Assessment::where('user_id', $id)
                        ->with('mission.module.strand')
                        ->latest('taken_at')->get();
        return view('admin.player-detail', compact('player','assessments'));
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return back()->with('success','Player deleted successfully.');
    }
}
