<?php
require __DIR__ . '/vendor/autoload.php';

use Google\Cloud\Vision\V1\ImageAnnotatorClient;

class ImageAnalyzer
{
    private $client;
    private $detectionCondifence;
    private $landmarkingConfidence;

    public function __construct()
    {
        $env = parse_ini_file('.env');
        $this->client = new ImageAnnotatorClient(
            ['credentials' => $env['CREDENTIALS_PATH']]
        );
    }

    public function detectFace($path)
    {
        $imageData = file_get_contents($path);
        $response = $this->client->faceDetection($imageData);
        $faces = $response->getFaceAnnotations();

        foreach ($faces as $face) {
            $this->detectionCondifence = $face->getDetectionConfidence();
            $this->landmarkingConfidence = $face->getLandmarkingConfidence();
        }
    }
}

$faceDetection = new ImageAnalyzer();
$faceDetection->detectFace('images/sample.jpeg');
