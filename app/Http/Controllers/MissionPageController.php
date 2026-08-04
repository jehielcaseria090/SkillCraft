<?php

namespace App\Http\Controllers;

use App\Models\Mission;
use App\Models\Module;
use Illuminate\Http\Request;

class MissionPageController extends Controller
{
    public function index(Request $request)
    {
        $scopedStrand = $request->attributes->get('cms_scoped_strand');

        $query = Mission::with('module.strand');

        if ($scopedStrand) {
            $query->whereHas('module.strand', fn($q) => $q->where('strand_name', $scopedStrand));
        }

        $missions = $query->get();
        $modules = $scopedStrand
            ? Module::whereHas('strand', fn($q) => $q->where('strand_name', $scopedStrand))->with('strand')->get()
            : Module::with('strand')->get();

        return view('admin.missions', compact('missions', 'modules'));
    }

    public function store(Request $request)
    {
        $scopedStrand = $request->attributes->get('cms_scoped_strand');

        $request->validate([
            'module_id'            => 'required|exists:modules,module_id',
            'mission_title'        => 'required|string',
            'scenario_description' => 'required|string',
            'max_score'            => 'required|integer',
            'difficulty_level'     => 'required|integer',
        ]);

        if ($scopedStrand) {
            $module = Module::with('strand')->find($request->module_id);
            if (!$module || ($module->strand->strand_name ?? null) !== $scopedStrand) {
                abort(403, 'You can only add missions to your own specialization.');
            }
        }

        Mission::create($request->only(
            'module_id', 'mission_title', 'scenario_description', 'max_score', 'difficulty_level'
        ));

        return back()->with('success', 'Mission created.');
    }

    public function update(Request $request, $id)
    {
        $scopedStrand = $request->attributes->get('cms_scoped_strand');

        if ($scopedStrand) {
            $mission = Mission::with('module.strand')->findOrFail($id);
            if (($mission->module->strand->strand_name ?? null) !== $scopedStrand) {
                abort(403, 'You can only edit missions in your own specialization.');
            }

            if ($request->filled('module_id')) {
                $newModule = Module::with('strand')->find($request->module_id);
                if (!$newModule || ($newModule->strand->strand_name ?? null) !== $scopedStrand) {
                    abort(403, 'You cannot move a mission outside your specialization.');
                }
            }
        }

        Mission::findOrFail($id)->update($request->only(
            'module_id', 'mission_title', 'scenario_description', 'max_score', 'difficulty_level'
        ));

        return back()->with('success', 'Mission updated.');
    }

    public function destroy(Request $request, $id)
    {
        $scopedStrand = $request->attributes->get('cms_scoped_strand');

        $mission = Mission::with('module.strand')->findOrFail($id);

        if ($scopedStrand && ($mission->module->strand->strand_name ?? null) !== $scopedStrand) {
            abort(403, 'You can only delete missions in your own specialization.');
        }

        $mission->delete();

        return back()->with('success', 'Mission deleted.');
    }
}
