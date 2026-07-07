<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use App\Models\Module;
use Illuminate\Http\Request;

class MissionPageController extends Controller
{
    public function index()
    {
        $missions = Mission::with('module.strand')->latest()->get();
        $modules  = Module::with('strand')->get();
        return view('admin.missions', compact('missions','modules'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'module_id'            => 'required|exists:modules,module_id',
            'mission_title'        => 'required|string',
            'scenario_description' => 'required|string',
            'max_score'            => 'required|integer|min:1',
            'difficulty_level'     => 'required|integer|between:1,3',
        ]);
        Mission::create($request->only('module_id','mission_title','scenario_description','max_score','difficulty_level'));
        return back()->with('success','Mission created successfully.');
    }

    public function update(Request $request, $id)
    {
        Mission::findOrFail($id)->update($request->only('module_id','mission_title','scenario_description','max_score','difficulty_level'));
        return back()->with('success','Mission updated.');
    }

    public function destroy($id)
    {
        Mission::findOrFail($id)->delete();
        return back()->with('success','Mission deleted.');
    }
}
