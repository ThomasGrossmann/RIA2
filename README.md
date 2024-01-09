# BI1 - README

## Description
This project is designed to use the image analysis API from providers such as Google (Vision), Amazon (Rekognition) or Azure (AI Vision).

The user will be able to customize the output by passing an image, the maximum number of results and the minimum level of confidence.

This project also introduces the concept of BI (Business Intelligence) by converting image analysis data into a SQL file that will be stocked on the provider's cloud storage.

## Getting started

### Prerequisites
Tools with versions that were used to realize this project.
- **IDE** : Visual Studio Code 1.85.1
- **Langage** : PHP 8.3.0
- **Package Manager** : Composer 2.4.4

### Configuration
Some data must remain private such as API crendentials JSON file or bucket URI.

In order to do so, copy and rename the `.env.example` file.
- `cp .env.example .env`
- Open the file and fill the variables with your data

## Deployment

### Dependencies
Composer dependencies used to realize and test the project.
- **Dev**
  - google/apiclient : ^2.15
  - google/cloud-vision : ^1.7
  - google/cloud-storage : ^1.36
- **Tests**
  - phpunit/phpunit ^10.5

### To install the project
- Clone the repo locally
  - ```
    git clone https://github.com/ThomasGrossmann/BI1/
    cd BI1
    ```
- Install Composer dependencies
  - `composer install`
### To run the tests
- DataObject Tests
  - `./vendor/bin/phpunit tests/DataObjectTests.php`
- LabelDetector Tests
  - `./vendor/bin/phpunit tests/LabelDetectorTests.php`  
## Directory structure
```
├── LICENSE
├── README.md
├── composer.json
├── composer.lock
├── images
│   ├── objectToRemove.jpeg
│   └── sample.jpeg
├── index.php
├── src
│   ├── GoogleDataObjectImpl.php
│   ├── IDataObject.php
│   ├── ILabelDetector.php
│   └── LabelDetectorImpl.php
└── tests
    ├── DataObjectTest.php
    └── LabelDetectorTest.php
```

## License
[LICENSE](LICENSE)

## Contact
- Teams
- Email
