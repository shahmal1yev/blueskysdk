<?php

namespace Tests\Unit\Lexicons\App\Bsky\Embed;

use Atproto\Lexicons\App\Bsky\Embed\File;
use PHPUnit\Framework\MockObject\MockObject;

trait FileMocking
{
    private int $size = 2000;
    private string $path = 'path';
    private string $blob = 'blob';
    private string $type = 'text/vtt';

    /**
     * @return (File&MockObject)
     */
    private function createMockFile()
    {
        $file = $this->getMockBuilder(File::class)
            ->disableOriginalConstructor()
            ->getMock();

        $file->expects($this->any())
            ->method('path')
            ->will($this->returnCallback(fn() => $this->path));

        $file->expects($this->any())
            ->method('type')
            ->will($this->returnCallback(fn() => $this->type));

        $file->expects($this->any())
            ->method('size')
            ->will($this->returnCallback(fn() => $this->size));

        $file->expects($this->any())
            ->method('blob')
            ->will($this->returnCallback(fn() => $this->blob));

        return $file;
    }
}