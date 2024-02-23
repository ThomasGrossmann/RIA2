<?php

namespace App;

interface ILabelDetector
{
    public function analyze(string $remoteFullPath, int $maxLabels = 10, int $minConfidenceLevel = 90): array;
}
