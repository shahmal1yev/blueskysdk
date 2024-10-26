<?php

namespace Atproto\Lexicons\App\Bsky\Embed;

use Atproto\Contracts\Lexicons\App\Bsky\Embed\CaptionContract;
use Atproto\DataModel\Blob\Blob;
use Atproto\Exceptions\InvalidArgumentException;

class Caption implements CaptionContract
{
    private const MAX_SIZE = 20000;

    private string $lang;
    private Blob $file;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $lang, Blob $file)
    {
        $this->lang($lang);
        $this->file($file);
    }

    public function lang(string $lang = null)
    {
        if (is_null($lang)) {
            return $this->lang;
        }

        $this->lang = $lang;

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function file(Blob $file = null)
    {
        if (is_null($file)) {
            return $this->file;
        }

        if ($file->size() > self::MAX_SIZE) {
            throw new InvalidArgumentException('$file is too large. Max size: '.self::MAX_SIZE);
        }

        if ($file->mimeType() !== 'text/vtt') {
            throw new InvalidArgumentException('$file is not a text/vtt file.');
        }

        $this->file = $file;

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function jsonSerialize(): array
    {
        return [
            'lang' => $this->lang(),
            'file'  => $this->file(),
        ];
    }

    public function __toString(): string
    {
        return json_encode($this);
    }
}
