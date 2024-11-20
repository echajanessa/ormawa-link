<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DocumentRegulation;
use Carbon\Carbon;
use League\CommonMark\Renderer\Block\DocumentRenderer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Else_;

class RegulationManagementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $regulations = DocumentRegulation::paginate(10);

        return view('content.regulation-management', ['regulations' => $regulations]);
    }

    public function dashboard()
    {
        $regulations = DocumentRegulation::all();

        return view('content.dashboard.dashboards-analytics', compact('regulations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'announcement_title' => 'required|max:255',
            'announcement_description' => 'required'
        ]);

        $userId = Auth::id();

        $lastRegulation = DocumentRegulation::orderBy('regulation_id', 'desc')->first();
        if ($lastRegulation) {
            $lastNumber = intval(substr($lastRegulation->regulation_id, 3));
            $newId = 'REG' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
        } else {
            $newId = 'REG001';
        }

        DocumentRegulation::create([
            'regulation_id' => $newId,
            'announcement_title' => $request->input('announcement_title'),
            'announcement_description' => $request->input('announcement_description'),
            'user_id' => $userId,
        ]);
        return redirect()->route('regulation-management.index')->with('success', 'Regulasi berhasil disimpan.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $regulation = DocumentRegulation::findOrFail($id);

        return view('content.regulation-management', compact('regulation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'announcement_title' => 'required|string|max:255',
            'announcement_description' => 'required|string'
        ]);

        $regulation = DocumentRegulation::findOrFail($id);
        // Update data regulasi
        $regulation->announcement_title = $request->announcement_title;
        $regulation->announcement_description = $request->announcement_description;
        $regulation->save();

        return redirect()->route('regulation-management.index')->with('success', 'Regulasi berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $regulation = DocumentRegulation::findOrFail($id);
        $regulation->delete();

        return redirect()->route('regulation-management.index')->with('success', 'Regulasi berhasil dihapus');
    }
}
