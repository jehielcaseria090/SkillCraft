<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Mission;
use Illuminate\Http\Request;

class MissionController extends Controller
{
    // GET /api/missions?module_id=1&difficulty_level=1
    public function index(Request $request)
    {
        $query = Mission::with(['module.strand']);
        if ($request->has('module_id'))        $query->where('module_id', $request->module_id);
        if ($request->has('difficulty_level')) $query->where('difficulty_level', $request->difficulty_level);
        return response()->json(['success' => true, 'data' => $query->get()]);
    }

    // GET /api/missions/{id}
    public function show($id)
    {
        $mission = Mission::with(['module.strand'])->find($id);
        if (!$mission) {
            return response()->json(['success' => false, 'message' => 'Mission not found.'], 404);
        }
        return response()->json(['success' => true, 'data' => $mission]);
    }

    // POST /api/missions
    public function store(Request $request)
    {
        $request->validate([
            'module_id'            => 'required|exists:modules,module_id',
            'mission_title'        => 'required|string',
            'scenario_description' => 'required|string',
            'max_score'            => 'integer|min:0',
            'difficulty_level'     => 'integer|between:1,3',
        ]);
        $mission = Mission::create($request->only(
            'module_id', 'mission_title', 'scenario_description', 'max_score', 'difficulty_level'
        ));
        return response()->json(['success' => true, 'data' => $mission->load('module.strand')], 201);
    }

    // PUT /api/missions/{id}
    public function update(Request $request, $id)
    {
        $mission = Mission::findOrFail($id);
        $mission->update($request->only(
            'module_id', 'mission_title', 'scenario_description', 'max_score', 'difficulty_level'
        ));
        return response()->json(['success' => true, 'data' => $mission]);
    }

    // DELETE /api/missions/{id}
    public function destroy($id)
    {
        Mission::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Mission deleted.']);
    }
}
