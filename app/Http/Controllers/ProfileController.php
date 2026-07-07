<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        $admin = User::findOrFail(session('admin_id'));
        return view('admin.profile', compact('admin'));
    }

    public function updatePicture(Request $request)
    {
        $request->validate([
            'profile_picture' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $admin = User::findOrFail(session('admin_id'));

        if ($admin->profile_picture &&
            Storage::disk('public')->exists($admin->profile_picture)) {
            Storage::disk('public')->delete($admin->profile_picture);
        }

        $path = $request->file('profile_picture')
                        ->store('profile_pictures', 'public');

        $admin->update(['profile_picture' => $path]);
        session(['admin_picture' => $path]);

        return back()->with('success', 'Profile picture updated successfully.');
    }

    public function updateInfo(Request $request)
    {
        $admin = User::findOrFail(session('admin_id'));

        $request->validate([
            'first_name'     => 'required|string|max:100',
            'last_name'      => 'required|string|max:100',
            'email'          => 'required|email|unique:users,email,'.$admin->user_id.',user_id',
            'contact_number' => 'nullable|string|max:20',
        ]);

        $admin->update([
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'name'           => $request->first_name.' '.$request->last_name,
            'email'          => $request->email,
            'contact_number' => $request->contact_number,
        ]);

        session(['admin_name' => $request->first_name.' '.$request->last_name]);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|min:8|confirmed',
        ]);

        $admin = User::findOrFail(session('admin_id'));

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $admin->update([
            'password'      => Hash::make($request->password),
            'password_hash' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password changed successfully.');
    }

    public function removePicture()
    {
        $admin = User::findOrFail(session('admin_id'));

        if ($admin->profile_picture &&
            Storage::disk('public')->exists($admin->profile_picture)) {
            Storage::disk('public')->delete($admin->profile_picture);
        }

        $admin->update(['profile_picture' => null]);
        session()->forget('admin_picture');

        return back()->with('success', 'Profile picture removed.');
    }
}
