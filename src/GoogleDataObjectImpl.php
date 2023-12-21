<?php

require __DIR__ . '/IDataObject.php';

use Google\Cloud\Storage\StorageClient;

class GoogleDataObjectImpl implements IDataObject
{
    public $bucket;

    public function __construct($bucketName)
    {
        $env = parse_ini_file('.env');
        $storage = new StorageClient([
            'keyFilePath' => $env['CREDENTIALS_PATH']
        ]);
        $this->bucket = $storage->bucket($bucketName);
    }

    public function doesExist($remoteFullPath): bool
    {
        if (substr($remoteFullPath, -1) === '/') {
            return $this->bucket->object($remoteFullPath)->exists();
        } else {
            return $this->bucket->exists();
        }
    }

    public function upload($bytes, $localFullPath): void
    {
        //
    }

    public function download($bytes, $localFullPath)
    {
        //
    }

    public function publish($remoteFullPath, $expirationTime = 90): string
    {
        //
    }

    public function remove($remoteFullPath, $recursive = false): void
    {
        //
    }
}
