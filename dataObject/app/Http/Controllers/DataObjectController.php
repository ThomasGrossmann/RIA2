<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GoogleDataObjectImpl;
use Illuminate\Support\Facades\Http;

class DataObjectController extends Controller
{
    private $dataObject;
    private $bucketUri;
    private $bucketName;
    private $crendentialsPath;

    public function __construct()
    {
        $this->crendentialsPath = env('CREDENTIALS_PATH');
        $this->bucketName = env('BUCKET_NAME');
        $this->bucketUri = env('BUCKET_URI');
        $this->dataObject = new GoogleDataObjectImpl($this->bucketName, $this->crendentialsPath);
    }

    public function doesExist(Request $request)
    {
        $image = $request->file('image');
        if (!$image) {
            return response()->json([
                'error' => 'No image provided'
            ], 400);
        }

        $object = $this->bucketUri . '/' . $image->getClientOriginalName();
        $exists = $this->dataObject->doesExist($object);

        return response()->json([
            'doesExist' => $exists
        ], 200);
    }

    public function upload(Request $request)
    {
        $image = $request->file('image');
        if (!$image) {
            return response()->json([
                'error' => 'No image provided'
            ], 400);
        }

        $object = $this->bucketUri . '/' . $image->getClientOriginalName();
        $this->dataObject->upload($image, $object);

        return response()->json([
            'message' => $image->getClientOriginalName() . ' uploaded'
        ]);
    }

    public function publish(Request $request)
    {
        $image = $request->file('image');
        return response()->json($image->getClientOriginalName());
        
        if (!$image) {
            return response()->json([
                'error' => 'No image provided'
            ], 400);
        }

        $object = $this->bucketUri . '/' . $image->getClientOriginalName();
        $url = $this->dataObject->publish($object);
        return response()->json($url);
    }
}
