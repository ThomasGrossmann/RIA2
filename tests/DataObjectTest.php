<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\GoogleDataObjectImpl;
use Exception;

class DataObjectTest extends TestCase
{
    private $bucketUri;
    private $bucketName;
    private $dataObject;

    protected function setUp(): void
    {
        $env = parse_ini_file('.env');
        $this->bucketName = $env['BUCKET_NAME'];
        $this->bucketUri = $env['BUCKET_URI'];
        $this->dataObject = new GoogleDataObjectImpl($this->bucketName);
    }

    private function clearBucket()
    {
        $objectsToClear = $this->dataObject->bucket->objects();
        foreach ($objectsToClear as $object) {
            $object->delete();
        }
    }

    public function testDoesExistExistingBucketBucketExists()
    {
        $this->assertTrue($this->dataObject->doesExist($this->bucketUri));
    }

    public function testDoesExistExistingObjectObjectExists()
    {
        $this->clearBucket();
        $objectUri = $this->bucketUri . '/sample.jpeg';
        $localFile = 'images/sample.jpeg';

        $this->dataObject->upload($localFile, $objectUri);

        $this->assertTrue($this->dataObject->doesExist($localFile));
    }

    public function testDoesExistMissingObjectObjectNotExists()
    {
        $objectUri = $this->bucketUri . '/sample.jpeg';

        $this->assertFalse($this->dataObject->doesExist($objectUri));
    }

    public function testUploadBucketAndLocalFileAreAvailableNewObjectCreatedOnBucket()
    {
        $this->clearBucket();
        $objectUri = $this->bucketUri . '/sample.jpeg';
        $localFile = 'images/sample.jpeg';

        $this->assertTrue($this->dataObject->doesExist($this->bucketUri));
        $this->assertFalse($this->dataObject->doesExist($objectUri));

        $this->dataObject->upload($localFile, $objectUri);

        $this->assertTrue($this->dataObject->doesExist($objectUri));
    }

    public function testDownloadObjectAndLocalPathAvailableObjectDownloaded()
    {
        $objectUri = $this->bucketUri . '/sample.jpeg';
        $localFile = 'images/testDownload.jpeg';

        $this->assertTrue($this->dataObject->doesExist($objectUri));
        $this->assertFalse(file_exists($localFile));

        $this->dataObject->download($objectUri, $localFile);

        $this->assertTrue(file_exists($localFile));
    }

    public function testDownloadObjectMissingThrowException()
    {
        $objectUri = $this->bucketUri . '/missingObject.jpeg';
        $localFile = 'images/testDownload.jpeg';

        if (file_exists($localFile)) {
            unlink($localFile);
        }

        $this->assertFalse($this->dataObject->doesExist($objectUri));
        $this->assertFalse(file_exists($localFile));

        $this->expectException(Exception::class);
        $this->dataObject->download($objectUri, $localFile);
    }

    public function testPublishObjectExistsPublicUrlCreated()
    {
        $objectUri = $this->bucketUri . '/sample.jpeg';
        $localFile = 'images/testPublish.jpeg';
        $destinationFolder = 'images/';

        $this->assertTrue($this->dataObject->doesExist($objectUri));
        $this->assertTrue(file_exists($destinationFolder));

        $presignedUrl = $this->dataObject->publish($objectUri);
        file_put_contents($localFile, file_get_contents($presignedUrl));

        $this->assertTrue($this->dataObject->doesExist($localFile));
    }

    public function testPublishObjectMissingThrowException()
    {
        $objectUri = $this->bucketUri . '/missingObject.jpeg';

        $this->assertFalse($this->dataObject->doesExist($objectUri));

        $this->expectException(Exception::class);
        $this->dataObject->publish($objectUri);
    }

    public function testRemoveObjectPresentNoFolderObjectRemoved()
    {
        $objectUri = $this->bucketUri . '/objectToRemove.jpeg';
        $localFile = 'images/objectToRemove.jpeg';

        $this->dataObject->upload($localFile, $objectUri);
        $this->assertTrue($this->dataObject->doesExist($objectUri));

        $this->dataObject->remove($objectUri);

        $this->assertFalse($this->dataObject->doesExist($objectUri));
    }

    public function testRemoveObjectAndFolderPresentObjectRemoved()
    {
        $this->clearBucket();
        $objectUri = $this->bucketUri . '/images';
        $objectUriWithSubFolder = $this->bucketUri . '/images/objectToRemove.jpeg';
        $localFile = 'images/objectToRemove.jpeg';

        $this->dataObject->upload($localFile, $objectUriWithSubFolder);
        $this->assertTrue($this->dataObject->doesExist($objectUri));
        $this->assertTrue($this->dataObject->doesExist($objectUriWithSubFolder));

        $this->dataObject->remove($objectUri, true);

        $this->assertFalse($this->dataObject->doesExist($objectUri));
    }
}
