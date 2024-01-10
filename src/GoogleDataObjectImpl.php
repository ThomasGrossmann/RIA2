<?php

namespace App;

use Google\Cloud\Storage\StorageClient;
use Exception;

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

    public function apiCall($objectUri, $localFile)
    {
        if (!$this->doesExist($objectUri)) {
            $this->upload($localFile, $objectUri);
        }
    }

    public function doesExist($remoteFullPath): bool
    {
        $occurrences = substr_count($remoteFullPath, '/');
        if ($occurrences <= 2) {
            return $this->bucket->exists();
        } else {
            $objects = $this->bucket->objects(['prefix' => $remoteFullPath]);
            foreach ($objects as $object) {
                if ($object->name() == $remoteFullPath || strpos($object->name(), $remoteFullPath) !== false) {
                    return true;
                }
            }
            return false;
        }
    }

    public function upload($localFullPath, $remoteFullPath): void
    {
        $this->bucket->upload(
            fopen($localFullPath, 'r'),
            ['name' => $remoteFullPath]
        );
    }

    public function download($remoteFullPath, $localFullPath): void
    {
        $this->bucket->object($remoteFullPath)->downloadToFile($localFullPath);
    }

    public function publish($remoteFullPath, $expirationTime = 90): string
    {
        if (!$this->doesExist($remoteFullPath)) {
            throw new Exception();
        } else {
            $object = $this->bucket->object($remoteFullPath);
            $url = $object->signedUrl(
                new \DateTime('+' . $expirationTime . ' minutes'),
                ['version' => 'v4']
            );
            return $url;
        }
    }

    public function remove($remoteFullPath, $recursive = false): void
    {
        if ($recursive) {
            $objects = $this->bucket->objects(['prefix' => $remoteFullPath]);
            foreach ($objects as $object) {
                $object->delete();
            }
        } else {
            $this->bucket->object($remoteFullPath)->delete();
        }
    }
}
