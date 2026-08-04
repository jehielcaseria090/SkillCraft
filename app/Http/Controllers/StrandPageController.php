<?php

namespace App\Http\Controllers;

use App\Models\Strand;
use Illuminate\Http\Request;

class StrandPageController extends Controller
{
    public function index(Request $request)
    {
        $scopedStrand = $request->attributes->get('cms_scoped_strand');

        $query = Strand::withCount('modules');

        if ($scopedStrand) {
            // Teacher: locked to their own strand only, filter dropdown ignored
            $query->where('strand_name', $scopedStrand);
        } elseif ($request->filled('strand_name')) {
            // Admin: free to filter by any strand
            $query->where('strand_name', $request->strand_name);
        }

        $strands = $query->latest()->get();

        $strandNames = $scopedStrand
            ? collect([$scopedStrand])
            : Strand::pluck('strand_name');

        return view('admin.strands', compact('strands', 'strandNames'));
    }

    public function store(Request $request)
    {
        $this->assertAdmin();

        $request->validate([
            'strand_name' => 'required|string|unique:strands,strand_name',
            'description' => 'required|string',
        ]);

        Strand::create($request->only('strand_name', 'description'));

        return back()->with('success', 'Strand created successfully.');
    }

    public function update(Request $request, $id)
    {
        $this->assertAdmin();

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
        $this->assertAdmin();

        Strand::findOrFail($id)->delete();

        return back()->with('success', 'Strand deleted.');
    }

    /**
     * Defense in depth: even though routes/web.php should already gate
     * these methods behind 'admin.only' middleware, this blocks direct
     * access too in case that route grouping is ever changed by mistake.
     */
    private function assertAdmin(): void
    {
        if (session('admin_role') !== 'admin') {
            abort(403, 'Only administrators can manage strands.');
        }
    }
}
