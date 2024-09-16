<?php

namespace Tests\Unit\HTTP\API\Requests\Com\Atproto\Repo;

use Atproto\Exceptions\Http\MissingProvidedFieldException;
use Atproto\HTTP\API\Requests\Com\Atproto\Repo\UploadBlob;
use PHPUnit\Framework\TestCase;
use Tests\Supports\Reflection;

class UploadBlobTest extends TestCase
{
    use Reflection;

    private UploadBlob $uploadBlob;

    protected function setUp(): void
    {
        parent::setUp();
        $this->uploadBlob = new UploadBlob();
    }

    public function testBlobMethodSetsAndReturnsValue(): void
    {
        $blobData = 'test blob data';

        $result = $this->uploadBlob->blob($blobData);

        $this->assertSame($this->uploadBlob, $result);
        $this->assertSame($blobData, $this->uploadBlob->blob());
    }

    public function testTokenMethodSetsAndReturnsValue(): void
    {
        $token = 'test_token';

        $result = $this->uploadBlob->token($token);

        $this->assertSame($this->uploadBlob, $result);
        $this->assertSame($token, $this->uploadBlob->token());
    }

    public function testTokenMethodSetsAuthorizationHeader(): void
    {
        $token = 'test_token';

        $this->uploadBlob->token($token);

        $headers = $this->getPropertyValue('headers', $this->uploadBlob);

        $this->assertArrayHasKey('Authorization', $headers);
        $this->assertSame("Bearer $token", $headers['Authorization']);
    }

    public function testBuildThrowsExceptionWhenBlobIsMissing(): void
    {
        $this->expectException(MissingProvidedFieldException::class);
        $this->expectExceptionMessage('blob');

        $this->uploadBlob->token('test_token')->build();
    }

    public function testBuildThrowsExceptionWhenTokenIsMissing(): void
    {
        $this->expectException(MissingProvidedFieldException::class);
        $this->expectExceptionMessage('token');

        $this->uploadBlob->blob('test_blob_data')->build();
    }

    public function testBuildReturnsInstanceWhenAllFieldsAreSet(): void
    {
        $result = $this->uploadBlob
            ->blob('test_blob_data')
            ->token('test_token')
            ->build();

        $this->assertInstanceOf(UploadBlob::class, $result);
    }
}