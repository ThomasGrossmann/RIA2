<?php

namespace App;

interface IDataObject
{
    public function doesExist(string $remoteFullPath): bool;
    public function upload(string $localFullPath, string $remoteFullPath): void;
    public function download(string $remoteFullPath, string $localFullPath): void;
    public function publish(string $remoteFullPath, int $expirationTime = 90): string;
    public function remove(string $remoteFullPath, bool $recursive = false): void;
}
