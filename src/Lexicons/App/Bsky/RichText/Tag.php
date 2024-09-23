<?php

namespace Atproto\Lexicons\App\Bsky\RichText;

use Atproto\Exceptions\InvalidArgumentException;

class Tag extends FeatureAbstract
{
    private string $tag;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $tag)
    {
        if (mb_strlen($tag, "UTF-8") > 640) {
            throw new InvalidArgumentException("Tag cannot be longer than 640 characters.");
        }

        $this->tag = $tag;
    }

    public function schema(): array
    {
        return [
            "tag" => $this->tag
        ];
    }

    public function type(): string
    {
        return "tag";
    }
}
