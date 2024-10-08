<?php

namespace Atproto\Lexicons\App\Bsky\Embed;

use Atproto\Exceptions\InvalidArgumentException;
use JsonSerializable;

class Caption implements JsonSerializable
{
    private const MAX_SIZE = 20000;

    private string $lang;
    private File $file;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $lang, File $file)
    {
        $this->lang($lang);
        $this->file($file);
    }

    public function lang(string $lang = null): string
    {
        if (is_null($lang)) {
            return $this->lang;
        }

        $this->lang = $lang;

        return $this->lang;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function file(File $file = null)
    {
        if (is_null($file)) {
            return $this->file;
        }

        if ($file->size() > self::MAX_SIZE) {
            throw new InvalidArgumentException($file->path().' is too large.');
        }

        if ($file->type() !== 'text/vtt') {
            throw new InvalidArgumentException($file->path().' is not a text vtt.');
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
            'file'  => $this->file()->blob(),
        ];
    }
}
