<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentSubmission;
use App\Models\DocumentApproval;
use App\Models\DocumentRegulation;


class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('query');

        // Pencarian sederhana berdasarkan kolom nama atau judul
        $results = DocumentSubmission::where('start_date', 'LIKE', "%$query%")
            ->orWhere('end_date', 'LIKE', "%$query%")
            ->orWhere('submission_id', 'LIKE', "%$query%")
            ->orWhere('event_name', 'LIKE', "%$query%")
            ->orWhere('doc_type_id', 'LIKE', "%$query%")
            ->orWhere('status_id', 'LIKE', "%$query%")
            ->get();

        return response()->json(['results' => $results]);
    }
}
