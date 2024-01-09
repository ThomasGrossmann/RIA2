<?php

require __DIR__ . '/../src/LabelDetectorImpl.php';

use PHPUnit\Framework\TestCase;

class LabelDetectorTest extends TestCase
{
    private $labelDetector;
    private $localFile;
    private $remoteFileUrl;

    protected function setUp(): void
    {
        $this->labelDetector = new LabelDetectorImpl();
        $this->localFile = 'images/sample.jpeg';
    }

    public function testAnalyzeLocalFileWithDefaultValuesImageAnalyzed()
    {
        $this->assertTrue(file_exists($this->localFile));

        $response = $this->labelDetector->analyze($this->localFile);

        $this->assertTrue($response->amountOfLabels <= 10);
        foreach ($response->metrics as $metric) {
            $this->assertTrue($metric->confidenceLevel >= 90);
        }
    }

    public function testAnalyzeRemoteImageWithDefaultValuesImageAnalyzed()
    {
        $response = $this->labelDetector->analyze($this->remoteFileUrl);

        $this->assertTrue(count($response->amountOfLabels) <= 10);
        foreach ($response->metrics as $metric) {
            $this->assertTrue($metric->confidenceLevel >= 90);
        }
    }

    public function testAnalyzeRemoteImageWithCustomMaxLabelValueImageAnalyzed()
    {
        $maxLabels = 5;

        $response = $this->labelDetector->analyze($this->remoteFileUrl, $maxLabels);

        $this->assertTrue(count($response->amountOfLabels) <= $maxLabels);
        foreach ($response->metrics as $metric) {
            $this->assertTrue($metric->confidenceLevel >= 50);
        }
    }

    public function testAnalyzeRemoteImageWithCustomMinConfidenceLevelValueImageAnalyzed()
    {
        $minConfidenceLevel = 60;

        $response = $this->labelDetector->analyze($this->remoteFileUrl, $minConfidenceLevel);

        $this->assertTrue(count($response->amountOfLabels) <= 10);
        foreach ($response->metrics as $metric) {
            $this->assertTrue($metric->confidenceLevel >= $minConfidenceLevel);
        }
    }

    public function testAnalyzeRemoteImageWithCustomValuesImageAnalyzed()
    {
        $maxLabels = 5;
        $minConfidenceLevel = 60;

        $response = $this->labelDetector->analyze($this->remoteFileUrl, $maxLabels, $minConfidenceLevel);
        // TODO: the type of response contains the payload (returned in json by the api)

        $this->assertTrue(count($response->amountOfLabels) <= $maxLabels);
        foreach ($response->metrics as $metric) {
            $this->assertTrue($metric->confidenceLevel >= $minConfidenceLevel);
        }
    }
}
