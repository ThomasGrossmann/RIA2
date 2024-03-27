<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiGatewayController extends Controller
{
    public function analyze(Request $request)
    {
        $image = $request->file('image');
        $maxLabels = $request->input('maxLabels');
        $minConfidence = $request->input('minConfidence');
        
        if (!$image) {
            return response()->json([
                'error' => 'No image provided'
            ], 400);
        }
        $doesExist = Http::attach('image', $image)->post('http://127.0.0.1:5002/api/doesExist');
        return response()->json($doesExist->json(), 200);

        if ($doesExist->json() === false) {
            Http::attach('image', $image)->post('http://127.0.0.1:5002/api/upload');
        }
        $url = Http::attach('image', $image)->post('http://127.0.0.1:5002/api/publish');
        return $url->json();

        // $results = Http::post('http://127.0.0.1:5001/api/analyze', [
        //     'remoteFullPath' => $urlData,
        //     'maxLabels' => $maxLabels,
        //     'minConfidence' => $minConfidence
        // ]);

        // return response()->json($results->json(), 200);
    }
}
