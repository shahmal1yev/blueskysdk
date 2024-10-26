<?php

namespace Tests\Unit\Lexicons\App\Bsky\Embed;

use Atproto\Contracts\Lexicons\App\Bsky\Embed\CaptionContract;
use Atproto\DataModel\Blob\Blob;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\App\Bsky\Embed\Collections\CaptionCollection;
use Atproto\Lexicons\App\Bsky\Embed\Video;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class VideoTest extends TestCase
{
    use FileMock;

    private Video $video;
    private Blob $file;

    protected function setUp(): void
    {
        parent::setUp();

        $this->type = 'video/mp4';

        $this->file = $this->createMockFile();
        $this->video = new Video($this->file);
    }

    public function testAlt(): void
    {
        $this->assertSame(null, $this->video->alt());

        $expected = 'alt text';
        $this->video->alt($expected);

        $this->assertSame($expected, $this->video->alt());
    }

    /** @dataProvider invalidAspectRatioProvider */
    public function testAspectRatioThrowsInvalidArgumentExceptionWhenPassedInvalidArguments(...$args): void
    {
        $this->expectException(InvalidArgumentException::class);

        $this->video->aspectRatio(...array_values($args));
    }

    public function invalidAspectRatioProvider(): array
    {
        return [
            [-1, 0],
            [0, -1],
            [false, 1],
            [true],
            [1]
        ];
    }

    public function testAspectRatio(): void
    {
        $this->assertSame([], $this->video->aspectRatio());

        $expected = ['width' => 1, 'height' => 2];
        $this->video->aspectRatio(...array_values($expected));

        $this->assertSame($expected, $this->video->aspectRatio());
    }

    public function testJsonSerializeReturnsCorrectSchema(): void
    {
        $expected = [
            'video' => $this->file,
        ];

        $target = new Video($this->file);

        $this->assertSame($expected, $target->jsonSerialize());

        $captions = $this->createCaptionsMock();
        $aspectRatio = $this->randAspectRatio();

        $expected = [
            'alt' => 'alt text',
            'video' => $expected['video'],
            'aspectRatio' => $aspectRatio,
            'captions' => $captions->toArray(),
        ];

        $target->captions($captions)
            ->alt($expected['alt'])
            ->aspectRatio(...array_values($expected['aspectRatio']));

        $this->assertSame($expected, $target->jsonSerialize());
    }

    /**
     * @return array
     */
    private function randAspectRatio(): array
    {
        return ['width' => rand(1, 50), 'height' => rand(1, 50)];
    }

    /**
     * @return (MockObject&CaptionCollection)
     */
    private function createCaptionsMock(): CaptionCollection
    {
        $caption = $this->getMockBuilder(CaptionContract::class)
            ->disableOriginalConstructor()
            ->getMock();

        $caption->expects($this->any())
            ->method('jsonSerialize')
            ->willReturn([
                'lang' => 'lang',
                'file' => $this->file,
            ]);

        $captions = $this->getMockBuilder(CaptionCollection::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['toArray'])
            ->getMock();

        $captions->expects($this->any())
            ->method('toArray')
            ->willReturn($caption->jsonSerialize());

        return $captions;
    }
}
