<?php

namespace Tests\Feature\Lexicons\Com\Atproto\Repo;

use Atproto\Client;
use Atproto\Contracts\Lexicons\App\Bsky\Feed\PostBuilderContract;
use Atproto\DataModel\Blob\Blob;
use Atproto\Exceptions\BlueskyException;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\App\Bsky\Embed\Collections\ImageCollection;
use Atproto\Lexicons\App\Bsky\Embed\Image;
use Atproto\Lexicons\Com\Atproto\Repo\CreateRecord;
use Atproto\Support\Arr;
use Atproto\Support\FileSupport;
use PHPUnit\Framework\TestCase;

class CreateRecordTest extends TestCase
{
    private static Client $client;

    public static function setUpBeforeClass(): void
    {
        static::$client = new Client();

        static::$client->authenticate(
            getenv('BLUESKY_IDENTIFIER'),
            getenv('BLUESKY_PASSWORD')
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testBuildCreateRecordRequestWithPost(): void
    {
        $client = static::$client;

        $post = $client->app()->bsky()->feed()->post()->forge()->text(
            "Hello World! ",
            "This post was sent from a feature test of the BlueSky PHP SDK ",
        );

        $this->assertInstanceOf(PostBuilderContract::class, $post);

        $createRecord = $client->com()->atproto()->repo()->createRecord()->forge()
            ->record($post)
            ->repo($client->authenticated()->did())
            ->collection('app.bsky.feed.post');

        $this->assertInstanceOf(CreateRecord::class, $createRecord);

        $serializedPost = json_decode($post, true);
        $actualPost = Arr::get(json_decode($createRecord, true), 'record');

        $this->assertSame($serializedPost, $actualPost);

        $response = $createRecord->send();

        $this->assertIsString($response->uri());

        echo $response->uri();
    }

    /**
     * @throws BlueskyException
     */
    public function testSendPostWithBlobUsingPostBuilderAPI(): void
    {
        $client = static::$client;

        /** @var Blob $uploadBlob */
        $uploadedBlob = $client->com()
            ->atproto()
            ->repo()
            ->uploadBlob()
            ->forge() // Atproto\Lexicons\Com\Atproto\Repo\UploadBlob
            ->token($client->authenticated()->accessJwt())
            ->blob(__DIR__.'/../../../../../../art/file.png')
            ->build()
            ->send() // Atproto\Resources\Com\Atproto\Repo\UploadBlobResource
            ->blob();

        $this->assertInstanceOf(Blob::class, $uploadedBlob);

        $post = $client->app()
            ->bsky()
            ->feed()
            ->post()
            ->forge()
            ->text("Hello World!")
            ->embed(new ImageCollection([
                new Image($uploadedBlob, "Alt text")
            ]));

        $this->assertInstanceOf(PostBuilderContract::class, $post);

        $createdRecord = $client->com()
            ->atproto()
            ->repo()
            ->createRecord()
            ->forge()
            ->record($post)
            ->repo($client->authenticated()->did())
            ->collection('app.bsky.feed.post')
            ->build()
            ->send();

        $this->assertIsString($createdRecord->uri());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testPostCreationWithoutBlobUploading(): void
    {
        $client = static::$client;

        $post = $client->app()
            ->bsky()
            ->feed()
            ->post()
            ->forge()
            ->text("Testing post creation without a blob uploading")
            ->embed(new ImageCollection([
                new Image(
                    Blob::viaFile(new FileSupport(__DIR__.'/../../../../../../art/file.png')),
                    'This blob not uploaded during this post creation'
                ),
            ]));

        $createdRecord = $client->com()
            ->atproto()
            ->repo()
            ->createRecord()
            ->forge()
            ->record($post)
            ->repo($client->authenticated()->did())
            ->collection('app.bsky.feed.post')
            ->build()
            ->send();

        $this->assertIsString($createdRecord->uri());
    }
}
