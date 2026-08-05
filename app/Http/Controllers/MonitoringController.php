<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MonitoringController extends Controller
{
    /**
     * Live monitoring: shows students who are online (heartbeat within the
     * last 15 min, matching GameSessionController's onlinePlayers() window)
     * and what mission/assessment_type they're currently attempting
     * (cached in GameSessionController::startMission as active_mission_{id}).
     *
     * Scoped by cms_scoped_strand: a teacher sees only students whose
     * active mission belongs to their own strand. Admin sees everyone.
     */
    public function index(Request $request)
    {
        $scopedStrand = $request->attributes->get('cms_scoped_strand');

        $students = User::where('role', 'student')
            ->where('updated_at', '>=', now()->subMinutes(15))
            ->get();

        $rows = $students->map(function ($student) {
            $activeMission = Cache::get("active_mission_{$student->user_id}");

            return [
                'user_id'        => $student->user_id,
                'first_name'     => $student->first_name,
                'last_name'      => $student->last_name,
                'username'       => $student->username,
                'is_online'      => Cache::has("online_user_{$student->user_id}"),
                'active_mission' => $activeMission, // null if idle in lobby, array if mid-mission
                'last_seen'      => $student->updated_at,
            ];
        });

        // Scope to the teacher's strand: only show students whose active
        // mission's strand matches. Students with no active mission (idle)
        // are shown to admin only, since we can't attribute them to a
        // strand without an active_mission cache entry.
        if ($scopedStrand) {
            $rows = $rows->filter(function ($row) use ($scopedStrand) {
                if (!$row['active_mission']) {
                    return false;
                }

                $missionId = $row['active_mission']['mission_id'] ?? null;
                if (!$missionId) {
                    return false;
                }

                $mission = \App\Models\Mission::with('module.strand')->find($missionId);
                return $mission && ($mission->module->strand->strand_name ?? null) === $scopedStrand;
            });
        }

        $rows = $rows->sortByDesc('is_online')->values();

        $onlineCount = $rows->where('is_online', true)->count();
        $inMissionCount = $rows->filter(fn($r) => $r['is_online'] && $r['active_mission'])->count();

        return view('admin.monitoring', [
            'rows'           => $rows,
            'onlineCount'    => $onlineCount,
            'inMissionCount' => $inMissionCount,
            'scopedStrand'   => $scopedStrand,
        ]);
    }
}
