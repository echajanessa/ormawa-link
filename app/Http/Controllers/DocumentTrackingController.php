<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentSubmission;
use App\Models\DocumentType;
use App\Models\DocumentStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;


class DocumentTrackingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();

        $perPage = 10;

        $documents = DB::table('document_submissions')
            ->join('document_types', 'document_submissions.doc_type_id', '=', 'document_types.doc_type_id')
            ->join('document_status', 'document_submissions.status_id', '=', 'document_status.status_id')
            ->leftJoin('document_approvals', function ($join) use ($userId) {
                $join->on('document_submissions.submission_id', '=', 'document_approvals.submission_id')
                    ->where('document_approvals.approver_id', '=', $userId);
            })
            ->where(function ($query) use ($userId) {
                $query->where('document_submissions.user_id', $userId)
                    ->orWhereNotNull('document_approvals.approval_id');
            })
            ->where('document_status.status_id', '!=', '16')
            ->select(
                'document_submissions.submission_id',
                'document_submissions.event_name',
                'document_submissions.start_date',
                'document_submissions.end_date',
                'document_types.document_type as doc_type_name',
                'document_submissions.doc_type_id',
                'document_status.status_description as doc_status',
                'document_submissions.status_id',
            )->paginate(10);

        return view('content.document-tracking', compact('documents'));
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $document = DB::table('document_submissions')
            ->join('document_types', 'document_submissions.doc_type_id', '=', 'document_types.doc_type_id')
            ->join('document_status', 'document_submissions.status_id', '=', 'document_status.status_id')
            ->where('submission_id', $id)
            ->select(
                'document_submissions.*',
                'document_types.document_type as doc_type_name',
                'document_status.status_description as doc_status'
            )
            ->first();

        $organizer = DB::table('users')
            ->join('faculties', 'users.faculty_id', '=', 'faculties.faculty_id')
            ->where('users.id', $document->user_id)
            ->select('users.*', 'faculties.faculty_name')
            ->first();

        $approvers = [];
        $currentUserId = $document->user_id;

        $firstApprover = DB::table('users')
            ->leftJoin('supervisors', 'users.id', '=', 'supervisors.user_id')
            ->where('users.id', $currentUserId)
            ->select('users.name', 'users.email', 'supervisors.spv_id')
            ->first();

        if ($firstApprover && $firstApprover->spv_id) {
            // Ambil supervisor dari user yang mengajukan
            $currentUserId = $firstApprover->spv_id;

            // Mengambil supervisor yang lainnya
            while ($currentUserId) {
                $supervisor = DB::table('users')
                    ->leftJoin('supervisors', 'users.id', '=', 'supervisors.user_id')
                    ->where('users.id', $currentUserId)
                    ->select('users.name', 'users.email', 'supervisors.spv_id')
                    ->first();

                if ($supervisor) {
                    $approvers[] = [
                        'name' => $supervisor->name,
                        'email' => $supervisor->email,
                        'status' => $document ? $document->doc_status : 'N/A',
                    ];
                    $currentUserId = $supervisor->spv_id; //rekursif buat spv selanjutnya
                } else {
                    break;
                }
            }
        }
        return view('content.document-tracking-detail', compact('document', 'organizer', 'approvers'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
