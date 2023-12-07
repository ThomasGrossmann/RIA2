<?php

require __DIR__ . '/LabelDetectorImpl.php';

$labelDetector = new LabelDetectorImpl();
$labels = $labelDetector->analyze('images/sample.jpeg');
echo json_encode($labels);
