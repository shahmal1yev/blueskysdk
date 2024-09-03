<?php

namespace Tests\Feature;

use Atproto\API\App\Bsky\Actor\GetProfile;
use Atproto\API\Com\Atrproto\Repo\CreateRecordRequest;
use Atproto\API\Com\Atrproto\Repo\UploadBlobRequest;
use Atproto\Auth\Strategies\PasswordAuthentication;
use Atproto\Builders\Bluesky\RecordBuilder;
use Atproto\Clients\BlueskyClient;
use Atproto\Contracts\AuthStrategyContract;
use Atproto\Contracts\HTTP\RequestContract;
use Atproto\Exceptions\Auth\AuthFailed;
use PHPUnit\Framework\TestCase;

class BlueskyClientTest extends TestCase
{
    // Test constructor with default URL
    public function testConstructorWithDefaultURL()
    {
        $client = new BlueskyClient(new CreateRecordRequest());

        $reflection = new \ReflectionClass(BlueskyClient::class);
        $property = $reflection->getProperty('url');
        $property->setAccessible(true);

        $url = $property->getValue($client);

        $this->assertIsString($url);
        $this->assertEquals('https://bsky.social/xrpc', $url);
    }

    // Test constructor with custom URL
    public function testConstructorWithCustomURL()
    {
        $expected = "https://shahmal1yev.com/api";
        $client = new BlueskyClient(new CreateRecordRequest(), $expected);

        $reflection = new \ReflectionClass(BlueskyClient::class);
        $property = $reflection->getProperty('url');
        $property->setAccessible(true);

        $url = $property->getValue($client);

        $this->assertIsString($url);
        $this->assertEquals($expected, $url);
    }

    // Test getRequest method
    public function testGetRequestMethod()
    {
        $request = new CreateRecordRequest;
        $client = new BlueskyClient($request);

        $this->assertInstanceOf(RequestContract::class, $client->getRequest());
        $this->assertInstanceOf(CreateRecordRequest::class, $client->getRequest());
        $this->assertSame($request, $client->getRequest());
    }

    // Test authenticate method with valid credentials
    public function testAuthenticateWithValidCredentials()
    {
        $client = new BlueskyClient(new CreateRecordRequest);
        $client->setStrategy(new PasswordAuthentication);

        $authenticated = $client->authenticate([
            'identifier' => 'shahmal1yev.bsky.social',
            'password' => 'ucvlqcq8'
        ]);

        $this->assertIsObject($authenticated);
        $this->assertNotNull($authenticated);
        $this->assertIsString($authenticated->did);
        $this->assertIsString($authenticated->accessJwt);
    }

    // Test authenticate method with invalid credentials
    public function testAuthenticateWithInvalidCredentials()
    {
        $this->expectException(AuthFailed::class);
        $this->expectExceptionMessage("Authentication failed: ");

        $client = new BlueskyClient(new CreateRecordRequest);
        $client->setStrategy(new PasswordAuthentication);

        $client->authenticate([
            'identifier' => 'invalid identifier',
            'password' => 'invalid password'
        ]);
    }

    // Test execute method with CreateRecord
    public function testExecuteWithCreateRecord()
    {
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

        $client->getRequest()->setRecord($recordBuilder);

        $response = $client->execute();

        $this->assertIsObject($response);
        $this->assertNotEmpty($response);
        $this->assertIsString($response->uri);
        $this->assertIsString($response->cid);
    }

    // Test execute method with UploadBlob
    public function testExecuteWithUploadBlob()
    {
        $client = new BlueskyClient(new UploadBlobRequest);

        $client->setStrategy(new PasswordAuthentication)
            ->authenticate([
                'identifier' => 'shahmal1yev.bsky.social',
                'password' => 'ucvlqcq8'
            ]);

        $client->getRequest()->setBlob('/var/www/blueskysdk/assets/file.png');

        $response = $client->execute();

        $this->assertIsObject($response);
        $this->assertNotEmpty($response);
    }

    // Test execute method with GetProfile
    public function testExecuteWithGetProfile()
    {
        $client = new BlueskyClient(new GetProfile);

        $client->setStrategy(new PasswordAuthentication)
            ->authenticate([
                'identifier' => 'shahmal1yev.bsky.social',
                'password' => 'ucvlqcq8'
            ]);

        $client->getRequest()->setActor('shahmal1yev.bsky.social');

        $response = $client->execute();

        $this->assertIsObject($response);
        $this->assertNotNull($response);
        $this->assertIsString($response->did);
        $this->assertIsString($response->displayName);
        $this->assertIsString($response->handle);
    }

    // Test execute method with both UploadBlob and CreateRecord
    public function testExecuteWithUploadBlobAndCreateRecord()
    {
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

        $client->getRequest()->setRecord($recordBuilder);

        $response = $client->execute();

        $this->assertIsObject($response);
        $this->assertNotEmpty($response);
        $this->assertIsString($response->uri);
        $this->assertIsString($response->cid);
    }

    // Test setStrategy method
    public function testSetStrategyMethod()
    {
        $authStrategy = new PasswordAuthentication;
        $client = new BlueskyClient(new CreateRecordRequest);

        $client->setStrategy($authStrategy);

        $reflection = new \ReflectionClass($client);
        $property = $reflection->getProperty('authStrategy');
        $property->setAccessible(true);

        $this->assertInstanceOf(AuthStrategyContract::class, $property->getValue($client));
        $this->assertInstanceOf(PasswordAuthentication::class, $property->getValue($client));
        $this->assertSame($authStrategy, $property->getValue($client));
    }
}
