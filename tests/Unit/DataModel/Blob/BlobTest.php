<?php

namespace Tests\Unit\DataModel\Blob;

use Atproto\Contracts\DataModel\BlobContract;
use Atproto\DataModel\Blob\Blob;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\IPFS\CID\CID;
use Atproto\IPFS\MultiFormats\MultiBase\MultiBase;
use Atproto\IPFS\MultiFormats\MultiCodec;
use Atproto\Support\FileSupport;
use finfo;
use PHPUnit\Framework\TestCase;

class BlobTest extends TestCase
{
    private static string $tmpFilePath;
    private static string $tmpDirPath;
    private const FILE_PREFIX = '__BlobTest__';

    public static function setUpBeforeClass(): void
    {
        self::$tmpFilePath = (function () {
            $fullPath = tempnam(sys_get_temp_dir(), self::FILE_PREFIX);
            file_put_contents($fullPath, 'content');

            return $fullPath;
        })();
        self::$tmpDirPath = (function () {
            $tmpDir = sys_get_temp_dir();
            $fullPath = $tmpDir.DIRECTORY_SEPARATOR.self::FILE_PREFIX.uniqid();
            mkdir($fullPath);

            return $fullPath;
        })();
    }

    public function testTemps(): void
    {
        if (posix_getuid() === 0) {
            $this->markTestSkipped("Skip checking temporary files: tests are running by root");
        }

        $this->assertTrue(is_dir(self::$tmpDirPath));
        $this->assertTrue(is_file(self::$tmpFilePath));
        $this->assertIsReadable(self::$tmpFilePath);
    }

    public static function tearDownAfterClass(): void
    {
        rmdir(self::$tmpDirPath);

        foreach(scandir(sys_get_temp_dir()) as $file) {
            if (strpos($file, self::FILE_PREFIX) === 0) {
                unlink(sys_get_temp_dir().DIRECTORY_SEPARATOR.$file);
            }
        }
    }

    public function testBlobConstructorThrowsExceptionWhenPassedInvalidBinary(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('$binary must be a binary');

        Blob::viaBinary('invalid binary');
    }

    public function testSizeReturnsSizeOfBinary(): void
    {
        $binary = random_bytes(1024);

        $blob = Blob::viaBinary($binary);

        $actual = $blob->size();
        $expected = 1024;

        $this->assertSame($expected, $actual);
    }

    public function testMimeTypeReturnsMimeTypeOfBinary(): void
    {
        $binary = random_bytes(1024);

        $blob = Blob::viaBinary($binary);

        $actual = $blob->mimeType();
        $expected = (new finfo(FILEINFO_MIME_TYPE))->buffer($binary);

        $this->assertSame($expected, $actual);
    }

    public function testViaFileConstructorWorkingCorrectly(): void
    {
        $file = new FileSupport(self::$tmpFilePath);
        $blob = Blob::viaFile($file);

        $this->assertSame($blob->size(), $file->getFileSize());
        $this->assertSame($blob->mimeType(), $file->getMimeType());
        $this->assertSame(
            $blob->link(),
            (new CID(MultiCodec::get('raw'), MultiBase::get('base32'), $file->getBlob()))->__toString()
        );
    }

    /** @dataProvider expectedSerializations */
    public function testJsonSerialize(BlobContract $blob, array $expectedSerialization): void
    {
        $this->assertSame($expectedSerialization, json_decode($blob, true));
    }

    public function expectedSerializations(): array
    {
        $content = 'content';

        $file = tempnam(sys_get_temp_dir(), self::FILE_PREFIX);

        $this->assertIsReadable($file);
        file_put_contents($file, $content);

        $file = new FileSupport($file);
        $viaFile = Blob::viaFile($file);

        $binary = random_bytes(strlen($content));
        $viaBinary = Blob::viaBinary($binary);

        return [
            [$viaFile, $this->createSchema($viaFile->link(), $file->getMimeType())],
            [$viaBinary, $this->createSchema($viaBinary->link(), (new finfo(FILEINFO_MIME_TYPE))->buffer($binary))],
        ];
    }

    public function createSchema(string $link, string $mimeType = null): array
    {
        return [
            '$type' => 'blob',
            'ref' => [
                '$link' => $link,
            ],
            'mimeType' => $mimeType ?: 'application/octet-stream',
            'size' => 7,
        ];
    }
}
