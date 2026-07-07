<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Strand;
use Illuminate\Http\Request;

class ModulePageController extends Controller
{
    public function index(Request $request)
    {
        $query = Module::with('strand')->withCount('missions');

        if ($request->filled('strand_id')) {
            $query->where('strand_id', $request->strand_id);
        }

        $modules = $query->latest()->get();
        $strands = Strand::all();

        return view('admin.modules', compact('modules', 'strands'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'strand_id'       => 'required|exists:strands,strand_id',
            'module_name'     => 'required|string',
            'competency_area' => 'required|string',
        ]);
        Module::create($request->only('strand_id', 'module_name', 'competency_area'));
        return back()->with('success', 'Module created successfully.');
    }

    public function update(Request $request, $id)
    {
        Module::findOrFail($id)->update(
            $request->only('strand_id', 'module_name', 'competency_area')
        );
        return back()->with('success', 'Module updated.');
    }

    public function destroy($id)
    {
        Module::findOrFail($id)->delete();
        return back()->with('success', 'Module deleted.');
    }
}
