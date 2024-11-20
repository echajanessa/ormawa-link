<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserRole;
use App\Models\Faculty;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth; // Import Auth
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */

    public function edit()
    {
        $user = Auth::user();
        $role = UserRole::find($user->role_id);
        $faculty = Faculty::find($user->faculty_id);
        $organization = Organization::find($user->organization_id);

        return view('content.profile-acc', compact('user', 'role', 'faculty', 'organization'));
    }

    public function update(Request $request)
    {
        Log::info('updateProfile method called');

        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|integer',
        ]);

        $userId = Auth::id();
        $user = User::find($userId);
        $user->name = $request->input('name');
        $user->phone_number = $request->input('phone_number');
        $user->save();

        return redirect()->route('profile.edit')->with('success', 'Profil berhasil diperbarui');
    }
}
