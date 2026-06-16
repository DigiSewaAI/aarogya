<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Symptom;

class SymptomCheckerController extends Controller
{
    public function index()
    {
        $symptoms = Symptom::all();
        return view('symptom-checker', compact('symptoms'));
    }

    public function check(Request $request)
    {
        $selectedSymptoms = $request->input('symptoms');
        // AI Logic yaha add garne - aaja ko laagi simple
        return view('results', ['results' => $this->getPossibleDiseases($selectedSymptoms)]);
    }

    private function getPossibleDiseases($symptoms)
    {
        // Temporary simple logic - pachi AI integrate garne
        return [
            'Common Cold' => '70% match',
            'Viral Fever' => '65% match', 
            'Allergy' => '50% match'
        ];
    }
}