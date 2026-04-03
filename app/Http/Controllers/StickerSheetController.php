<?php

namespace App\Http\Controllers;

use App\Models\StickerSheet;
use App\Models\SheetConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class StickerSheetController extends Controller
{
    /**
     * Show the dashboard with list of designs.
     */
    public function index()
    {
        return Inertia::render('Dashboard', [
            'sheets' => StickerSheet::where('user_id', Auth::id())->latest()->get()
        ]);
    }

    /**
     * Show the setup screen (Screen 1).
     */
    public function create()
    {
        return Inertia::render('Setup', [
            'configs' => SheetConfig::where('user_id', Auth::id())
                ->orWhereNull('user_id')
                ->latest()
                ->get()
        ]);
    }

    /**
     * Store the layout configuration and redirect to designer.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'paper_unit' => 'required|in:mm,in',
            'paper_width' => 'required|numeric|min:1',
            'paper_height' => 'required|numeric|min:1',
            'sticker_width' => 'required|numeric|min:1',
            'sticker_height' => 'required|numeric|min:1',
            'rows' => 'required|integer|min:1',
            'columns' => 'required|integer|min:1',
            'gap_horizontal' => 'required|numeric|min:0',
            'gap_vertical' => 'required|numeric|min:0',
            'margin_top' => 'required|numeric|min:0',
            'margin_bottom' => 'required|numeric|min:0',
            'margin_left' => 'required|numeric|min:0',
            'margin_right' => 'required|numeric|min:0',
        ]);

        // Validation logic for grid fit
        $paperWidth = $validated['paper_width'];
        $paperHeight = $validated['paper_height'];
        
        $totalWidth = $validated['margin_left'] + 
                      ($validated['columns'] * $validated['sticker_width']) + 
                      (($validated['columns'] - 1) * $validated['gap_horizontal']) + 
                      $validated['margin_right'];

        $totalHeight = $validated['margin_top'] + 
                       ($validated['rows'] * $validated['sticker_height']) + 
                       (($validated['rows'] - 1) * $validated['gap_vertical']) + 
                       $validated['margin_bottom'];

        if ($totalWidth > $paperWidth) {
            return back()->withErrors(['columns' => "Content width ($totalWidth) exceeds paper width ($paperWidth)."]);
        }

        if ($totalHeight > $paperHeight) {
            return back()->withErrors(['rows' => "Content height ($totalHeight) exceeds paper height ($paperHeight)."]);
        }

        $validated['user_id'] = Auth::id();
        $sheet = StickerSheet::create($validated);

        return redirect()->route('designer', $sheet->id);
    }

    /**
     * Show the setup screen for an existing sheet.
     */
    public function edit(StickerSheet $sheet)
    {
        if ($sheet->user_id !== Auth::id()) {
            abort(403);
        }

        return Inertia::render('Setup', [
            'sheet' => $sheet,
            'configs' => SheetConfig::where('user_id', Auth::id())
                ->orWhereNull('user_id')
                ->latest()
                ->get()
        ]);
    }

    /**
     * Update the layout configuration of an existing sheet and redirect to designer.
     */
    public function updateSetup(Request $request, StickerSheet $sheet)
    {
        if ($sheet->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'paper_unit' => 'required|in:mm,in',
            'paper_width' => 'required|numeric|min:1',
            'paper_height' => 'required|numeric|min:1',
            'sticker_width' => 'required|numeric|min:1',
            'sticker_height' => 'required|numeric|min:1',
            'rows' => 'required|integer|min:1',
            'columns' => 'required|integer|min:1',
            'gap_horizontal' => 'required|numeric|min:0',
            'gap_vertical' => 'required|numeric|min:0',
            'margin_top' => 'required|numeric|min:0',
            'margin_bottom' => 'required|numeric|min:0',
            'margin_left' => 'required|numeric|min:0',
            'margin_right' => 'required|numeric|min:0',
        ]);

        // Validation logic for grid fit
        $paperWidth = $validated['paper_width'];
        $paperHeight = $validated['paper_height'];
        
        $totalWidth = $validated['margin_left'] + 
                      ($validated['columns'] * $validated['sticker_width']) + 
                      (($validated['columns'] - 1) * $validated['gap_horizontal']) + 
                      $validated['margin_right'];

        $totalHeight = $validated['margin_top'] + 
                       ($validated['rows'] * $validated['sticker_height']) + 
                       (($validated['rows'] - 1) * $validated['gap_vertical']) + 
                       $validated['margin_bottom'];

        if ($totalWidth > $paperWidth) {
            return back()->withErrors(['columns' => "Content width ($totalWidth) exceeds paper width ($paperWidth)."]);
        }

        if ($totalHeight > $paperHeight) {
            return back()->withErrors(['rows' => "Content height ($totalHeight) exceeds paper height ($paperHeight)."]);
        }

        $sheet->update($validated);

        return redirect()->route('designer', $sheet->id);
    }

    /**
     * Show the designer screen (Screen 2).
     */
    public function show(StickerSheet $sheet)
    {
        if ($sheet->user_id !== Auth::id()) {
            abort(403);
        }

        return Inertia::render('Designer', [
            'sheet' => $sheet
        ]);
    }

    /**
     * Update the sticker design.
     */
    public function update(Request $request, StickerSheet $sheet)
    {
        if ($sheet->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'design_data' => 'nullable|array',
            'name' => 'nullable|string|max:255',
        ]);

        $sheet->update($validated);

        return back();
    }

    /**
     * Delete a sticker sheet.
     */
    public function destroy(StickerSheet $sheet)
    {
        if ($sheet->user_id !== Auth::id()) {
            abort(403);
        }
        $sheet->delete();
        return redirect()->route('dashboard');
    }

    /**
     * Duplicate a sticker sheet.
     */
    public function duplicate(StickerSheet $sheet)
    {
        if ($sheet->user_id !== Auth::id()) {
            abort(403);
        }

        $newSheet = $sheet->replicate();
        $newSheet->user_id = Auth::id();
        $newSheet->name = $sheet->name ? $sheet->name . ' (Copy)' : 'Copy of ' . $sheet->created_at->format('Y-m-d');
        $newSheet->created_at = now();
        $newSheet->updated_at = now();
        $newSheet->save();

        return redirect()->route('dashboard');
    }

    /**
     * Show the preview/print screen (Screen 3).
     */
    public function preview(StickerSheet $sheet)
    {
        if ($sheet->user_id !== Auth::id()) {
            abort(403);
        }

        return Inertia::render('Preview', [
            'sheet' => $sheet
        ]);
    }
}
