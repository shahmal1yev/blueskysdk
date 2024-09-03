<?php

namespace Clients;

use Atproto\API\App\Bsky\Actor\GetProfile;
use Atproto\API\Com\Atrproto\Repo\CreateRecordRequest;
use Atproto\API\Com\Atrproto\Repo\UploadBlobRequest;
use Atproto\Auth\Strategies\PasswordAuthentication;
use Atproto\Builders\Bluesky\RecordBuilder;
use Atproto\Clients\BlueskyClient;
use Atproto\Contracts\AuthStrategyContract;
use Atproto\Contracts\HTTP\RequestContract;
use Atproto\Exceptions\Auth\AuthFailed;
use RuntimeException;

// Test constructor with default URL
test('BlueskyClient constructor with default URL', function() {
    $client = new BlueskyClient(new CreateRecordRequest());

    $reflection = new \ReflectionClass(BlueskyClient::class);
    $method = $reflection->getProperty('url');
    $method->setAccessible(true);

    $url = $method->getValue($client);

    expect($url)
        ->toBeString()
        ->toBe('https://bsky.social/xrpc');
});

// Test constructor with custom URL
test('BlueskyClient constructor with custom URL', function() {
    $expected = "https://shahmal1yev.com/api";
    $client = new BlueskyClient(new CreateRecordRequest, $expected);

    $reflection = new \ReflectionClass(BlueskyClient::class);
    $method = $reflection->getProperty('url');
    $method->setAccessible(true);

    $url = $method->getValue($client);

    expect($url)
        ->toBeString()
        ->toBe($expected);
});

// Test getRequest method
test('BlueskyClient getRequest method', function () {
    $request = new CreateRecordRequest;
    $client = new BlueskyClient($request);

    expect($client->getRequest())
        ->toBeInstanceOf(RequestContract::class)
        ->toBeInstanceOf(CreateRecordRequest::class)
        ->toBe($request);
});

// Test authenticate method with valid credentials
test('BlueskyClient authenticate method with valid credentials', function () {
    $client = new BlueskyClient(new CreateRecordRequest);
    $client->setStrategy(new PasswordAuthentication);

    $authenticated = $client->authenticate([
        'identifier' => 'shahmal1yev.bsky.social',
        'password' => 'ucvlqcq8'
    ]);

    expect($authenticated)
        ->toBeObject()
        ->not
        ->toBeNull()
        ->and($authenticated->did)
        ->toBeString()
        ->and($authenticated->accessJwt)
        ->toBeString();
});

// Test authenticate method with invalid credentials
test('BlueskyClient authenticate method with invalid credentials', function () {
    $client = new BlueskyClient(new CreateRecordRequest);
    $client->setStrategy(new PasswordAuthentication);

    $client->authenticate([
        'identifier' => 'invalid identifier',
        'password' => 'invalid password'
    ]);
})->throws(
    AuthFailed::class,
    "Authentication failed: "
);

// Test execute method with CreateRecord
test('BlueskyClient execute method with CreateRecord', function () {
    $client = new BlueskyClient(new CreateRecordRequest);

    $client->setStrategy(new PasswordAuthentication)
        ->authenticate([
            'identifier' => 'shahmal1yev.bsky.social',
            'password' => 'ucvlqcq8'
        ]);

    $recordBuilder = (new RecordBuilder())
        ->addText("Hello World! I am posted from PHP Unit tests for testing this URL adding to this post: \n1. https://www.fs-poster.com \n2. https://github.com/shahmal1yev/blueskysdk \n3. https://github.com/easypay/php-yigim")
        ->addType()
        ->addCreatedAt();

    $client->getRequest()
        ->setRecord($recordBuilder);

    $response = $client->execute();

    expect($response)
        ->toBeObject()
        ->not
        ->toBeEmpty()
        ->and($response->uri)
        ->toBeString()
        ->and($response->cid)
        ->toBeString();
});

// Test execute method with UploadBlob
test('BlueskyClient execute method with UploadBlob', function () {
    $client = new BlueskyClient(new UploadBlobRequest);

    $client->setStrategy(new PasswordAuthentication)
        ->authenticate([
            'identifier' => 'shahmal1yev.bsky.social',
            'password' => 'ucvlqcq8'
        ]);

    $client->getRequest()
        ->setBlob('/var/www/blueskysdk/assets/file.png');

    $response = $client->execute();

    expect($response)
        ->toBeObject()
        ->not
        ->toBeEmpty();
});

// Test execute method with GetProfile
test("BlueskyClient execute method with GetProfile", function() {
    $client = new BlueskyClient(new GetProfile);

    $client->setStrategy(new PasswordAuthentication)
        ->authenticate([
            'identifier' => 'shahmal1yev.bsky.social',
            'password' => 'ucvlqcq8'
        ]);

    $client->getRequest()
        ->setActor('shahmal1yev.bsky.social');

    $response = $client->execute();

    expect($response)
        ->toBeObject()
        ->not
        ->toBeNull()
        ->and($response->did)
        ->toBeString()
        ->and($response->displayName)
        ->toBeString()
        ->and($response->handle)
        ->toBeString();
});

// Test execute method both UploadBlob and CreateRecord
test('BlueskyClient execute method with both UploadBlob and CreateRecord', function () {
    $client = new BlueskyClient(new UploadBlobRequest);

    $client->setStrategy(new PasswordAuthentication)
        ->authenticate([
            'identifier' => 'shahmal1yev.bsky.social',
            'password' => 'ucvlqcq8'
        ]);

    $client->getRequest()
        ->setBlob('/var/www/blueskysdk/assets/file.png')
        ->setHeaders([
            'Content-Type' => $client->getRequest()
                ->getBlob()
                ->getMimeType()
        ]);

    $image = $client->execute();

    $recordBuilder = (new RecordBuilder())
        ->addText("Hello World!")
        ->addText("")
        ->addText("I was sent to test the inclusion of these URLs in this post:")
        ->addText("")
        ->addText("1. https://www.fs-poster.com")
        ->addText("2. https://github.com/shahmal1yev/blueskysdk")
        ->addText("3. https://www.wordpress.php")
        ->addType()
        ->addImage($image->blob)
        ->addImage($image->blob)
        ->addCreatedAt();

    $client->setRequest(new CreateRecordRequest);

    $client->getRequest()
        ->setRecord($recordBuilder);

    $response = $client->execute();

    expect($response)
        ->toBeObject()
        ->not
        ->toBeEmpty()
        ->and($response->uri)
        ->toBeString()
        ->and($response->cid)
        ->toBeString();
});

// Test setStrategy method
test('BlueskyClient setStrategy method', function () {
    $authStrategy = new PasswordAuthentication;
    $client = new BlueskyClient(new CreateRecordRequest);

    $client->setStrategy($authStrategy);

    $reflection = new \ReflectionClass($client);
    $property = $reflection->getProperty('authStrategy');
    $property->setAccessible(true);

    expect($property->getValue($client))
        ->toBeInstanceOf(AuthStrategyContract::class)
        ->toBeInstanceOf(PasswordAuthentication::class)
        ->toBe($authStrategy);
});