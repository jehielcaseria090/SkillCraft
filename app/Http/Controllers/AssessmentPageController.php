<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use Illuminate\Http\Request;

class AssessmentPageController extends Controller
{
    public function index(Request $request)
    {
        $scopedStrand = $request->attributes->get('cms_scoped_strand');

        $query = Assessment::with('user', 'mission.module.strand');

        if ($scopedStrand) {
            $query->whereHas('mission.module.strand', fn($q) => $q->where('strand_name', $scopedStrand));
        }

        if ($request->filled('type')) {
            $query->where('assessment_type', $request->type);
        }

        $assessments = $query->latest('taken_at')->paginate(20)->withQueryString();

        return view('admin.assessments', compact('assessments'));
    }
}
