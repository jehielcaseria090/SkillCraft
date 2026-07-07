<?php

namespace App\Http\Controllers;

use App\Models\AcceptabilitySurvey;

class SurveyPageController extends Controller
{
    public function index()
    {
        $surveys = AcceptabilitySurvey::with('user')->latest('submitted_at')->paginate(20);
        $avg = AcceptabilitySurvey::selectRaw('
            AVG(usability_rating) as usability,
            AVG(interface_rating) as interface_r,
            AVG(educational_value) as educational,
            AVG(curriculum_alignment) as curriculum,
            AVG(overall_rating) as overall
        ')->first();
        return view('admin.surveys', compact('surveys','avg'));
    }
}
