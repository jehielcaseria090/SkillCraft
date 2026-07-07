<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use Illuminate\Http\Request;

class AssessmentPageController extends Controller
{
    public function index(Request $request)
    {
        $query = Assessment::with(['user','mission.module.strand']);
        if ($request->type) $query->where('assessment_type', $request->type);
        $assessments = $query->latest('taken_at')->paginate(20);
        return view('admin.assessments', compact('assessments'));
    }
}
