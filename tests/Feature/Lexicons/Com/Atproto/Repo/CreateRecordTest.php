<?php

namespace Tests\Feature\Lexicons\Com\Atproto\Repo;

use Atproto\Client;
use Atproto\Contracts\Lexicons\App\Bsky\Feed\PostBuilderContract;
use Atproto\DataModel\Blob\Blob;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\App\Bsky\Embed\Collections\ImageCollection;
use Atproto\Lexicons\App\Bsky\Embed\Image;
use Atproto\Lexicons\App\Bsky\RichText\RichText;
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
            'shahmal1yevv.bsky.social',
            'ucvlqcq8'
        );
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testBuildCreateRecordRequestWithPost(): void
    {
        $client = static::$client;

        /** @var Blob $uploadBlob */
        $uploadedBlob = $client->com()
            ->atproto()
            ->repo()
            ->uploadBlob()
            ->forge() // Atproto\Lexicons\Com\Atproto\Repo\UploadBlob
            ->token($client->authenticated()->accessJwt())
            ->blob(__DIR__.'/../../../../../../art/logo-small.webp')
            ->build()
            ->send() // Atproto\Resources\Com\Atproto\Repo\UploadBlobResource
            ->blob();

        $post = $client->app()
            ->bsky()
            ->feed()
            ->post()
            ->forge()
            ->text(
                "Hello World! ",
                "This post was sent from a feature test of the ",
                RichText::link('https://github.com/shahmal1yev/blueskysdk', 'BlueSky PHP SDK'),
                '. You can read the docs from ',
                RichText::link('https://blueskysdk.shahmal1yev.dev', 'here'),
                '. Author? Yes, ',
                RichText::mention('did:plc:bdkw6ic5ugy6ni4pqvljcpva', 'here'),
                ' it is. ',
                RichText::tag('php', 'php'),
                " ",
                RichText::tag('sdk', 'sdk'),
                " ",
                RichText::tag('bluesky', 'bluesky'),
            )
            ->embed(new ImageCollection([
                new Image($uploadedBlob, "PHP BlueSky SDK Logo")
            ]));


        $this->assertInstanceOf(PostBuilderContract::class, $post);

        /** @var CreateRecord $createRecord */
        $createRecord = $client->com()->atproto()->repo()->createRecord()->forge();

        $createRecord->record($post)
            ->repo($client->authenticated()->did())
            ->collection($post->nsid());

        $this->assertInstanceOf(CreateRecord::class, $createRecord);

        $serializedPost = json_decode($post, true);
        $actualPost = Arr::get(json_decode($createRecord, true), 'record');

        $this->assertSame($serializedPost, $actualPost);

        $response = $createRecord->send();

        $this->assertIsString($response->uri());
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
                    Blob::viaFile(new FileSupport(__DIR__.'/../../../../../../art/logo-small.webp')),
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

    public function testPostCreationWithPreviewCard(): void
    {
        $client = static::$client;

        $uploadedBlob = $client->com()->atproto()->repo()->uploadBlob()->forge()
            ->token($client->authenticated()->accessJwt())
            ->blob(__DIR__.'/../../../../../../art/logo-small.webp')
            ->send()
            ->blob();

        $external = $client->app()->bsky()->embed()->external()->forge(
            'https://shahmal1yev.dev',
            'Eldar Shahmaliyev\'s blog',
            'A personal blog about cybersecurity and development.'
        )->thumb($uploadedBlob);

        $post = $client->app()->bsky()->feed()->post()->forge()
            ->text('Come to my blog: ')
            ->link('https://shahmal1yev.dev', 'click here and read the posts')
            ->embed($external);

        $createRecord = $client->com()->atproto()->repo()->createRecord()->forge()
            ->record($post)
            ->repo($client->authenticated()->did())
            ->collection($post->nsid())
        ;

        $serializedCreateRecord = json_decode($createRecord, true);

        $this->assertIsArray(Arr::get($serializedCreateRecord, 'record.embed.external.thumb'));
    }
}
