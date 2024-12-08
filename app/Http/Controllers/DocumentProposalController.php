<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentSubmission;
use App\Models\DocumentFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentProposalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('content.document-proposal');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //validasi input
        $request->validate([
            'event_name' => 'required|string',
            'organization' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'project_leader' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'proposal_doc' => 'required|file|mimes:pdf,doc,docx|max:2048',
            'letter_doc' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);
        //simpan data pengajuan
        $submission = DocumentProposalController::create([
            'doc_type_id' => 'DT01',
            'user_id' => Auth::id()
        ]);
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
