<?php

namespace App\Services;

use App\Exceptions\ObjectAlreadyExistsException;
use App\Exceptions\ObjectNotFoundException;
use Google\Cloud\Storage\StorageClient;

class GoogleDataObjectImpl implements IDataObject
{
    public $bucket;

    public function __construct(string $bucketName, string $credentialsPath)
    {
        $storage = new StorageClient([
            'keyFilePath' => $credentialsPath
        ]);
        $this->bucket = $storage->bucket($bucketName);
    }

    public function apiCall(string $objectUri, string $localFile): void
    {
        if (!$this->doesExist($objectUri)) {
            $this->upload($localFile, $objectUri);
        }
    }

    public function doesExist(string $remoteFullPath): bool
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

    public function upload(string $localFullPath, string $remoteFullPath): void
    {
        if ($this->doesExist($remoteFullPath)) {
            throw new ObjectAlreadyExistsException();
        } else {
            $this->bucket->upload(
                fopen($localFullPath, 'r'),
                ['name' => $remoteFullPath]
            );
        }
    }

    public function download(string $remoteFullPath, string $localFullPath): void
    {
        if (!$this->doesExist($remoteFullPath)) {
            throw new ObjectNotFoundException();
        } else {
            $this->bucket->object($remoteFullPath)->downloadToFile($localFullPath);
        }
    }

    public function publish(string $remoteFullPath, int $expirationTime = 90): string
    {
        if (!$this->doesExist($remoteFullPath)) {
            throw new ObjectNotFoundException();
        } else {
            $object = $this->bucket->object($remoteFullPath);
            $url = $object->signedUrl(
                new \DateTime('+' . $expirationTime . ' minutes'),
                ['version' => 'v4']
            );
            return $url;
        }
    }

    public function remove(string $remoteFullPath, bool $recursive = false): void
    {
        if (!$this->doesExist($remoteFullPath)) {
            throw new ObjectNotFoundException();
        } else if ($recursive) {
            $objects = $this->bucket->objects(['prefix' => $remoteFullPath]);
            foreach ($objects as $object) {
                $object->delete();
            }
        } else {
            $this->bucket->object($remoteFullPath)->delete();
        }
    }
}
