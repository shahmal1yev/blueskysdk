<?php

namespace Tests\Unit\Lexicons\App\Bsky\Embed;

use Atproto\DataModel\Blob\Blob;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Support\FileSupport;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    private string $testFilePath;
    private string $testDirPath;
    private Blob $fileInstance;
    private const FILE_PREFIX = '__FileTest__';

    protected function setUp(): void
    {
        // Create a temporary test file
        $this->testFilePath = tempnam(sys_get_temp_dir(), self::FILE_PREFIX);
        file_put_contents($this->testFilePath, 'test content');

        // Create a temporary test directory
        $this->testDirPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . self::FILE_PREFIX . uniqid();
        mkdir($this->testDirPath);

        // Create file instance using the factory method
        $fileSupport = new FileSupport($this->testFilePath);
        $this->fileInstance = Blob::viaFile($fileSupport);
    }

    protected function tearDown(): void
    {
        // Clean up test files
        if (file_exists($this->testFilePath)) {
            unlink($this->testFilePath);
        }
        if (is_dir($this->testDirPath)) {
            rmdir($this->testDirPath);
        }
    }

    public function testConstructorThrowsExceptionForNonExistentFile(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $nonExistentFile = new FileSupport('/path/to/nonexistent/file');
        Blob::viaFile($nonExistentFile);
    }

    public function testConstructorThrowsExceptionForDirectory(): void
    {
        $this->expectException(InvalidArgumentException::class);

        $directory = new FileSupport($this->testDirPath);
        Blob::viaFile($directory);
    }

    public function testSize(): void
    {
        $expectedSize = filesize($this->testFilePath);
        $this->assertEquals($expectedSize, $this->fileInstance->size());
    }

    public function testMimeType(): void
    {
        $expectedType = mime_content_type($this->testFilePath);
        $this->assertEquals($expectedType, $this->fileInstance->mimeType());
    }

    public function testJsonSerialize(): void
    {
        $serialized = json_decode(json_encode($this->fileInstance), true);

        $this->assertArrayHasKey('$type', $serialized);
        $this->assertArrayHasKey('ref', $serialized);
        $this->assertArrayHasKey('mimeType', $serialized);
        $this->assertArrayHasKey('size', $serialized);

        $this->assertEquals('blob', $serialized['$type']);
    }
}
