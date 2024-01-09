<?php

require_once __DIR__ . '/vendor/autoload.php';

$env = parse_ini_file('.env');
$bucketName = $env['BUCKET_NAME'];
$bucketUri = $env['BUCKET_URI'];

$labelDetector = new App\LabelDetectorImpl();
$dataObject = new App\GoogleDataObjectImpl($bucketName);

$objectUri = $bucketUri . '/sample.jpeg';
$localFile = 'images/sample.jpeg';

clearBucket($dataObject);

if (!$dataObject->doesExist($objectUri)) {
    $dataObject->upload($localFile, $objectUri);
}

$signedUrl = $dataObject->publish($objectUri);

$response = $labelDetector->analyze($signedUrl);

echo json_encode($response);

function clearBucket($dataObject)
{
    $objectsToClear = $dataObject->bucket->objects();
    foreach ($objectsToClear as $object) {
        $object->delete();
    }
}
