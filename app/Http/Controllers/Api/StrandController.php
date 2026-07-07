<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Strand;
use Illuminate\Http\Request;

class StrandController extends Controller
{
    public function index()
    {
        $strands = Strand::with('modules')->get();
        return response()->json(['success' => true, 'data' => $strands]);
    }

    public function show($id)
    {
        $strand = Strand::with(['modules.missions'])->find($id);
        if (!$strand) {
            return response()->json(['success' => false, 'message' => 'Strand not found.'], 404);
        }
        return response()->json(['success' => true, 'data' => $strand]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'strand_name' => 'required|string|unique:strands,strand_name',
            'description' => 'required|string',
        ]);
        $strand = Strand::create($request->only('strand_name', 'description'));
        return response()->json(['success' => true, 'data' => $strand], 201);
    }

    public function update(Request $request, $id)
    {
        $strand = Strand::findOrFail($id);
        $strand->update($request->only('strand_name', 'description'));
        return response()->json(['success' => true, 'data' => $strand]);
    }

    public function destroy($id)
    {
        Strand::findOrFail($id)->delete();
        return response()->json(['success' => true, 'message' => 'Strand deleted.']);
    }
}
