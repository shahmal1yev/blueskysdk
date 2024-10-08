<?php

namespace Atproto\Lexicons\App\Bsky\Embed;

use Atproto\Contracts\Lexicons\App\Bsky\Embed\ImageInterface;
use Atproto\Exceptions\InvalidArgumentException;

class Image implements ImageInterface
{
    private File $file;
    private string $alt;
    private ?array $aspectRatio = null;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(File $file, string $alt)
    {
        if (true !== str_starts_with($file->type(), 'image/')) {
            throw new InvalidArgumentException($file->path()." is not a valid image file.");
        }

        $this->file = $file;
        $this->alt = $alt;
    }

    public function alt(string $alt = null)
    {
        if (is_null($alt)) {
            return $this->alt;
        }

        $this->alt = $alt;

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function aspectRatio(int $width = null, int $height = null)
    {
        if (is_null($width) && is_null($height)) {
            return $this->aspectRatio;
        }

        if ($width < 1 || $height < 1) {
            throw new InvalidArgumentException("Width and height must be at least 1");
        }

        $this->aspectRatio = [
            'width'  => $width,
            'height' => $height
        ];

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function jsonSerialize(): array
    {
        return array_filter([
            'alt' => $this->alt(),
            'image' => $this->file->blob(),
            'aspectRatio' => $this->aspectRatio() ?: null,
        ]);
    }
}
