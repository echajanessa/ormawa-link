<?php

namespace App\Http\Controllers;

use App\Models\DocumentSubmission;
use App\Models\DocumentStatus;
use App\Models\DocumentType;
use App\Models\Supervisor;
use Illuminate\Http\Request;
use App\Models\DocumentFile;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\View\Components\Alert;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DocumentSubmissionController extends Controller
{

    public function create()
    {
        return view('content.document-proposal', ['mode' => 'new']);
    }

    public function editProposal($submissionId)
    {
        $submission = DocumentSubmission::findOrFail($submissionId);
        return view('content.document-proposal', [
            'mode' => 'revision',
            'submission' => $submission // Pass the existing submission data
        ]);
    }

    public function editLpj($submissionId)
    {
        $submission = DocumentSubmission::findOrFail($submissionId);
        return view('content.document-lpj', [
            'mode' => 'revision',
            'submission' => $submission // Pass the existing submission data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Log::info('Data yang diterima:', $request->all());

        //ini untuk validasi input
        $request->validate([
            'event_name' => 'required|string|max:255',
            'organization' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'location' => 'required|string|max:255',
            'project_leader' => 'required|string|max:255',
            'proposal_document' => 'required|file|mimes:pdf,doc,docx',
            'letter_document' => 'required|file|mimes:pdf,doc,docx',
        ]);

        $userId = Auth::id(); // ngecek user yang login
        $supervisor = Supervisor::where('user_id', $userId)->first();
        $statusId = $this->getStatusIdFromSupervisor($supervisor);

        // Ambil tipe dokumen
        $documentTypeCode = $request->doc_type_id == 'DT01' ? '10' : '20'; // DT01: Proposal, DT05: LPJ
        $submissionDate = Carbon::now()->format('dmy'); // Format tanggal DDMMYY

        // Ambil nomor increment terakhir untuk tanggal tersebut
        $lastSubmission = DocumentSubmission::whereDate('created_at', Carbon::today())
            ->where('doc_type_id', $request->doc_type_id)
            ->orderBy('submission_id', 'desc')
            ->first();

        // Tentukan increment berikutnya
        $increment = 1; // Default jika tidak ada record sebelumnya
        if ($lastSubmission) {
            $lastIncrement = (int) substr($lastSubmission->submission_id, -2); // Ambil 2 digit terakhir
            $increment = $lastIncrement + 1;
        }

        // Format menjadi 2 digit
        $increment = str_pad($increment, 2, '0', STR_PAD_LEFT);

        // Gabungkan semua bagian menjadi submission_id
        $submissionId = $documentTypeCode . $submissionDate . $increment;

        try {
            //ini untuk simpan nilai dari pengajuan
            $submission = new DocumentSubmission();
            $submission->submission_id = $submissionId;
            $submission->doc_type_id = $request->input('doc_type_id');
            $submission->user_id = $userId;
            $submission->status_id = $statusId;
            $submission->event_name = $request->input('event_name');
            $submission->project_leader = $request->input('project_leader');
            $submission->start_date = $request->input('start_date');
            $submission->end_date = $request->input('end_date');
            $submission->location = $request->input('location');
            $submission->organization = $request->input('organization');
            $submission->created_at = Carbon::now();
            $submission->updated_at = Carbon::now();
            $submission->save(); // Simpan data submission

            // Ambil submission_id setelah data disimpan
            $submissionId = $submission->submission_id;

            // Simpan file proposal
            if ($request->hasFile('proposal_document')) {
                $proposalFilePath = $request->file('proposal_document')->store('documents/proposals', 'public');

                $submission->files()->create([
                    'approval_id' => null,
                    'document_type' => $request->input('doc_type_id'),
                    'uploaded_by' => $userId,
                    'file_path' => $proposalFilePath,
                    'document_desc' => 'Dokumen',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            // Simpan file surat pengantar
            if ($request->hasFile('letter_document')) {
                $letterFilePath = $request->file('letter_document')->store('documents/letters', 'public');

                $submission->files()->create([
                    'approval_id' => null,
                    'document_type' => 'DT03',
                    'uploaded_by' => $userId,
                    'file_path' => $letterFilePath,
                    'document_desc' => 'Surat Pengantar',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
            // return redirect()->route('document-tracking.index')->with('success', 'Pengajuan berhasil disimpan.');
            return back()->with('success', 'Pengajuan berhasil diproses, silakan lihat pada halaman pemantauan proses');
        } catch (\Exception $e) {
            Log::error('Error saat menyimpan data pengajuan: ' . $e->getMessage());
            return redirect()->route('submission-proposal.create')->with('error', 'Terjadi kesalahan saat menyimpan pengajuan.');
        }

        // Setelah melakukan validasi
        Log::info('Validasi berhasil, menyimpan data ke database.');

        // Setelah pengunggahan file
        if ($request->hasFile('proposal_document')) {
            Log::info('Proposal document uploaded successfully: ' . $request->file('proposal_document')->getClientOriginalName());
        }
    }

    // Method untuk mendapatkan status ID berdasarkan supervisor
    private function getStatusIdFromSupervisor($supervisor)
    {
        if ($supervisor) {
            $spv = $supervisor->spv_id; //nentuin spv id dari user yg ngajuin
            $user = User::find($spv); //cari data spv di tabel Users
            $spvRole = $user->role_id; //cari role spv

            // Cek status berdasarkan role supervisor
            if ($spvRole == 'RL007') {
                return DocumentStatus::where('status_description', 'Review BEM')->first()->status_id;
            } elseif ($spvRole == 'RL006') {
                return DocumentStatus::where('status_description', 'Review DPM')->first()->status_id;
            } elseif ($spvRole == 'RL005') {
                return DocumentStatus::where('status_description', 'Review BINMA')->first()->status_id;
            } elseif ($spvRole == 'RL004') {
                return DocumentStatus::where('status_description', 'Review Dekan')->first()->status_id;
            } elseif ($spvRole == 'RL002') {
                return DocumentStatus::where('status_description', 'Review Lemawa')->first()->status_id;
            }
        }

        // Jika tidak ada supervisor atau role tidak terdefinisi
        return DocumentStatus::where('status_description', 'Review Lemawa')->first()->status_id;
    }

    public function reUploadLpj(Request $request, $submissionId)
    {
        $request->validate([
            'proposalLpj_document' => 'required|file|mimes:pdf,docx,doc',
        ]);

        $submission = DocumentSubmission::findOrFail($submissionId);
        $userId = Auth::id();

        try {
            if ($request->hasFile('proposalLpj_document')) {
                $proposalLpjFilePath = $request->file('proposalLpj_document')->store('document/revisions', 'public');
                DocumentFile::create([
                    'submission_id' => $submissionId,
                    'approval_id' => null,
                    'document_type' => $submission->doc_type_id,
                    'uploaded_by' => $userId,
                    'file_path' => $proposalLpjFilePath,
                    'document_desc' => 'Revisi Dokumen',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            // Update the status to the appropriate review status based on last approver's decision
            $submission->update([
                'status_id' => $this->getReviseStatusId($submission->status_id),
                'updated_at' => now(),
            ]);

            return redirect()->route('document-tracking.index', $submissionId)->with('success', 'Documents have been successfully re-uploaded.');
        } catch (\Exception $e) {
            Log::error('Error during document re-upload: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error occurred during document re-upload.');
        }
    }

    public function reUploadProposal(Request $request, $submissionId)
    {
        $request->validate([
            'proposal_document' => 'required|file|mimes:pdf,docx,doc',
        ]);

        $submission = DocumentSubmission::findOrFail($submissionId);
        $userId = Auth::id();

        try {
            if ($request->hasFile('proposal_document')) {
                $proposalLpjFilePath = $request->file('proposal_document')->store('document/revisions', 'public');
                DocumentFile::create([
                    'submission_id' => $submissionId,
                    'approval_id' => null,
                    'document_type' => $submission->doc_type_id,
                    'uploaded_by' => $userId,
                    'file_path' => $proposalLpjFilePath,
                    'document_desc' => 'Revisi Dokumen',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            // Update the status to the appropriate review status based on last approver's decision
            $submission->update([
                'status_id' => $this->getReviseStatusId($submission->status_id),
                'updated_at' => now(),
            ]);

            return redirect()->route('document-tracking.index', $submissionId)->with('success', 'Documents have been successfully re-uploaded.');
        } catch (\Exception $e) {
            Log::error('Error during document re-upload: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error occurred during document re-upload.');
        }
    }

    private function getReviseStatusId($currentStatusId)
    {
        switch ($currentStatusId) {
            case 6: // Revise BEM
                return 1; // Review BEM
            case 7: // Revise DPM
                return 2; // Review DPM
            case 8: // Revise Binma
                return 3; // Review Binma
            case 9: // Revise Dekan
                return 4; // Review Dekan
            case 10: // Revise Lemawa
                return 5; // Review Lemawa
            default:
                return $currentStatusId; // Return the current status if not matched
        }
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
