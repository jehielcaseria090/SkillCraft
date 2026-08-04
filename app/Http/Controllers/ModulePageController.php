<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Strand;
use Illuminate\Http\Request;

class ModulePageController extends Controller
{
    public function index(Request $request)
    {
        $scopedStrand = $request->attributes->get('cms_scoped_strand');

        $query = Module::with('strand')->withCount('missions');

        if ($scopedStrand) {
            $query->whereHas('strand', fn($q) => $q->where('strand_name', $scopedStrand));
        } elseif ($request->filled('strand_id')) {
            $query->where('strand_id', $request->strand_id);
        }

        $modules = $query->get();
        $strands = $scopedStrand
            ? Strand::where('strand_name', $scopedStrand)->get()
            : Strand::all();

        return view('admin.modules', compact('modules', 'strands'));
    }

    public function store(Request $request)
    {
        $scopedStrand = $request->attributes->get('cms_scoped_strand');

        $request->validate([
            'strand_id'       => 'required|exists:strands,strand_id',
            'module_name'     => 'required|string',
            'competency_area' => 'required|string',
        ]);

        if ($scopedStrand) {
            $strand = Strand::find($request->strand_id);
            if (!$strand || $strand->strand_name !== $scopedStrand) {
                abort(403, 'You can only add modules to your own specialization.');
            }
        }

        Module::create($request->only('strand_id', 'module_name', 'competency_area'));

        return back()->with('success', 'Module created successfully.');
    }

    public function update(Request $request, $id)
    {
        $scopedStrand = $request->attributes->get('cms_scoped_strand');

        $module = Module::with('strand')->findOrFail($id);

        if ($scopedStrand) {
            if (($module->strand->strand_name ?? null) !== $scopedStrand) {
                abort(403, 'You can only edit modules in your own specialization.');
            }

            if ($request->filled('strand_id')) {
                $newStrand = Strand::find($request->strand_id);
                if (!$newStrand || $newStrand->strand_name !== $scopedStrand) {
                    abort(403, 'You cannot move a module outside your specialization.');
                }
            }
        }

        $module->update($request->only('strand_id', 'module_name', 'competency_area'));

        return back()->with('success', 'Module updated.');
    }

    public function destroy(Request $request, $id)
    {
        $scopedStrand = $request->attributes->get('cms_scoped_strand');

        $module = Module::with('strand')->findOrFail($id);

        if ($scopedStrand && ($module->strand->strand_name ?? null) !== $scopedStrand) {
            abort(403, 'You can only delete modules in your own specialization.');
        }

        $module->delete();

        return back()->with('success', 'Module deleted.');
    }
}
