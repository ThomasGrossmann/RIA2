<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\LabelDetectorImpl;
use Exception;

class LabelDetectorTest extends TestCase
{
    private $labelDetector;
    private $localFile;
    private $remoteFileUrl;

    protected function setUp(): void
    {
        $this->labelDetector = new LabelDetectorImpl();
        $this->localFile = 'images/sample.jpeg';
        $this->remoteFileUrl = 'https://www.admin.ch/gov/de/start/departemente/departement-fuer-auswaertige-angelegenheiten-eda/_jcr_content/par/image/image.imagespooler.jpg/1611330706364/Cassis.jpg';
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

        $this->assertTrue($response->amountOfLabels <= 10);
        foreach ($response->metrics as $metric) {
            $this->assertTrue($metric->confidenceLevel >= 90);
        }
    }

    public function testAnalyzeRemoteImageWithCustomMaxLabelValueImageAnalyzed()
    {
        $maxLabels = 5;

        $response = $this->labelDetector->analyze($this->remoteFileUrl, $maxLabels);

        $this->assertTrue($response->amountOfLabels <= $maxLabels);
        foreach ($response->metrics as $metric) {
            $this->assertTrue($metric->confidenceLevel >= 50);
        }
    }

    public function testAnalyzeRemoteImageWithCustomMinConfidenceLevelValueImageAnalyzed()
    {
        $minConfidenceLevel = 60;

        $response = $this->labelDetector->analyze($this->remoteFileUrl, $minConfidenceLevel);

        $this->assertTrue($response->amountOfLabels <= 10);
        foreach ($response->metrics as $metric) {
            $this->assertTrue($metric->confidenceLevel >= $minConfidenceLevel);
        }
    }

    public function testAnalyzeRemoteImageWithCustomValuesImageAnalyzed()
    {
        $maxLabels = 5;
        $minConfidenceLevel = 60;

        $response = $this->labelDetector->analyze($this->remoteFileUrl, $maxLabels, $minConfidenceLevel);

        $this->assertTrue($response->amountOfLabels <= $maxLabels);
        foreach ($response->metrics as $metric) {
            $this->assertTrue($metric->confidenceLevel >= $minConfidenceLevel);
        }
    }
}
