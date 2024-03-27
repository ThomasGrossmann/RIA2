<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LabelDetectorImpl;
use Illuminate\Support\Facades\Http;

class LabelDetectorController extends Controller
{
    private $labelDetector;

    public function __construct()
    {
        $crendentialsPath = env('CREDENTIALS_PATH');
        $this->labelDetector = new LabelDetectorImpl($crendentialsPath);
    }

    public function analyze(Request $request)
    {
        $remoteFullPath = $request->input('remoteFullPath');
        $maxLabels = $request->input('maxLabels');
        $minConfidenceLevel = $request->input('minConfidenceLevel');

        $result = $this->labelDetector->analyze($remoteFullPath, $maxLabels, $minConfidenceLevel);
        return response()->json($result, 200);
    }
}
