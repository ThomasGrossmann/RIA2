<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LabelDetectorController extends Controller
{
    public function analyze(Request $request)
    {
        // TODO: Implement analyze method with correspoding API calls

        return response()->json(['message' => 'Analyze route from labelDetector service']);
    }
}
