<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcceptabilitySurvey;
use Illuminate\Http\Request;

class AcceptabilitySurveyController extends Controller
{
    // POST /api/survey — Submit acceptability ratings
    public function store(Request $request)
    {
        $request->validate([
            'user_id'              => 'required|exists:users,user_id',
            'usability_rating'     => 'required|numeric|between:1,5',
            'interface_rating'     => 'required|numeric|between:1,5',
            'educational_value'    => 'required|numeric|between:1,5',
            'curriculum_alignment' => 'required|numeric|between:1,5',
            'overall_rating'       => 'required|numeric|between:1,5',
            'comments'             => 'nullable|string',
        ]);

        $survey = AcceptabilitySurvey::create([
            'user_id'              => $request->user_id,
            'usability_rating'     => $request->usability_rating,
            'interface_rating'     => $request->interface_rating,
            'educational_value'    => $request->educational_value,
            'curriculum_alignment' => $request->curriculum_alignment,
            'overall_rating'       => $request->overall_rating,
            'comments'             => $request->comments,
            'submitted_at'         => now(),
        ]);

        return response()->json(['success' => true, 'message' => 'Survey submitted.', 'data' => $survey], 201);
    }

    // GET /api/survey/results — Aggregate results (for teachers/admin)
    public function results()
    {
        $avg = AcceptabilitySurvey::selectRaw('
            AVG(usability_rating)     as avg_usability,
            AVG(interface_rating)     as avg_interface,
            AVG(educational_value)    as avg_educational_value,
            AVG(curriculum_alignment) as avg_curriculum_alignment,
            AVG(overall_rating)       as avg_overall,
            COUNT(*)                  as total_respondents
        ')->first();

        return response()->json(['success' => true, 'data' => $avg]);
    }

    // GET /api/survey — List all surveys
    public function index()
    {
        $surveys = AcceptabilitySurvey::with('user')->latest()->get();
        return response()->json(['success' => true, 'data' => $surveys]);
    }
}
