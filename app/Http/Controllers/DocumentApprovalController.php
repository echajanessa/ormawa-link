<?php

namespace App\Http\Controllers;

use App\Models\DocumentSubmission;
use App\Models\DocumentApproval;
use App\Models\DocumentFile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DocumentApprovalController extends Controller
{
    public function countPendingApprovals()
    {
        $currentUser = Auth::user();
        $roleId = $currentUser->role_id;
        $facultyId = $currentUser->faculty_id;

        $pendingApprovals = DB::table('document_submissions')
            ->join('document_types', 'document_submissions.doc_type_id', '=', 'document_types.doc_type_id')
            ->join('users', 'document_submissions.user_id', '=', 'users.id')
            ->leftJoin('faculties', 'users.faculty_id', '=', 'faculties.faculty_id')
            ->select(
                'document_submissions.submission_id',
                'document_submissions.event_name',
                'users.name',
                'document_submissions.start_date',
                'document_submissions.end_date',
                'document_types.document_type as type',
                'document_submissions.doc_type_id',
                'faculties.faculty_name'
            );

        if ($roleId == 'RL002') {
            // Untuk LEMAWA, tampilkan dokumen yang sudah disetujui oleh DEKAN dari semua fakultas
            $pendingApprovals->where('document_submissions.status_id', '=', 5); // Review LEMAWA
        } else {
            // Filter berdasarkan fakultas untuk role selain LEMAWA
            $pendingApprovals->where('users.faculty_id', '=', $facultyId);

            if ($roleId == 'RL007') {
                // Untuk BEM, tampilkan dokumen yang diajukan oleh UKM/HIMA dan belum disetujui oleh BEM
                $pendingApprovals->where('document_submissions.status_id', '=', 1); // Review BEM
            } elseif ($roleId == 'RL006') {
                // Untuk DPM, tampilkan dokumen yang sudah disetujui oleh BEM (Review DPM)
                $pendingApprovals->where('document_submissions.status_id', '=', 2); // Review DPM
            } elseif ($roleId == 'RL005') {
                // Untuk BINMA, tampilkan dokumen yang sudah disetujui oleh DPM (Review BINMA)
                $pendingApprovals->where('document_submissions.status_id', '=', 3); // Review BINMA
            } elseif ($roleId == 'RL004') {
                // Untuk DEKAN, tampilkan dokumen yang sudah disetujui oleh BINMA (Review DEKAN)
                $pendingApprovals->where('document_submissions.status_id', '=', 4); // Review DEKAN
            }
        }

        // Tambahkan count() untuk menghitung jumlah data
        $count = $pendingApprovals->count();

        return response()->json(['count' => $count]);
    }

    public function index()
    {
        $currentUser = Auth::user();
        $roleId = $currentUser->role_id;
        $facultyId = $currentUser->faculty_id;

        // $userIds = $this->getSubordinateUserIds($currentUser->id);

        $documentQuery = DB::table('document_submissions')
            ->join('document_types', 'document_submissions.doc_type_id', '=', 'document_types.doc_type_id')
            ->join('users', 'document_submissions.user_id', '=', 'users.id')
            ->leftJoin('faculties', 'users.faculty_id', '=', 'faculties.faculty_id')
            ->select(
                'document_submissions.submission_id',
                'document_submissions.event_name',
                'users.name',
                'document_submissions.start_date',
                'document_submissions.end_date',
                'document_types.document_type as type',
                'document_submissions.doc_type_id',
                'faculties.faculty_name'
            );

        if ($roleId == 'RL002') {
            // Untuk LEMAWA, tampilkan dokumen yang sudah disetujui oleh DEKAN dari semua fakultas
            $documentQuery->where('document_submissions.status_id', '=', 5); // Review LEMAWA
        } else {
            // Filter berdasarkan fakultas untuk role selain LEMAWA
            $documentQuery->where('users.faculty_id', '=', $facultyId);

            if ($roleId == 'RL007') {
                // Untuk BEM, tampilkan dokumen yang diajukan oleh UKM/HIMA dan belum disetujui oleh BEM
                $documentQuery->where('document_submissions.status_id', '=', 1); // Review BEM
            } elseif ($roleId == 'RL006') {
                // Untuk DPM, tampilkan dokumen yang sudah disetujui oleh BEM (Review DPM)
                $documentQuery->where('document_submissions.status_id', '=', 2); // Review DPM
            } elseif ($roleId == 'RL005') {
                // Untuk BINMA, tampilkan dokumen yang sudah disetujui oleh DPM (Review BINMA)
                $documentQuery->where('document_submissions.status_id', '=', 3); // Review BINMA
            } elseif ($roleId == 'RL004') {
                // Untuk DEKAN, tampilkan dokumen yang sudah disetujui oleh BINMA (Review DEKAN)
                $documentQuery->where('document_submissions.status_id', '=', 4); // Review DEKAN
            }
        }


        $documentSubmissions = $documentQuery->paginate(5);


        return view('content.document-approval', compact('documentSubmissions'));
    }

    public function show($submissionId)
    {
        $document = DB::table('document_submissions')
            ->join('document_types', 'document_submissions.doc_type_id', '=', 'document_types.doc_type_id')
            ->join('users', 'document_submissions.user_id', '=', 'users.id')
            ->select(
                'document_submissions.*',
                'users.name as organizer',
                'document_types.document_type as doc_type_name'
            )
            ->where('submission_id', $submissionId)
            ->first();

        // Ambil dokumen proposal atau LPJ terbaru yang diupload oleh approver terakhir
        $proposalLpj = DB::table('document_files')
            ->leftJoin('document_approvals', 'document_files.approval_id', '=', 'document_approvals.approval_id')
            ->select(
                'document_files.*',
                'document_approvals.status_id',
                'document_approvals.updated_at'
            )
            ->where('document_files.submission_id', $submissionId)
            ->where(function ($query) {
                $query->where('document_files.document_type', '=', 'DT01') // Dokumen Proposal
                    ->orWhere('document_files.document_type', '=', 'DT05'); // Dokumen LPJ
            })->orderBy('document_files.created_at', 'desc')
            ->first(); // Ambil hanya satu dokumen terbaru


        // Ambil dokumen surat pengantar yang diajukan pertama kali oleh pengaju (tidak di-replace)
        $suratPengantar = DB::table('document_files')
            ->where('submission_id', $submissionId)
            ->where('document_type', 'DT03') // DT03 adalah surat pengantar
            ->first();

        if ($proposalLpj) {
            Log::info('Proposal/LPJ ditemukan:', ['proposalLpj' => $proposalLpj]);
        } else {
            Log::info('Proposal/LPJ belum ada, menunggu pengajuan.');
        }

        $approvals = DB::table('document_approvals')
            ->where('submission_id', $submissionId)
            ->get();

        return view('content.document-approval-detail', compact('document', 'proposalLpj', 'suratPengantar', 'approvals'));
    }

    public function process(Request $request, $submissionId)
    {
        Log::info('Data yang diterima:', $request->all());

        $request->validate([
            'decision' => 'required|in:approve,reject',
            'comments' => 'required|string',
            'approval_document' => 'sometimes|file|mimes:pdf,docx,doc',
        ]);

        $userId = Auth::id();
        $userRole = Auth::user()->role_id;
        $submission = DocumentSubmission::where('submission_id', $submissionId)->first();

        // Ambil keputusan dari dropdown
        $decision = $request->input('decision');

        if ($decision === 'approve') {
            return $this->approve($request, $submissionId, $userId, $submission, $userRole);
        } else {
            return $this->rejectDocument($request, $submissionId, $userId, $submission);
        }
    }

    private function approve(Request $request, $submissionId, $userId, $submission, $userRole)
    {
        $currentStatusId = $submission->status_id;
        $nextStatusId = $this->getNextStatusId($currentStatusId);

        $submission->update([
            'status_id' => $nextStatusId,
            'updated_at' => Carbon::now(),
        ]);

        $approval = DocumentApproval::create([
            'submission_id' => $submissionId,
            'approver_id' => $userId,
            'status_id' => $nextStatusId,
            'approval_date' => Carbon::now(),
            'comments' => $request->input('comments'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $filePath = $request->file('approval_document')->store('documents/approvals', 'public');

        DocumentFile::where('submission_id', $submissionId)->update([
            'approval_id' => $approval->approval_id
        ]);

        if ($userRole === 'RL002') {
            DocumentFile::create([
                'submission_id' => $submissionId,
                'approval_id' => null,
                'document_type' => 'DT02',
                'uploaded_by' => $userId,
                'file_path' => $filePath,
                'document_desc' => 'Surat Izin Kegiatan',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        } else {
            DocumentFile::create([
                'submission_id' => $submissionId,
                'approval_id' => null,
                'document_type' => $submission->doc_type_id,
                'uploaded_by' => $userId,
                'file_path' => $filePath,
                'document_desc' => 'Dokumen Tanda Tangan',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        return redirect()->route('document-approval.index')->with('success', 'Dokumen berhasil disetujui');
    }

    private function rejectDocument(Request $request, $submissionId, $userId, $submission)
    {
        $currentStatusId = $submission->status_id;
        $reviseStatusId = $this->getReviseStatusId($currentStatusId);

        $submission->update([
            'status_id' => $reviseStatusId,
            'updated_at' => Carbon::now(),
        ]);

        $approval = DocumentApproval::create([
            'submission_id' => $submissionId,
            'approver_id' => $userId,
            'status_id' => $reviseStatusId,
            'approval_date' => Carbon::now(),
            'comments' => $request->input('comments'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        if ($request->hasFile('approval_document')) {
            $filePath = $request->file('approval_document')->store('documents/rejections', 'public');

            DocumentFile::where('submission_id', $submissionId)->update([
                'approval_id' => $approval->approval_id,
            ]);

            DocumentFile::create([
                'submission_id' => $submissionId,
                'approval_id' => $approval->approval_id,
                'document_type' => $submission->doc_type_id,
                'uploaded_by' => $userId,
                'file_path' => $filePath,
                'document_desc' => 'Revisi Dokumen',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        return redirect()->route('document-approval.index', $submissionId)
            ->with('success', 'Dokumen berhasil ditolak.');
    }


    private function getNextStatusId($currentStatusId)
    {
        switch ($currentStatusId) {
            case 1: // Review BEM
                return 2; // Review DPM
            case 2: // Review DPM
                return 3; // Review Binma
            case 3: // Review Binma
                return 4; // Review Dekan
            case 4: // Review Dekan
                return 5; // Review Lemawa
            case 5: // Review Lemawa
                return 16; // Done
            default:
                return $currentStatusId; // Jika tidak sesuai, kembalikan status sekarang
        }
    }

    private function getReviseStatusId($currentStatusId)
    {
        switch ($currentStatusId) {
            case 1: // Review BEM
                return 6; // Revise BEM
            case 2: // Review DPM
                return 7; // Revise DPM
            case 3: // Review Binma
                return 8; // Revise Binma
            case 4: // Review Dekan
                return 9; // Revise Dekan
            case 5: // Review Lemawa
                return 10; // Revise Lemawa
            default:
                return $currentStatusId; // Jika tidak sesuai, kembalikan status sekarang
        }
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
