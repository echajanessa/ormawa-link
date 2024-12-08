<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        if (in_array($user->role_id, ['RL001', 'RL002'])) {
            $users = User::with('faculty', 'userRole', 'supervisor')->paginate(8);
            $roles = DB::table('user_roles')->pluck('role_name', 'role_id');
            $faculties = DB::table('faculties')->pluck('faculty_name', 'faculty_id');
            $types = DB::table('user_types')->pluck('type_description', 'user_type_id');
            $organizations = DB::table('organizations')->pluck('org_name', 'organization_id');
        } elseif (in_array($user->role_id, ['RL003', 'RL004', 'RL005'])) {
            $users = User::with('faculty', 'userRole', 'supervisor')->where('faculty_id', $user->faculty_id)->paginate(8);
            $roles = DB::table('user_roles')->pluck('role_name', 'role_id');
            $faculties = DB::table('faculties')->pluck('faculty_name', 'faculty_id');
            $types = DB::table('user_types')->pluck('type_description', 'user_type_id');
            $organizations = DB::table('organizations')->pluck('org_name', 'organization_id');
        }


        return view('content.user-management', compact('users', 'roles', 'faculties', 'types', 'organizations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'user_type_id' => 'required|exists:user_types,user_type_id',
            'role_id' => 'required|exists:user_roles,role_id',
            'faculty_id' => 'nullable|exists:faculties,faculty_id',
            'organization_id' => 'nullable|exists:organizations,organization_id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $faculty_id = $request->input('faculty_id');
        if (in_array($user->role_id, ['RL003', 'RL004', 'RL005'])) {
            $faculty_id = $user->faculty_id;
        }

        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'user_type_id' => $request->input('user_type_id'),
            'phone_number' => null,
            'role_id' => $request->input('role_id'),
            'faculty_id' => $faculty_id,
            'organization_id' => $request->input('organization_id'),
            'password' => Hash::make('AdOrma123'), // Password default
        ]);

        return redirect()->route('user-management.index')->with('success', 'Akun berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $users = User::with('faculty', 'userRole', 'supervisor')->get();
        $user = User::findOrFail($id);
        $roles = DB::table('user_roles')->pluck('role_name', 'role_id');
        $faculties = DB::table('faculties')->pluck('faculty_name', 'faculty_id');

        return view('content.user-management', compact('user', 'roles', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->update([
            'name' => $request->input('name'),
            'role_id' => $request->input('role_id'),
            'faculty_id' => $request->input('faculty_id'),
        ]);

        return redirect()->route('user-management.index')->with('success', 'Akun berhasil diperbarui');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $deleteUser = User::findOrFail($id);
        $deleteUser->delete();

        return redirect()->route('user-management.index');
    }
}
