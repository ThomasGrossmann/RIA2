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
        $env = parse_ini_file('.env.labelDetector');
        $credentialsPath = $env['CREDENTIALS_PATH'];
        self::$labelDetectorInstance = new LabelDetectorImpl($credentialsPath);
    }

    protected function setUp(): void
    {
        $this->labelDetector = self::$labelDetectorInstance;

        $this->localFile = 'images/sample.jpeg';
        $this->remoteFileUrl = 'https://www.elysee.fr/cdn-cgi/image/width=1520%2Cheight=2534/images/default/0001/13/22e85bb25185f2f19748178a2f46713c11a32913.jpg';
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
