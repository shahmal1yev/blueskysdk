# BlueskySDK

## Project Description

BlueskySDK is a PHP library used to interact with the Bluesky API. This library allows you to perform file uploads, create records, and other operations using the Bluesky API.

## Requirements

- PHP 5.6 or newer
- Composer

## Installation

1. Clone the repository: `git clone https://github.com/user/BlueskySDK.git`
2. Navigate to the project directory: `cd BlueskySDK`
3. Install dependencies: `composer install`

## Usage

After including the library in your project, you can refer to the following examples:

### File Upload

```php
use Atproto\API\Com\Atrproto\Repo\UploadBlobRequest;
use Atproto\Clients\BlueskyClient;
use Atproto\Auth\Strategies\PasswordAuthentication;

$client = new BlueskyClient(new UploadBlobRequest);

$client->setStrategy(new PasswordAuthentication)
    ->authenticate([
        'identifier' => 'user@example.com',
        'password' => 'password'
    ]);

$client->getRequest()
    ->setBlob('/var/www/blueskysdk/assets/file.png');

$response = $client->execute();

echo "Blob uploaded successfully. CID: {$response->cid}";
```

### Record Creation
```php
use Atproto\API\Com\Atrproto\Repo\CreateRecordRequest;
use Atproto\Clients\BlueskyClient;
use Atproto\Auth\Strategies\PasswordAuthentication;

$client = new BlueskyClient(new CreateRecordRequest);

$client->setStrategy(new PasswordAuthentication)
    ->authenticate([
        'identifier' => 'user@example.com',
        'password' => 'password'
    ]);

$client->getRequest()
    ->setRecord([
        'text' => 'I posted from Unit tests'
    ]);

echo "Record created successfully. URI: {$response->uri}";
```
### Create Record (with blob)

```php
use Atproto\API\Com\Atrproto\Repo\UploadBlobRequest;
use Atproto\Auth\Strategies\PasswordAuthentication;
use Atproto\Clients\BlueskyClient;
use Atproto\API\Com\Atrproto\Repo\CreateRecordRequest;

$client = new BlueskyClient(new UploadBlobRequest);

$client->setStrategy(new PasswordAuthentication)
    ->authenticate([
        'identifier' => 'user@example.com',
        'password' => 'password'
    ]);

$client->getRequest()
    ->setBlob('/var/www/blueskysdk/assets/file.png')
    ->setHeaders([
        'Content-Type' => $client->getRequest()
            ->getBlob()
            ->getMimeType()
    ]);

$image = $client->execute();

$client->setRequest(new CreateRecordRequest);

$client->getRequest()
    ->setRecord([
        'text' => 'Hello World. I posted from "test BlueskyClient execute method with both UploadBlob and CreateRecord"',
        'embed' => [
            '$type' => 'app.bsky.embed.images',
            'images' => [
                [
                    'alt' => 'Image alt value',
                    'image' => $image->blob
                ]
            ]
        ]
    ]);

$response = $client->execute();
```

## Contribution
- If you find any bug or issue, please open an issue.
- If you want to contribute to the code, feel free to submit a pull request.

## License

This project is licensed under the MIT License. For more information, see the LICENSE file.