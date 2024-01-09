<?php

require_once __DIR__ . '/vendor/autoload.php';

callApi();

function callApi()
{
    $env = parse_ini_file('.env');
    $bucketName = $env['BUCKET_NAME'];
    $bucketUri = $env['BUCKET_URI'];
    $labelDetector = new App\LabelDetectorImpl();
    $dataObject = new App\GoogleDataObjectImpl($bucketName);

    $objectUri = $bucketUri . '/sample.jpeg';
    $localFile = 'images/sample.jpeg';
    $sqlObjectUri = $bucketUri . '/labels.sql';
    $sqlFile = 'labels.sql';

    clearBucket($dataObject);

    if (!$dataObject->doesExist($objectUri)) {
        $dataObject->upload($localFile, $objectUri);
    }

    $signedUrl = $dataObject->publish($objectUri);
    $response = $labelDetector->analyze($signedUrl);

    $sql = "INSERT INTO `labels` (`description`, `confidenceLevel`) VALUES ";
    $values = [];
    foreach ($response['metrics'] as $metric) {
        $values[] = "('" . $metric['description'] . "', " . $metric['confidenceLevel'] . ")";
    }
    $sql .= implode(',', $values) . ';';
    file_put_contents($sqlFile, $sql);

    $dataObject->upload($sqlFile, $sqlObjectUri);

    if ($dataObject->doesExist($sqlObjectUri)) {
        echo 'Success!';
    } else {
        echo 'Failure!';
    }
}

function clearBucket($dataObject)
{
    $objectsToClear = $dataObject->bucket->objects();
    foreach ($objectsToClear as $object) {
        $object->delete();
    }
}
