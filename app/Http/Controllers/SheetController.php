<?php

namespace App\Http\Controllers;

use App\Models\Sheet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SheetController extends Controller
{
    public function index()
    {
        $sheets = Sheet::where('company_id', Auth::user()->company_id)->get();
        return view('sheets.index', compact('sheets'));
    }

    public function store(Request $request)
    {
        try{
            $request->validate([
                'name' => 'required|string|max:255',
                'headers' => 'required|array',
                'headers.*' => 'required|string|max:255',
            ]);

            Sheet::create([
                'company_id' => Auth::user()->company_id,
                'name' => $request->input('name'),
                'headers' => $request->input('headers'),
                'data' => [],
            ]);
        } catch (\Exception $e) {
            return redirect()->route('sheets.index')->with('error', 'Failed to create sheet. Please try again.');
        }

        return redirect()->route('sheets.index')->with('success', 'Sheet created successfully!');
    }

    public function show(Sheet $sheet)
    {
        if ($sheet->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        return view('sheets.show', compact('sheet'));
    }

    public function updateData(Request $request, Sheet $sheet)
    {
        if ($sheet->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        $request->validate([
            'data' => 'required|array',
        ]);

        $sheet->update(['data' => $request->data]);

        return response()->json(['success' => true]);
    }

    public function destroy(Sheet $sheet)
    {
        if ($sheet->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        $sheet->delete();

        return redirect()->route('sheets.index')->with('success', 'Sheet deleted successfully!');
    }

    public function showClearConfirmation(Sheet $sheet)
    {
        if ($sheet->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        $rowCount = count($sheet->data ?? []);
        $columnCount = count($sheet->headers ?? []);
        
        \Log::info('Clear confirmation requested', [
            'sheet_id' => $sheet->id,
            'row_count' => $rowCount,
            'column_count' => $columnCount
        ]);
        
        return view('sheets.partials.clear-confirmation-modal', compact('sheet', 'rowCount', 'columnCount'));
    }

    public function clear(Sheet $sheet)
    {
        if ($sheet->company_id !== Auth::user()->company_id) {
            abort(403);
        }

        \Log::info('Clearing sheet data', [
            'sheet_id' => $sheet->id,
            'previous_data_count' => count($sheet->data ?? [])
        ]);

        // Clear the data
        $sheet->update(['data' => []]);

        // Return the empty table body for HTMX to swap
        return view('sheets.partials.empty-table-body', compact('sheet'));
    }
}
