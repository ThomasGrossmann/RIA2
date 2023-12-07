<?php

interface IDataObject
{
    public function doesObjectExist();
    public function uploadObject($bytes, $localFullPath);
    public function downloadObject($bytes, $localFullPath);
    public function publishObject($remoteFullPath, $expirationTime = 90);
    public function removeObject($remoteFullPath, $recursive = false);
}
