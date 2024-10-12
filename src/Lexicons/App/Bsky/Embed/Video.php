<?php

namespace Atproto\Lexicons\App\Bsky\Embed;

use Atproto\Contracts\Lexicons\App\Bsky\Embed\VideoInterface;
use Atproto\Contracts\Stringable;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\App\Bsky\Embed\Collections\CaptionCollection;

class Video implements VideoInterface, Stringable
{
    private Blob $file;
    private ?string $alt = null;
    private CaptionCollection $captions;
    private array $aspectRatio = [];

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(Blob $file)
    {
        if ("video/mp4" !== $file->type()) {
            throw new InvalidArgumentException($file->path()." is not a valid video file.");
        }

        $this->file = $file;

        $this->captions = new CaptionCollection();
    }

    /**
     * @throws InvalidArgumentException
     */
    public function jsonSerialize(): array
    {
        $result = array_filter([
            'alt' => $this->alt() ?: null,
            'video' => $this->file->blob(),
            'aspectRatio' => $this->aspectRatio() ?: null,
            'captions' => $this->captions()->toArray() ?: null,
        ]);

        return $result;
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
            throw new InvalidArgumentException("Width and height must be greater than 1");
        }

        $this->aspectRatio = [
            'width' => $width,
            'height' => $height
        ];

        return $this;
    }

    public function captions(CaptionCollection $captions = null)
    {
        if (is_null($captions)) {
            return $this->captions;
        }

        $this->captions = $captions;

        return $this;
    }

    public function __toString(): string
    {
        $result = json_encode($this);
        return $result;
    }
}
