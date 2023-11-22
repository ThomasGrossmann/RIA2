<?php
require __DIR__ . '/vendor/autoload.php';

use Google\Cloud\Vision\V1\ImageAnnotatorClient;

$env = parse_ini_file('.env');
$client = new ImageAnnotatorClient(
    ['credentials' => $env['CREDENTIALS_PATH']]
);

$path = 'images/sample.jpeg';
$imageData = file_get_contents($path);
$response = $client->faceDetection($imageData);

echo (json_encode($response->serializeToJsonString()));
