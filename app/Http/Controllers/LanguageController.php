<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function switch(Request $request)
    {
        $locale = $request->input('locale');

        // Only allow 'en' or 'ne'
        if (!in_array($locale, ['en', 'ne'])) {
            $locale = 'ne'; // default
        }

        session(['locale' => $locale]);

        return redirect()->back();
    }
}