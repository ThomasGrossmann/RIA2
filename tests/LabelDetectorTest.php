<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\LabelDetectorImpl;

class LabelDetectorTest extends TestCase
{
    private static $labelDetectorInstance;
    private $labelDetector;
    private $localFile;
    private $remoteFileUrl;
    private $maxLabels;
    private $minConfidenceLevel;

    public static function setUpBeforeClass(): void
    {
        $env = parse_ini_file('.env');
        $credentialsPath = $env['LABELDETECTOR_CREDENTIALS_PATH'];
        self::$labelDetectorInstance = new LabelDetectorImpl($credentialsPath);
    }

    protected function setUp(): void
    {
        $this->labelDetector = self::$labelDetectorInstance;

        $this->localFile = 'images/sample.jpeg';
        $this->remoteFileUrl = 'https://www.admin.ch/gov/de/start/departemente/departement-fuer-auswaertige-angelegenheiten-eda/_jcr_content/par/image/image.imagespooler.jpg/1611330706364/Cassis.jpg';
        $this->maxLabels = 5;
        $this->minConfidenceLevel = 60;
    }

    public function testAnalyzeLocalFileWithDefaultValuesImageAnalyzed()
    {
        // given
        $this->assertTrue(file_exists($this->localFile));

        // when
        $response = $this->labelDetector->analyze($this->localFile);

        // then
        $this->assertTrue($response->amountOfLabels <= 10);
        foreach ($response->metrics as $metric) {
            $this->assertTrue($metric->confidenceLevel >= 90);
        }
    }

    public function testAnalyzeRemoteImageWithDefaultValuesImageAnalyzed()
    {
        // given

        // when
        $response = $this->labelDetector->analyze($this->remoteFileUrl);

        // then
        $this->assertTrue($response->amountOfLabels <= 10);
        foreach ($response->metrics as $metric) {
            $this->assertTrue($metric->confidenceLevel >= 90);
        }
    }

    public function testAnalyzeRemoteImageWithCustomMaxLabelValueImageAnalyzed()
    {
        // given

        // when
        $response = $this->labelDetector->analyze($this->remoteFileUrl, $this->maxLabels);

        // then
        $this->assertTrue($response->amountOfLabels <= $this->maxLabels);
        foreach ($response->metrics as $metric) {
            $this->assertTrue($metric->confidenceLevel >= 50);
        }
    }

    public function testAnalyzeRemoteImageWithCustomMinConfidenceLevelValueImageAnalyzed()
    {
        // given

        // when
        $response = $this->labelDetector->analyze($this->remoteFileUrl, $this->minConfidenceLevel);

        // then
        $this->assertTrue($response->amountOfLabels <= 10);
        foreach ($response->metrics as $metric) {
            $this->assertTrue($metric->confidenceLevel >= $this->minConfidenceLevel);
        }
    }

    public function testAnalyzeRemoteImageWithCustomValuesImageAnalyzed()
    {
        // given

        // when
        $response = $this->labelDetector->analyze($this->remoteFileUrl, $this->maxLabels, $this->minConfidenceLevel);

        // then
        $this->assertTrue($response->amountOfLabels <= $this->maxLabels);
        foreach ($response->metrics as $metric) {
            $this->assertTrue($metric->confidenceLevel >= $this->minConfidenceLevel);
        }
    }
}
