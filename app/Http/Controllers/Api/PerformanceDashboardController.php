<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PerformanceDashboard;
use Illuminate\Http\Request;

class PerformanceDashboardController extends Controller
{
    // GET /api/dashboard/{userId} — All strand performance for one student
    public function show($userId)
    {
        $records = PerformanceDashboard::with('strand')
                    ->where('user_id', $userId)
                    ->get();

        return response()->json(['success' => true, 'data' => $records]);
    }

    // GET /api/dashboard/{userId}/{strandId}
    public function showByStrand($userId, $strandId)
    {
        $record = PerformanceDashboard::with(['user', 'strand'])
                    ->where('user_id', $userId)
                    ->where('strand_id', $strandId)
                    ->first();

        if (!$record) {
            return response()->json(['success' => false, 'message' => 'No data yet.'], 404);
        }

        return response()->json(['success' => true, 'data' => $record]);
    }

    // GET /api/dashboard/leaderboard/{strandId} — Top students per strand
    public function leaderboard($strandId)
    {
        $records = PerformanceDashboard::with('user')
                    ->where('strand_id', $strandId)
                    ->orderByDesc('average_score')
                    ->limit(10)
                    ->get()
                    ->map(fn($r) => [
                        'rank'               => 0,
                        'user_id'            => $r->user_id,
                        'name'               => $r->user->first_name . ' ' . $r->user->last_name,
                        'average_score'      => $r->average_score,
                        'missions_completed' => $r->missions_completed,
                    ])
                    ->values()
                    ->map(function ($item, $index) {
                        $item['rank'] = $index + 1;
                        return $item;
                    });

        return response()->json(['success' => true, 'data' => $records]);
    }
}
