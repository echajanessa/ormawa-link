<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentSubmission;
use App\Models\DocumentType;
use App\Models\DocumentStatus;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DocumentHistoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();

        // Ambil dokumen yang sudah "Done"
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
            ->where('document_status.status_id', '=', '16') // filter dokumen yang sudah selesai
            ->select(
                'document_submissions.submission_id',
                'document_submissions.event_name',
                'document_submissions.start_date',
                'document_submissions.end_date',
                'document_submissions.doc_type_id',
                'document_types.document_type as doc_type_name',
                'document_status.status_description as doc_status'
            )->paginate(10);

        return view('content.document-history', compact('documents'));
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
                'document_status.status_description as doc_status',
            )
            ->first();

        $organizer = DB::table('users')
            ->join('faculties', 'users.faculty_id', '=', 'faculties.faculty_id')
            ->where('users.id', $document->user_id)
            ->select('users.*', 'faculties.faculty_name')
            ->first();

        $docApprove = DB::table('document_approvals')
            ->join('users', 'document_approvals.approver_id', 'users.id')
            ->where('submission_id', $id)
            ->select(
                'document_approvals.*',
                'users.name as approver'
            )
            ->get();

        $sikPath = DB::table('document_files')
            ->join('document_submissions', 'document_files.submission_id', 'document_submissions.submission_id')
            ->where('document_submissions.submission_id', $id)
            ->where('document_files.document_type', 'DT02')
            ->select(
                'document_files.*'
            )
            ->first();

        $docPath = DB::table('document_files')
            ->join('document_submissions', 'document_files.submission_id', 'document_submissions.submission_id')
            ->where('document_submissions.submission_id', $id)
            ->where('document_files.document_type', ['DT01', 'DT05'])
            ->where('document_submissions.status_id', '16')
            ->select(
                'document_files.*'
            )
            ->orderBy('document_files.created_at', 'desc')
            ->first();

        return view('content.document-history-detail', compact('document', 'organizer', 'docApprove', 'sikPath', 'docPath'));
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
