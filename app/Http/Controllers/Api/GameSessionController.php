<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Mission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class GameSessionController extends Controller
{
    // POST /api/game/heartbeat
    // Unity calls every 30s to keep player marked as online
    public function heartbeat(Request $request)
    {
        $user = $request->user();
        Cache::put("online_user_{$user->user_id}", now()->toDateTimeString(), 60);
        $user->touch();

        return response()->json([
            'success'     => true,
            'message'     => 'Heartbeat received.',
            'server_time' => now()->toDateTimeString(),
        ]);
    }

    // POST /api/game/mission/start
    // Body: { "mission_id": 1, "assessment_type": "post_test" }
    public function startMission(Request $request)
    {
        $request->validate([
            'mission_id'      => 'required|exists:missions,mission_id',
            'assessment_type' => 'required|in:pre_test,post_test,practice',
        ]);

        $user    = $request->user();
        $mission = Mission::with('module.strand')->findOrFail($request->mission_id);

        Cache::put("active_mission_{$user->user_id}", [
            'user_id'         => $user->user_id,
            'username'        => $user->username,
            'mission_id'      => $mission->mission_id,
            'mission_title'   => $mission->mission_title,
            'assessment_type' => $request->assessment_type,
            'started_at'      => now()->toDateTimeString(),
        ], 3600);

        return response()->json([
            'success' => true,
            'message' => 'Mission started.',
            'data'    => [
                'mission'         => $mission,
                'assessment_type' => $request->assessment_type,
                'started_at'      => now()->toDateTimeString(),
            ],
        ]);
    }

    // POST /api/game/mission/complete
    // Body: { "mission_id":1, "assessment_type":"post_test",
    //         "score":85, "accuracy_percentage":85.0 }
    public function completeMission(Request $request)
    {
        $request->validate([
            'mission_id'          => 'required|exists:missions,mission_id',
            'assessment_type'     => 'required|in:pre_test,post_test,practice',
            'score'               => 'required|integer|min:0',
            'accuracy_percentage' => 'required|numeric|between:0,100',
        ]);

        $user = $request->user();

        $assessment = \App\Models\Assessment::create([
            'user_id'             => $user->user_id,
            'mission_id'          => $request->mission_id,
            'assessment_type'     => $request->assessment_type,
            'score'               => $request->score,
            'accuracy_percentage' => $request->accuracy_percentage,
            'taken_at'            => now(),
        ]);

        Cache::forget("active_mission_{$user->user_id}");
        $this->updateDashboard($user->user_id, $request->mission_id);

        return response()->json([
            'success' => true,
            'message' => 'Mission completed and score saved.',
            'data'    => $assessment->load('mission.module.strand'),
        ], 201);
    }

    // GET /api/game/online
    // Returns online players — admin dashboard polls this
    public function onlinePlayers()
    {
        $players = User::where('role', '!=', 'admin')
            ->where('updated_at', '>=', now()->subMinutes(15))
            ->get(['user_id', 'first_name', 'last_name', 'username', 'role', 'updated_at'])
            ->map(function ($u) {
                $activeMission = Cache::get("active_mission_{$u->user_id}");
                return [
                    'user_id'        => $u->user_id,
                    'name'           => $u->first_name . ' ' . $u->last_name,
                    'username'       => $u->username,
                    'role'           => $u->role,
                    'last_seen'      => $u->updated_at,
                    'active_mission' => $activeMission,
                ];
            });

        return response()->json([
            'success' => true,
            'count'   => $players->count(),
            'data'    => $players,
        ]);
    }

    private function updateDashboard(int $userId, int $missionId): void
    {
        $mission  = Mission::with('module')->findOrFail($missionId);
        $strandId = $mission->module->strand_id;

        $scores = \App\Models\Assessment::where('user_id', $userId)
            ->where('assessment_type', 'post_test')
            ->whereHas('mission.module', fn($q) => $q->where('strand_id', $strandId))
            ->pluck('score');

        \App\Models\PerformanceDashboard::updateOrCreate(
            ['user_id' => $userId, 'strand_id' => $strandId],
            [
                'average_score'      => round($scores->avg() ?? 0, 2),
                'missions_completed' => $scores->count(),
                'last_updated'       => now(),
            ]
        );
    }
}
