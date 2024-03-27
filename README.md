# BI1 - README

## Description
This project is designed to use the image analysis API from providers such as Google (Vision), Amazon (Rekognition) or Azure (AI Vision).

The user will be able to customize the output by passing an image, the maximum number of results and the minimum level of confidence.

This project also introduces the concept of BI (Business Intelligence) by converting image analysis data into a SQL file that will be stocked on the provider's cloud storage.

## Getting started

### Prerequisites
Tools with versions that were used to realize this project. Versions may be subject to change as the project was realized exclusively on MacOS system.
- **IDE** : Visual Studio Code 1.85.1
- **Langages** :
  - PHP 8.3.0
  - MySQL (from 11.1.3-MariaDB)
- **Package Manager** : Composer 2.4.4

### Configuration
#### Env
Some data must remain private such as the API crendentials JSON file or bucket URI.

In order to do so, copy and rename the `.env.example` file to `.env` and fill the corresponding values, for each service.
- API Gateway
  - `cd apiGateway`
  - `cp .env.example .env`
  - The API Gateway does not have any real private data so you do not need to fill anything
- Data Object
  - `cd dataObject`
  - `cp .env.example .env`
  - Fill the `CREDENTIALS_PATH`, `BUCKET_NAME` and `BUCKET_URI` variables with the corresponding values from your prefered Cloud Storage Provider.
- Label Detector
  - `cd labelDetector`
  - `cp .env.example .env`
  - Fill the `CREDENTIALS_PATH` variable with the corresponding value from you prefered Image Rekognition Provider.

## Deployment

### To install the project
- Clone the repo locally
  - `git clone https://github.com/ThomasGrossmann/RIA2`
  - `cd RIA2`
- Install Composer dependencies and launch each service
  - API Gateway
    - `cd apiGateway`
    - `composer install`
    - `php artisan serve --port=5000`
  - Label Detector
    - `cd labelDetector`
    - `composer install`
    - `php artisan serve --port=5001`
  - Data Object
    - `cd dataObject`
    - `composer install`
    - `php artisan serve --port=5002`
```
Note that the ports used are up to you but in the project's state, these are the port that are use for development
```
- Please refer to the [Frontend README](https://github.com/CPNV-ES-RIA2/THOMAS/blob/main/README.md) to know how to really use the app with its graphical interface.

### To run the tests
TODO

## Directory structure
The services are all Laravel projects. To reduce boilerplate and repetition, I only mentionned the important folders and files that were either modified or created by myself.
```
├── LICENSE
├── README.md
├── apiGateway
│   ├── README.md
│   ├── app
│   │   ├── Http
│   │   │   ├── Controllers
│   │   │   │   ├── ApiGatewayController.php
│   ├── composer.json
│   ├── composer.lock
│   ├── package.json
│   ├── routes
│   │   ├── api.php
│   ├── tests
│   │   └── Unit
│   │       └── ExampleTest.php
├── composer.json
├── composer.lock
├── create_model.sql
├── dataObject
│   ├── README.md
│   ├── app
│   │   ├── Http
│   │   │   ├── Controllers
│   │   │   │   └── DataObjectController.php
│   │   └── Services
│   │       ├── GoogleDataObjectImpl.php
│   │       └── IDataObject.php
│   ├── composer.json
│   ├── composer.lock
│   ├── package.json
│   ├── routes
│   │   ├── api.php
│   ├── tests
│   │   └── Unit
│   │       ├── DataObjectTest.php
├── docs
│   ├── RIA2-Frontend_Conception.png
│   ├── RIA2.drawio
│   └── RIA2.png
├── images
│   ├── objectToRemove.jpeg
│   └── sample.jpeg
├── index.php
└── labelDetector
    ├── README.md
    ├── app
    │   ├── Http
    │   │   ├── Controllers
    │   │   │   └── LabelDetectorController.php
    │   └── Services
    │       ├── ILabelDetector.php
    │       └── LabelDetectorImpl.php
    ├── composer.json
    ├── composer.lock
    ├── package.json
    ├── routes
    │   ├── api.php
    ├── tests
    │   └── Unit
    │       └── ExampleTest.php
```

## License
[LICENSE](LICENSE)

## Contact
- Teams
- Email
- Create an issue in the repo
