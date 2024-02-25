<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use App\GoogleDataObjectImpl;
use App\Exceptions\ObjectNotFoundException;

class DataObjectTest extends TestCase
{
    private static GoogleDataObjectImpl $dataObjectInstance;
    private static string $bucketName;
    private static string $bucketUri;
    private GoogleDataObjectImpl $dataObject;
    private string $objectUri;
    private string $localFile;
    private string $downloadDestination;

    public static function setUpBeforeClass(): void
    {
        $env = parse_ini_file('.env.dataObject');
        $credentialsPath = $env['CREDENTIALS_PATH'];
        self::$bucketUri = $env['BUCKET_URI'];
        self::$bucketName = $env['BUCKET_NAME'];
        self::$dataObjectInstance = new GoogleDataObjectImpl(self::$bucketName, $credentialsPath);
    }

    protected function setUp(): void
    {
        $this->bucketUri = self::$bucketUri;
        $this->dataObject = self::$dataObjectInstance;

        $this->objectUri = $this->bucketUri . '/sample.jpeg';
        $this->localFile = 'images/sample.jpeg';
        $this->downloadDestination = 'images/testDownload.jpeg';
    }

    protected function tearDown(): void
    {
        $objectsToClear = $this->dataObject->bucket->objects();
        foreach ($objectsToClear as $object) {
            $object->delete();
        }

        if (file_exists($this->downloadDestination)) {
            unlink($this->downloadDestination);
        }

        if (file_exists('images/testPublish.jpeg')) {
            unlink('images/testPublish.jpeg');
        }
    }

    public function testDoesExistExistingBucketBucketExists()
    {
        // given
        // The bucket is always available

        // when

        // then
        $this->assertTrue($this->dataObject->doesExist($this->bucketUri));
    }

    public function testDoesExistExistingObjectObjectExists()
    {
        // given
        // The bucket is always available
        $this->dataObject->upload($this->localFile, $this->objectUri);

        // when

        // then
        $this->assertTrue($this->dataObject->doesExist($this->localFile));
    }

    public function testDoesExistMissingObjectObjectNotExists()
    {
        // given
        // The bucket is always available
        // The bucket is empty (or does not contain the expected object)

        // when

        // then
        $this->assertFalse($this->dataObject->doesExist($this->objectUri));
    }

    public function testUploadBucketAndLocalFileAreAvailableNewObjectCreatedOnBucket()
    {
        // given
        $this->assertTrue($this->dataObject->doesExist($this->bucketUri));
        $this->assertFalse($this->dataObject->doesExist($this->objectUri));

        // when
        $this->dataObject->upload($this->localFile, $this->objectUri);

        // then
        $this->assertTrue($this->dataObject->doesExist($this->objectUri));
    }

    public function testDownloadObjectAndLocalPathAvailableObjectDownloaded()
    {
        // given
        // have to upload an image first (the bucket is cleaned before each test)
        $this->dataObject->upload($this->localFile, $this->objectUri);
        $this->assertTrue($this->dataObject->doesExist($this->objectUri));
        $this->assertFalse(file_exists($this->downloadDestination));

        // when
        $this->dataObject->download($this->objectUri, $this->downloadDestination);

        // then
        $this->assertTrue(file_exists($this->downloadDestination));
    }

    public function testDownloadObjectMissingThrowException()
    {
        // given
        $this->objectUri = $this->bucketUri . '/missingObject.jpeg';

        $this->assertFalse($this->dataObject->doesExist($this->objectUri));
        $this->assertFalse(file_exists($this->downloadDestination));

        // when
        $this->expectException(ObjectNotFoundException::class);
        $this->dataObject->download($this->objectUri, $this->downloadDestination);

        // then
        // The exception is thrown
    }

    public function testPublishObjectExistsPublicUrlCreated()
    {
        // given
        // have to upload an image first (the bucket is cleaned before each test)
        $this->dataObject->upload($this->localFile, $this->objectUri);
        $this->localFile = 'images/testPublish.jpeg';
        $destinationFolder = 'images/';

        $this->assertTrue($this->dataObject->doesExist($this->objectUri));
        $this->assertTrue(file_exists($destinationFolder));

        // when
        $presignedUrl = $this->dataObject->publish($this->objectUri);
        file_put_contents($this->localFile, file_get_contents($presignedUrl));

        // then
        $this->assertTrue($this->dataObject->doesExist($this->localFile));
    }

    public function testPublishObjectMissingThrowException()
    {
        // given
        $this->objectUri = $this->bucketUri . '/missingObject.jpeg';

        $this->assertFalse($this->dataObject->doesExist($this->objectUri));

        // when
        $this->expectException(ObjectNotFoundException::class);
        $this->dataObject->publish($this->objectUri);

        // then
        // The exception is thrown
    }

    public function testRemoveObjectPresentNoFolderObjectRemoved()
    {
        // given
        $this->objectUri = $this->bucketUri . '/objectToRemove.jpeg';
        $this->localFile = 'images/objectToRemove.jpeg';

        $this->dataObject->upload($this->localFile, $this->objectUri);
        $this->assertTrue($this->dataObject->doesExist($this->objectUri));

        // when
        $this->dataObject->remove($this->objectUri);

        // then
        $this->assertFalse($this->dataObject->doesExist($this->objectUri));
    }

    public function testRemoveObjectAndFolderPresentObjectRemoved()
    {
        // given
        // The bucket contains object at the root level as well as in subfolders
        // Sample: mybucket.com/myobject     //myObject is a folder
        //        mybucket.com/myobject/myObjectInSubfolder
        $this->objectUri = $this->bucketUri . '/images';
        $objectUriWithSubFolder = $this->bucketUri . '/images/objectToRemove.jpeg';
        $this->localFile = 'images/objectToRemove.jpeg';

        $this->dataObject->upload($this->localFile, $objectUriWithSubFolder);
        $this->assertTrue($this->dataObject->doesExist($this->objectUri));
        $this->assertTrue($this->dataObject->doesExist($objectUriWithSubFolder));

        // when
        $this->dataObject->remove($this->objectUri, true);

        // then
        $this->assertFalse($this->dataObject->doesExist($this->objectUri));
    }
}
