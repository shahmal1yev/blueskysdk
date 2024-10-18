<?php

namespace Tests\Feature\Lexicons\Com\Atproto\Repo;

use Atproto\Client;
use Atproto\Contracts\Lexicons\App\Bsky\Feed\PostBuilderContract;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\Com\Atproto\Repo\CreateRecord;
use Atproto\Support\Arr;
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

        $createRecord = $client->com()->atproto()->repo()->createRecord()->forge()->record($post);

        $this->assertInstanceOf(CreateRecord::class, $createRecord);

        $serializedPost = json_decode($post, true);
        $actualPost = Arr::get(json_decode($createRecord, true), 'record');

        $this->assertSame($serializedPost, $actualPost);

        $response = $client->send();

        $this->assertIsString($response->uri());

        echo $response->uri();
    }
}
