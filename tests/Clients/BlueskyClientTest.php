<?php

namespace Clients;

use Atproto\API\Com\Atrproto\Repo\CreateRecordRequest;
use Atproto\API\Com\Atrproto\Repo\UploadBlobRequest;
use Atproto\Auth\Strategies\PasswordAuthentication;
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

// Test authenticate method without setting authentication strategy
test('BlueskyClient authenticate method without strategy', function () {
    $client = new BlueskyClient(new CreateRecordRequest);

    $client->authenticate([
        'identifier' => 'shahmal1yev.bsky.social',
        'password' => 'ucvlqcq8'
    ]);
})->throws(
    RuntimeException::class,
    'You must set an authentication strategy first'
);

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

    $client->getRequest()
        ->setRecord([
            'text' => 'I posted from Unit tests'
        ]);

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