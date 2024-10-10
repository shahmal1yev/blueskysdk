<?php

namespace Tests\Unit\Lexicons\App\Bsky\Embed;

use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\App\Bsky\Embed\Caption;
use PHPUnit\Framework\TestCase;

class CaptionTest extends TestCase
{
    use FileMocking;

    private Caption $caption;
    private array $dependencies = ['lang' => 'lang'];

    /**
     * @throws InvalidArgumentException
     */
    protected function setUp(): void
    {
        $file = $this->createMockFile();

        $this->dependencies['file'] = $file;

        $this->caption = $this->createCaption();
    }

    /**
     * @return Caption
     * @throws InvalidArgumentException
     */
    private function createCaption(): Caption
    {
        return new Caption(...array_values($this->dependencies));
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testFile()
    {
        $actual = $this->caption->file();
        $this->assertSame($this->dependencies['file'], $actual);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function testSetFile(): void
    {
        $expected = clone $this->dependencies['file'];

        $this->assertFalse(spl_object_hash($expected) === spl_object_hash($this->caption->file()));

        $this->caption->file($expected);
        $actual = $this->caption->file();

        $this->assertSame($expected, $actual);
    }

    public function testLang()
    {
        $actual = $this->caption->lang();
        $expected = $this->dependencies['lang'];

        $this->assertSame($expected, $actual);
    }

    public function testSetLang(): void
    {
        $expected = "language";
        $this->caption->lang($expected);
        $actual = $this->caption->lang();

        $this->assertSame($expected, $actual);
    }

    public function testJsonSerialize()
    {
        $expected = [
            'file' => $this->blob,
            'lang' => $this->dependencies['lang'],
        ];

        $actual = json_decode($this->caption, true);

        $this->assertFalse(is_bool($actual));
        $this->assertEquals($expected, $actual);
    }

    public function test__constructThrowsInvalidArgumentWhenPassedInvalidFileType(): void
    {
        $this->type = 'image/png';

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($this->dependencies['file']->path()." is not a text/vtt file.");

        $this->createCaption();
    }

    public function test__constructorThrowsExceptionWhePassedUnacceptableSizedFile(): void
    {
        $this->size = 20001;

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage($this->dependencies['file']->path()." is too large. Max size: 20000");

        $this->createCaption();
    }
}
