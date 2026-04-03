<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class LanguageController extends Controller
{
    /**
     * Switch the application language.
     */
    public function switch(Request $request)
    {
        $request->validate([
            'locale' => 'required|in:en,cs,vi',
        ]);

        Session::put('locale', $request->locale);

        return back();
    }
}
