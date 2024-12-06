<?php

namespace App\Http\Controllers;

use App\Models\DocumentRegulation;
use App\Models\DocumentSubmission;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{

    public function showLpjReminder(Request $request)
    {

        $userId = Auth::id();
        $user = User::findOrFail($userId);
        $userRole = Auth::user()->role_id;

        // untuk filtering card
        $currentYear = Carbon::now()->year;
        $startYear = $request->input('year', $currentYear); // pilih tahun lewat dropdown 
        $startDate = Carbon::create($startYear, 8, 1); // 1 Agustus
        $endDate = Carbon::create($startYear + 1, 7, 31); // 31 Juli tahun berikutnya

        $totalDocuments = 0;
        $proposalCount = 0;
        $lpjCount = 0;
        $sikCount = 0;
        $activeDocuments = 0;

        if (in_array($userRole, ['RL009', 'RL008', 'RL007', 'RL006'])) {
            // Hitung jumlah dokumen berdasarkan jenis
            $totalDocuments = DocumentSubmission::where('user_id', $userId)
                ->whereBetween('created_at', [$startDate, $endDate])->count();
            $proposalCount = DocumentSubmission::where('user_id', $userId)->where('doc_type_id', 'DT01')
                ->whereBetween('created_at', [$startDate, $endDate])->count();
            $lpjCount = DocumentSubmission::where('user_id', $userId)->where('doc_type_id', 'DT05')
                ->whereBetween('created_at', [$startDate, $endDate])->count();
            $sikCount = DocumentSubmission::where('user_id', $userId)->where('doc_type_id', 'DT02')->count();
            $activeDocuments = DocumentSubmission::where('user_id', $userId)->where('status_id', '!=', '16')
                ->whereBetween('created_at', [$startDate, $endDate])->count();
        } else {
            $totalDocuments = DocumentSubmission::with(['documentType'])
                ->leftJoin('document_approvals', function ($join) use ($userId) {
                    $join->on('document_submissions.submission_id', '=', 'document_approvals.submission_id')
                        ->where('document_approvals.approver_id', '=', $userId);
                })
                ->where(function ($query) use ($userId) {
                    $query->where('document_submissions.user_id', $userId)
                        ->orWhereNotNull('document_approvals.approval_id');
                })
                ->select(
                    'document_submissions.submission_id',
                    'document_submissions.event_name',
                    'document_submissions.start_date',
                    'document_submissions.end_date',
                    'document_submissions.doc_type_id',
                    'document_status.status_description as doc_status',
                    'document_submissions.status_id'
                )
                ->whereBetween('document_submissions.created_at', [$startDate, $endDate])
                ->count();

            $proposalCount = DocumentSubmission::with(['documentType'])
                ->leftJoin('document_approvals', function ($join) use ($userId) {
                    $join->on('document_submissions.submission_id', '=', 'document_approvals.submission_id')
                        ->where('document_approvals.approver_id', '=', $userId);
                })
                ->where(function ($query) use ($userId) {
                    $query->where('document_submissions.user_id', $userId)
                        ->orWhereNotNull('document_approvals.approval_id');
                })->where('doc_type_id', 'DT01')
                ->whereBetween('document_submissions.created_at', [$startDate, $endDate])->count();

            $lpjCount = DocumentSubmission::with(['documentType'])
                ->leftJoin('document_approvals', function ($join) use ($userId) {
                    $join->on('document_submissions.submission_id', '=', 'document_approvals.submission_id')
                        ->where('document_approvals.approver_id', '=', $userId);
                })
                ->where(function ($query) use ($userId) {
                    $query->where('document_submissions.user_id', $userId)
                        ->orWhereNotNull('document_approvals.approval_id');
                })->where('doc_type_id', 'DT05')
                ->whereBetween('document_submissions.created_at', [$startDate, $endDate])->count();

            $activeDocuments = DocumentSubmission::with(['documentType'])
                ->leftJoin('document_approvals', function ($join) use ($userId) {
                    $join->on('document_submissions.submission_id', '=', 'document_approvals.submission_id')
                        ->where('document_approvals.approver_id', '=', $userId);
                })
                ->where(function ($query) use ($userId) {
                    $query->where('document_submissions.user_id', $userId)
                        ->orWhereNotNull('document_approvals.approval_id');
                })
                ->where('document_submissions.status_id', '!=', '16')
                ->whereBetween('document_submissions.created_at', [$startDate, $endDate])->count();
        }


        $regulations = DocumentRegulation::all();

        // Dapatkan dokumen proposal terakhir yang diajukan
        $lastProposal = DocumentSubmission::where('doc_type_id', 'DT01')
            ->where('user_id', $userId)
            ->where('status_id', '16')
            ->orderBy('end_date')
            ->first();

        // Jika tidak ada proposal yang diajukan, kembalikan halaman tanpa peringatan
        if (!$lastProposal) {
            return view('content.dashboard.dashboards-analytics', compact('user', 'userRole', 'activeDocuments', 'lastProposal', 'regulations', 'totalDocuments', 'proposalCount', 'lpjCount', 'sikCount'));
        }

        // Hitung selisih hari dari end_date
        $endDate = Carbon::parse($lastProposal->end_date);
        $daysSinceEnd = floor($endDate->diffInDays(Carbon::now(), false));
        $daysRemaining = max(0, 30 - $daysSinceEnd); // 30 hari setelah end_date

        //Modal Reminder Approval
        $approverRole = DB::table('users')
            ->join('user_roles', 'users.role_id', '=', 'user_roles.role_id')
            ->where('users.id', $userId)
            ->value('role_name'); // Misalnya: "BEM", "DPM", dll.

        // Ambil dokumen yang harus disetujui oleh pengguna berdasarkan role mereka
        $approvalReminder = DB::table('document_submissions')
            ->join('document_status', 'document_submissions.status_id', '=', 'document_status.status_id')
            ->where('document_status.status_description', 'LIKE', "Ditinjau $approverRole") // Sesuaikan dengan role user
            ->get();

        // Simpan ke session untuk digunakan di tampilan
        session(['approval_reminder' => $approvalReminder]);

        // dd(session()->all()); // Tambahkan ini untuk memastikan data benar-benar ada

        return view('content.dashboard.dashboards-analytics', compact('approvalReminder', 'daysSinceEnd', 'daysRemaining', 'user', 'userRole', 'activeDocuments', 'lastProposal', 'regulations', 'totalDocuments', 'proposalCount', 'lpjCount', 'sikCount'));
    }
}
