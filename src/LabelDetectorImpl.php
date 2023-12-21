<?php
require __DIR__ . '/ILabelDetector.php';

use Google\Cloud\Vision\V1\ImageAnnotatorClient;

class LabelDetectorImpl implements ILabelDetector
{
    private $client;
    private $metrics;
    private $amountOfLabels;

    public function __construct()
    {
        $env = parse_ini_file('.env');
        $this->client = new ImageAnnotatorClient([
            'credentials' => $env['CREDENTIALS_PATH']
        ]);
    }

    public function analyze($remoteFullPath, $maxLabels = 10, $minConfidenceLevel = 0.9): array
    {
        $image = file_get_contents($remoteFullPath);
        $response = $this->client->labelDetection($image);
        $labels = $response->getLabelAnnotations();

        foreach ($labels as $label) {
            $this->metrics = [
                "description" => $label->getDescription(),
                "confidenceLevel" => $label->getScore()
            ];
        }

        return [
            "amountOfLabels" => $this->amountOfLabels,
            "metrics" => $this->metrics
        ];
    }
}
