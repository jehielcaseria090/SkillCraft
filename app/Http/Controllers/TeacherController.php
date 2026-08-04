<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class TeacherController extends Controller
{
    public function index()
    {
        $teachers = User::where('role', 'teacher')
                        ->orderBy('specialization')
                        ->orderBy('first_name')
                        ->get();

        return view('admin.teachers', compact('teachers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'email'          => 'required|email|unique:users,email',
            'username'       => 'required|string|unique:users,username',
            'password'       => 'required|min:8|confirmed',
            'specialization' => 'required|in:ict,smaw,cookery',
            'contact_number' => 'nullable|string',
        ]);

        User::create([
            'name'                  => $request->first_name . ' ' . $request->last_name,
            'first_name'            => $request->first_name,
            'last_name'             => $request->last_name,
            'email'                 => $request->email,
            'username'              => $request->username,
            'password'              => Hash::make($request->password),
            'password_hash'         => Hash::make($request->password),
            'confirm_password_hash' => Hash::make($request->password),
            'role'                  => 'teacher',
            'specialization'        => $request->specialization,
            'contact_number'        => $request->contact_number,
        ]);

        return redirect()->route('teachers.index')
                         ->with('success', 'Teacher account created.');
    }

    public function destroy($id)
    {
        $teacher = User::where('role', 'teacher')->findOrFail($id);
        $teacher->delete();

        return redirect()->route('teachers.index')
                         ->with('success', 'Teacher account removed.');
    }
}
