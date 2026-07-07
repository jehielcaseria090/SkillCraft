<?php

namespace App\Http\Controllers;

use App\Models\Strand;
use Illuminate\Http\Request;

class StrandPageController extends Controller
{
    public function index(Request $request)
    {
        $query = Strand::withCount('modules');

        if ($request->filled('strand_name')) {
            $query->where('strand_name', $request->strand_name);
        }

        $strands     = $query->latest()->get();
        $strandNames = Strand::pluck('strand_name');

        return view('admin.strands', compact('strands', 'strandNames'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'strand_name' => 'required|string|unique:strands,strand_name',
            'description' => 'required|string',
        ]);
        Strand::create($request->only('strand_name', 'description'));
        return back()->with('success', 'Strand created successfully.');
    }

    public function update(Request $request, $id)
    {
        $strand = Strand::findOrFail($id);
        $request->validate([
            'strand_name' => 'required|string|unique:strands,strand_name,'.$strand->strand_id.',strand_id',
            'description' => 'required|string',
        ]);
        $strand->update($request->only('strand_name', 'description'));
        return back()->with('success', 'Strand updated.');
    }

    public function destroy($id)
    {
        Strand::findOrFail($id)->delete();
        return back()->with('success', 'Strand deleted.');
    }
}
