<?php

namespace Atproto\Responses\Objects;

use Atproto\Contracts\Lexicons\App\Bsky\Embed\EmbedInterface;
use Atproto\Contracts\Resources\ObjectContract;
use Atproto\Factories\EmbedFactory;
use Atproto\Support\Arr;

class EmbedObject implements ObjectContract
{
    use BaseObject;

    public function __construct(array $content)
    {
        $this->content = $content;
    }

    public function cast(): EmbedInterface
    {
        $args = array_pop($this->content);

        return EmbedFactory::make(
            Arr::get($this->content, '$type'),
            ...array_values($args)
        );
    }
}
