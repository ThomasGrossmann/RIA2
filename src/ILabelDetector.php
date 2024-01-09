<?php

interface ILabelDetector
{
    public function analyze($remoteFullPath, $maxLabels = 10, $minConfidenceLevel = 90): array;
}
