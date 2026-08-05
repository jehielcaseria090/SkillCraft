<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PlayerController extends Controller
{
    /**
     * Admin sees every student. A teacher sees only students enrolled in
     * their own strand, OR students who have actually played a mission in
     * that strand (covers self-registered Unity accounts with no
     * enrolled_strand tag yet).
     */
    public function index(Request $request)
    {
        $scopedStrand = $request->attributes->get('cms_scoped_strand');

        $query = User::where('role', 'student');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($scopedStrand) {
            $query->where(function ($q) use ($scopedStrand) {
                $q->where('enrolled_strand', $scopedStrand)
                  ->orWhereHas('assessments.mission.module.strand', function ($sq) use ($scopedStrand) {
                      $sq->where('strand_name', $scopedStrand);
                  });
            });
        }

        // NOTE: role filter dropdown in the old view (student/teacher) no
        // longer applies here since this page is students-only now —
        // teacher account management lives on its own page (Manage Teachers).

        $players = $query->paginate(20)->withQueryString();

        return view('admin.players', compact('players', 'scopedStrand'));
    }

    public function show(Request $request, $id)
    {
        $scopedStrand = $request->attributes->get('cms_scoped_strand');

        $player = User::where('role', 'student')->findOrFail($id);

        if ($scopedStrand && !$player->isInScopedStrand($scopedStrand)) {
            abort(403, 'This student is not in your specialization.');
        }

        $assessments = $player->assessments()->with('mission.module.strand')->get();

        return view('admin.players-detail', compact('player', 'assessments'));
    }

    /**
     * Create a student account. Available to admin AND teacher — a teacher
     * creating a student auto-enrolls them in the teacher's own strand;
     * admin picks the strand from a dropdown (or leaves unassigned).
     */
    public function store(Request $request)
    {
        $scopedStrand = $request->attributes->get('cms_scoped_strand');

        $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'email'          => 'required|email|unique:users,email',
            'username'       => 'required|string|unique:users,username',
            'password'       => 'required|min:8|confirmed',
            'contact_number' => 'nullable|string',
            'enrolled_strand'=> 'nullable|string',
        ]);

        // Teacher: force-enroll into their own strand regardless of what
        // was posted, so they can never enroll a student elsewhere.
        $enrolledStrand = $scopedStrand ?: $request->enrolled_strand;

        User::create([
            'name'                  => $request->first_name . ' ' . $request->last_name,
            'first_name'            => $request->first_name,
            'last_name'             => $request->last_name,
            'email'                 => $request->email,
            'username'              => $request->username,
            'password'              => Hash::make($request->password),
            'password_hash'         => Hash::make($request->password),
            'confirm_password_hash' => Hash::make($request->password),
            'role'                  => 'student',
            'enrolled_strand'       => $enrolledStrand,
            'contact_number'        => $request->contact_number,
        ]);

        return redirect()->route('players.index')->with('success', 'Student account created.');
    }

    public function update(Request $request, $id)
    {
        $scopedStrand = $request->attributes->get('cms_scoped_strand');

        $player = User::where('role', 'student')->findOrFail($id);

        if ($scopedStrand && !$player->isInScopedStrand($scopedStrand)) {
            abort(403, 'This student is not in your specialization.');
        }

        $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'email'          => 'required|email|unique:users,email,'.$player->user_id.',user_id',
            'contact_number' => 'nullable|string',
            'enrolled_strand'=> 'nullable|string',
        ]);

        $data = $request->only('first_name', 'last_name', 'email', 'contact_number');
        $data['name'] = $request->first_name . ' ' . $request->last_name;

        // Teacher can never move a student OUT of their own strand via edit.
        if ($scopedStrand) {
            $data['enrolled_strand'] = $scopedStrand;
        } elseif ($request->filled('enrolled_strand')) {
            $data['enrolled_strand'] = $request->enrolled_strand;
        }

        $player->update($data);

        return back()->with('success', 'Student updated.');
    }

    public function destroy(Request $request, $id)
    {
        $scopedStrand = $request->attributes->get('cms_scoped_strand');

        $player = User::where('role', 'student')->findOrFail($id);

        if ($scopedStrand && !$player->isInScopedStrand($scopedStrand)) {
            abort(403, 'This student is not in your specialization.');
        }

        $player->delete();

        return back()->with('success', 'Student deleted.');
    }
}
