<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Assessment;
use App\Models\Mission;
use App\Models\PerformanceDashboard;
use Illuminate\Http\Request;

class AssessmentController extends Controller
{
    // GET /api/assessments
    public function index(Request $request)
    {
        $query = Assessment::with(['user', 'mission.module.strand']);
        if ($request->has('user_id'))         $query->where('user_id', $request->user_id);
        if ($request->has('assessment_type')) $query->where('assessment_type', $request->assessment_type);
        if ($request->has('mission_id'))      $query->where('mission_id', $request->mission_id);
        return response()->json(['success' => true, 'data' => $query->get()]);
    }

    // POST /api/assessments
    public function store(Request $request)
    {
        $request->validate([
            'user_id'             => 'required|exists:users,user_id',
            'mission_id'          => 'required|exists:missions,mission_id',
            'assessment_type'     => 'required|in:pre_test,post_test,practice',
            'score'               => 'required|integer|min:0',
            'accuracy_percentage' => 'required|numeric|between:0,100',
        ]);

        $assessment = Assessment::create([
            'user_id'             => $request->user_id,
            'mission_id'          => $request->mission_id,
            'assessment_type'     => $request->assessment_type,
            'score'               => $request->score,
            'accuracy_percentage' => $request->accuracy_percentage,
            'taken_at'            => now(),
        ]);

        $this->updateDashboard($request->user_id, $request->mission_id);

        return response()->json([
            'success' => true,
            'message' => 'Assessment recorded.',
            'data'    => $assessment->load('mission.module.strand'),
        ], 201);
    }

    // GET /api/assessments/compare/{userId}/{missionId}
    // ── FIXED ────────────────────────────────────────────────────────
    // OLD: returned 404 when no records → Unity defaulted to post_test
    // NEW: always returns 200 with null values → Unity correctly detects
    //      pre_test == null means first time → save as pre_test
    public function compare($userId, $missionId)
    {
        $preTest  = Assessment::where('user_id', $userId)
                              ->where('mission_id', $missionId)
                              ->where('assessment_type', 'pre_test')
                              ->latest()->first();

        $postTest = Assessment::where('user_id', $userId)
                              ->where('mission_id', $missionId)
                              ->where('assessment_type', 'post_test')
                              ->latest()->first();

        $improvement = null;
        if ($preTest && $postTest) {
            $improvement = $postTest->score - $preTest->score;
        }

        // FIXED: Always return 200 success:true
        // pre_test: null  = never taken → Unity saves as pre_test
        // pre_test: {...} = already taken → Unity saves as post_test
        return response()->json([
            'success' => true,
            'data'    => [
                'pre_test'    => $preTest,
                'post_test'   => $postTest,
                'improvement' => $improvement,
            ],
        ]);
    }

    private function updateDashboard(int $userId, int $missionId): void
    {
        $mission  = Mission::with('module')->findOrFail($missionId);
        $strandId = $mission->module->strand_id;

        $scores = Assessment::where('user_id', $userId)
            ->where('assessment_type', 'post_test')
            ->whereHas('mission.module', fn($q) => $q->where('strand_id', $strandId))
            ->pluck('score');

        PerformanceDashboard::updateOrCreate(
            ['user_id' => $userId, 'strand_id' => $strandId],
            [
                'average_score'      => round($scores->avg() ?? 0, 2),
                'missions_completed' => $scores->count(),
                'last_updated'       => now(),
            ]
        );
    }
}
