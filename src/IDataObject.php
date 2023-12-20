<?php

interface IDataObject
{
    public function doesExist($remoteFullPath): bool;
    public function upload($bytes, $remoteFullPath): void;
    public function download($remoteFullPath, $localFullPath);
    public function publish($remoteFullPath, $expirationTime = 90): string;
    public function remove($remoteFullPath, $recursive = false): void;
}
