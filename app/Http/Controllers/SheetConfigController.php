<?php

namespace App\Http\Controllers;

use App\Models\SheetConfig;
use Illuminate\Http\Request;

class SheetConfigController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
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

        SheetConfig::create($validated);

        return back()->with('success', 'Configuration saved successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SheetConfig $config)
    {
        $config->delete();

        return back()->with('success', 'Configuration deleted successfully!');
    }
}
