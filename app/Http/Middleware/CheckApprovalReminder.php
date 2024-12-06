<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;


class CheckApprovalReminder
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $userId = Auth::id();

            // Cek apakah user adalah approver
            $userRole = DB::table('users')
                ->join('user_roles', 'users.role_id', '=', 'user_roles.role_id')
                ->where('users.id', $userId)
                ->value('role_name'); // Misal: "BEM", "DPM", dll.

            // Ambil dokumen yang butuh persetujuan berdasarkan role
            $pendingApprovals = DB::table('document_submissions')
                ->join('document_status', 'document_submissions.status_id', '=', 'document_status.status_id')
                ->where('document_status.status_description', 'LIKE', "Ditinjau $userRole")
                ->get();

            // Simpan reminder ke dalam session jika ada dokumen yang butuh approval
            if ($pendingApprovals->isNotEmpty()) {
                session(['approval_reminder' => $pendingApprovals]);
                dd($pendingApprovals);
            } else {
                session()->forget('approval_reminder');
            }
        }

        return $next($request);
    }
}
