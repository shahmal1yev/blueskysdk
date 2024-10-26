<?php

namespace Tests\Unit\Lexicons\App\Bsky\Embed;

use Atproto\DataModel\Blob\Blob;
use PHPUnit\Framework\MockObject\MockObject;

trait FileMock
{
    private int $size = 2000;
    private string $path = 'path';
    private string $type = 'text/vtt';

    /**
     * @return (Blob&MockObject)
     */
    private function createMockFile()
    {
        $file = $this->getMockBuilder(Blob::class)
            ->disableOriginalConstructor()
            ->getMock();

        $file->expects($this->any())
            ->method('mimeType')
            ->will($this->returnCallback(fn () => $this->type));

        $file->expects($this->any())
            ->method('size')
            ->will($this->returnCallback(fn () => $this->size));

        return $file;
    }
}
