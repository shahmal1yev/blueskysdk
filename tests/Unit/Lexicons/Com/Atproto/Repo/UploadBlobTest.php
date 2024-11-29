<?php

namespace Tests\Unit\Lexicons\Com\Atproto\Repo;

use Atproto\Client;
use Atproto\Exceptions\Http\MissingFieldProvidedException;
use Atproto\Lexicons\Com\Atproto\Repo\UploadBlob;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;
use Tests\Supports\Reflection;

class UploadBlobTest extends TestCase
{
    use Reflection;

    private UploadBlob $uploadBlob;
    private Generator $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->uploadBlob = new UploadBlob();
        $this->faker = Factory::create();
    }

    public function testBlobMethodSetsAndReturnsValue(): void
    {
        $blobData = random_bytes(1024);

        $result = $this->uploadBlob->blob($blobData);

        $this->assertSame($blobData, $result->blob());
    }

    public function testTokenMethodSetsAndReturnsValue(): void
    {
        $token = $this->faker->word;

        $result = $this->uploadBlob->token($token);

        $this->assertSame($token, $result->token());
    }

    public function testTokenMethodSetsAuthorizationHeader(): void
    {
        $token = $this->faker->word;

        $result = $this->uploadBlob->token($token);

        $this->assertSame($token, $result->token());
    }
}
