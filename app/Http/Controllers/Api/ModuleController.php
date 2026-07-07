<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Module;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    // GET /api/modules?strand_id=1
    public function index(Request $request)
    {
        $query = Module::with('strand');
        if ($request->has('strand_id')) {
            $query->where('strand_id', $request->strand_id);
        }
        return response()->json(['success' => true, 'data' => $query->get()]);
    }

    // GET /api/modules/{id}
    public function show($id)
    {
        $module = Module::with(['strand', 'missions'])->find($id);
        if (!$module) {
            return response()->json(['success' => false, 'message' => 'Module not found.'], 404);
        }
        return response()->json(['success' => true, 'data' => $module]);
    }

    // POST /api/modules
    public function store(Request $request)
    {
        $request->validate([
            'strand_id'       => 'required|exists:strands,strand_id',
            'module_name'     => 'required|string',
            'competency_area' => 'required|string',
        ]);
        $module = Module::create($request->only('strand_id', 'module_name', 'competency_area'));
        return response()->json(['success' => true, 'data' => $module->load('strand')], 201);
    }

    // PUT /api/modules/{id}
    public function update(Request $request, $id)
    {
        $module = Module::findOrFail($id);
        $module->update($request->only('strand_id', 'module_name', 'competency_area'));
        return response()->json(['success' => true, 'data' => $module]);
    }

    // DELETE /api/modules/{id}
    public function destroy($id)
    {
        Module::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Module deleted.']);
    }
}
