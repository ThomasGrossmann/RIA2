<?php

namespace App;

use Google\Cloud\Vision\V1\ImageAnnotatorClient;

class LabelDetectorImpl implements ILabelDetector
{
    private $client;
    private $metrics = [];
    private $amountOfLabels;

    public function __construct(string $credentialsPath)
    {
        $this->client = new ImageAnnotatorClient([
            'credentials' => $credentialsPath
        ]);
    }

    public function analyze(string $remoteFullPath, int $maxLabels = 10, int $minConfidenceLevel = 90): array
    {
        $image = file_get_contents($remoteFullPath);
        $response = $this->client->labelDetection($image);
        $labels = $response->getLabelAnnotations();

        foreach ($labels as $label) {
            if (count($this->metrics) >= $maxLabels) {
                break;
            }
            if ($label->getScore() * 100 >= $minConfidenceLevel) {
                $this->metrics[] = [
                    "description" => $label->getDescription(),
                    "confidenceLevel" => $label->getScore() * 100
                ];
            }
        }
        $this->amountOfLabels = count($this->metrics);

        return [
            "metrics" => $this->metrics,
            "amountOfLabels" => $this->amountOfLabels
        ];
    }
}
