# BlueskySDK

## Project Description

BlueskySDK is a PHP library used to interact with the Bluesky API. This library allows you to perform file uploads, create records, and other operations using the Bluesky API.

## Requirements

- PHP 5.6 or newer
- Composer

## Installation

```shell
composer require shahmal1yev/blueskysdk
```

## Usage

After including the library in your project, you can refer to the following examples:

### File Upload

```php
use Atproto\API\Com\Atrproto\Repo\UploadBlob;
use Atproto\Clients\BlueskyClient;
use Atproto\Auth\Strategies\PasswordAuthentication;

$client = new BlueskyClient(new UploadBlob);

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
use Atproto\API\Com\Atrproto\Repo\CreateRecord;
use Atproto\Clients\BlueskyClient;
use Atproto\Auth\Strategies\PasswordAuthentication;

$client = new BlueskyClient(new CreateRecord);

$client->setStrategy(new PasswordAuthentication)
    ->authenticate([
        'identifier' => 'user@example.com',
        'password' => 'password'
    ]);
    
$record = new \Atproto\Builders\Bluesky\RecordBuilder();

$record->addText("Hello World!")
    ->addText("")
    ->addText("I was sent via BlueskySDK: https://github.com/shahmal1yev/blueskysdk")
    ->addCreatedAt(date_format(date_create_from_format("d/m/Y", "08/11/2020"), "c"))
    ->addType();

$client->getRequest()
    ->setRecord($record);

echo "Record created successfully. URI: {$response->uri}";
```
### Create Record (with blob)

```php
use Atproto\API\Com\Atrproto\Repo\UploadBlob;
use Atproto\Auth\Strategies\PasswordAuthentication;
use Atproto\Clients\BlueskyClient;
use Atproto\API\Com\Atrproto\Repo\CreateRecord;

$client = new BlueskyClient(new UploadBlob);

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

$client->setRequest(new CreateRecord);

$record = (new \Atproto\Builders\Bluesky\RecordBuilder)
    ->addText("Hello World!")
    ->addText("")
    ->addText("I was sent from 'test BlueskyClient execute method with both UploadBlob and CreateRecord'")
    ->addText("")
    ->addText("Here are the pictures: ")
    ->addImage($image->blob, "Image 1: Alt text")
    ->addImage($image->blob, "Image 2: Alt text")
    ->addType()
    ->addCreatedAt();

$client->getRequest()
    ->setRecord($record);

$response = $client->execute();
```

## Contribution
- If you find any bug or issue, please open an issue.
- If you want to contribute to the code, feel free to submit a pull request.

## License

This project is licensed under the MIT License. For more information, see the LICENSE file.