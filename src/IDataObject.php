<?php

namespace App;

interface IDataObject
{
    public function doesExist($remoteFullPath): bool;
    public function upload($localFullPath, $remoteFullPath): void;
    public function download($remoteFullPath, $localFullPath): void;
    public function publish($remoteFullPath, $expirationTime = 90): string;
    public function remove($remoteFullPath, $recursive = false): void;
}
