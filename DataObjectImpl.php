<?php

class DataObjectImpl //implements IDataObject
{
    private $bucketName;

    public function __construct($bucketName)
    {
        $this->bucketName = $bucketName;
    }
}
