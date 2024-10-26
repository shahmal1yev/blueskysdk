<?php

namespace Tests\Unit\Lexicons\App\Bsky\Embed;

use Atproto\DataModel\Blob\Blob;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\App\Bsky\Embed\External;
use PHPUnit\Framework\TestCase;

class ExternalTest extends TestCase
{
    private External $external;

    private Blob $blob;
    private int $maximumAllowedBlobSize = 1000000;
    private string $allowedMimes = 'image/*';

    public function setUp(): void
    {
        $this->external = new External('https://shahmal1yev.dev', 'foo', 'bar');

        $this->blob = $this->getMockBuilder(Blob::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->blob->expects($this->any())
            ->method('size')
            ->will($this->returnCallback(fn () => $this->maximumAllowedBlobSize));

        $this->blob->expects($this->any())
            ->method('mimeType')
            ->will($this->returnCallback(fn () => $this->allowedMimes));
    }

    public function testDescription()
    {
        $this->assertSame('bar', $this->external->description());
        $this->external->description('foo');
        $this->assertSame('foo', $this->external->description());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testThumb()
    {
        $this->external->thumb($this->blob);
        $this->assertSame($this->blob, $this->external->thumb());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testThumbReturnsNull(): void
    {
        $this->assertNull($this->external->thumb());
    }

    public function testThumbThrowsExceptionWhenPassedBlobWithInvalidMimeType(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('is not a valid image type: invalid/*');

        $this->allowedMimes = 'invalid/*';

        $this->external->thumb($this->blob);
    }

    public function testThumbThrowsExceptionWhenPassedBlobExceedsMaximumAllowedSize(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('size is too big than maximum allowed:');

        $this->maximumAllowedBlobSize = ++$this->maximumAllowedBlobSize;

        $this->external->thumb($this->blob);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testUri()
    {
        $this->assertSame('https://shahmal1yev.dev', $this->external->uri());
        $this->external->uri('https://google.com');
        $this->assertSame('https://google.com', $this->external->uri());
    }

    public function testUriThrowsExceptionWhenPassedInvalidUrl(): void
    {
        $trigger = 'invalid url';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("'$trigger' is not a valid URL");

        $this->external->uri($trigger);
    }

    public function testTitle()
    {
        $this->assertSame('foo', $this->external->title());
        $this->external->title('bar');
        $this->assertSame('bar', $this->external->title());
    }

    public function testJsonSerializeWithoutSetBlob(): void
    {
        $expected = [
            'uri' => 'https://shahmal1yev.dev',
            'title' => 'foo',
            'description' => 'bar',
        ];

        $this->assertSame($expected, json_decode($this->external, true));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testJsonSerializeWithSetBlob(): void
    {
        $this->external->thumb($this->blob);

        $expected = [
            'uri' => 'https://shahmal1yev.dev',
            'title' => 'foo',
            'description' => 'bar',
            'blob' => $this->blob->jsonSerialize(),
        ];

        $this->assertSame($expected, json_decode($this->external, true));
    }
}
