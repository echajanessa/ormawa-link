<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserRole;

class UserRoleController extends Controller
{
    public function index()
    {
        $userroles = UserRole::all();
        return view('auth.register', compact('userroles'));

        // Debugging untuk cek data
        dd($userroles);
    }
}
