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
        $this->uploadBlob = new UploadBlob($this->createMock(Client::class));
        $this->faker = Factory::create();
    }

    public function testBlobMethodSetsAndReturnsValue(): void
    {
        $blobData = random_bytes(1024);

        $result = $this->uploadBlob->blob($blobData);

        $this->assertSame($this->uploadBlob, $result);
        $this->assertSame($blobData, $this->uploadBlob->blob());
    }

    public function testTokenMethodSetsAndReturnsValue(): void
    {
        $token = $this->faker->word;

        $result = $this->uploadBlob->token($token);

        $this->assertSame($this->uploadBlob, $result);
        $this->assertSame($token, $this->uploadBlob->token());
    }

    public function testTokenMethodSetsAuthorizationHeader(): void
    {
        $token = $this->faker->word;

        $this->uploadBlob->token($token);

        $headers = $this->getPropertyValue('headers', $this->uploadBlob);

        $this->assertArrayHasKey('Authorization', $headers);
        $this->assertSame("Bearer $token", $headers['Authorization']);
    }

    public function testBuildThrowsExceptionWhenBlobIsMissing(): void
    {
        $this->expectException(MissingFieldProvidedException::class);
        $this->expectExceptionMessage('blob');

        $this->uploadBlob->token($this->faker->word)->build();
    }

    public function testBuildThrowsExceptionWhenTokenIsMissing(): void
    {
        $this->expectException(MissingFieldProvidedException::class);
        $this->expectExceptionMessage('token');

        $this->uploadBlob->blob(random_bytes(1024))->build();
    }

    /**
     * @throws MissingFieldProvidedException
     */
    public function testBuildReturnsInstanceWhenAllFieldsAreSet(): void
    {
        $result = $this->uploadBlob
            ->blob(random_bytes(1024))
            ->token($this->faker->word)
            ->build();

        $this->assertInstanceOf(UploadBlob::class, $result);
    }
}
