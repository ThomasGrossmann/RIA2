<?php

require __DIR__ . '/vendor/autoload.php';

$env = parse_ini_file('.env');
$bucketUri = $env['BUCKET_URI'];
$bucketName = $env['BUCKET_NAME'];

$dataObject = new App\GoogleDataObjectImpl($bucketName);
$labelDetector = new App\LabelDetectorImpl();

$objectUri = $bucketUri . '/sample.jpeg';
$localFile = 'images/sample.jpeg';
$sqlFile = 'labels_' . date('Ymd_His') . '.sql';
$sqlObjectUri = $bucketUri . '/' . $sqlFile;

$dataObject->apiCall($objectUri, $localFile);

$signedUrl = $dataObject->publish($objectUri);
$response = json_decode(json_encode($labelDetector->analyze($signedUrl)));

$sql = "INSERT INTO `labels` (`description`, `confidence_level`) VALUES ";
$values = [];
foreach ($response->metrics as $metric) {
    $values[] = "('" . $metric->description . "', " . $metric->confidenceLevel . ")";
}
$sql .= implode(',', $values) . ';';
file_put_contents($sqlFile, $sql);

$dataObject->upload($sqlFile, $sqlObjectUri);
