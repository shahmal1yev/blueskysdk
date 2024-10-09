<?php

namespace Tests\Lexicons\App\Bsky\Embed;

use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\App\Bsky\Embed\File;
use PHPUnit\Framework\TestCase;

class FileTest extends TestCase
{
    private string $testFilePath;
    private string $unreadableFilePath;
    private string $nonFilePath;
    private File $fileInstance;

    /**
     * @throws InvalidArgumentException
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->testFilePath = tempnam(sys_get_temp_dir(), 'testfile');
        file_put_contents($this->testFilePath, 'This is a test file.');
        $this->fileInstance = new File($this->testFilePath);

        $this->unreadableFilePath = tempnam(sys_get_temp_dir(), 'unreadable');
        file_put_contents($this->unreadableFilePath, 'This is an unreadable file.');
        chmod($this->unreadableFilePath, 0000);

        $this->nonFilePath = sys_get_temp_dir() . '/nonFile' . uniqid();
        mkdir($this->nonFilePath, 0777, true);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        if (file_exists($this->testFilePath)) {
            unlink($this->testFilePath);
        }

        if (file_exists($this->unreadableFilePath)) {
            chmod($this->unreadableFilePath, 0644);
            unlink($this->unreadableFilePath);
        }

        if (is_dir($this->nonFilePath)) {
            rmdir($this->nonFilePath);
        }
    }

    public function testFileSize(): void
    {
        $expectedSize = filesize($this->testFilePath);
        $this->assertEquals($expectedSize, $this->fileInstance->size());
    }

    public function testMimeType(): void
    {
        $expectedType = mime_content_type($this->testFilePath);
        $this->assertEquals($expectedType, $this->fileInstance->type());
    }

    public function testFileBlob(): void
    {
        $expectedContent = file_get_contents($this->testFilePath);
        $this->assertEquals($expectedContent, $this->fileInstance->blob());
    }

    public function testToStringMethod(): void
    {
        $expectedContent = file_get_contents($this->testFilePath);
        $this->assertEquals($expectedContent, (string) $this->fileInstance);
    }

    public function testConstructorThrowsExceptionWhenPassedUnreadableFilePath(): void
    {
        if (function_exists('posix_geteuid') && posix_geteuid() === 0) {
            $this->markTestSkipped('Test skipped because it is running as root.');
        }

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("$this->unreadableFilePath is not readable.");

        $this->assertFalse(is_readable($this->unreadableFilePath), 'File should not be readable.');

        new File($this->unreadableFilePath);
    }

    public function testConstructorThrowsExceptionWhenPassedNonFilePath(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("$this->nonFilePath is not a file.");

        new File($this->nonFilePath);
    }
}
