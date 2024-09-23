<?php

namespace Atproto\Lexicons\App\Bsky\RichText;

class Mention extends FeatureAbstract
{
    private string $did;

    public function __construct(string $did)
    {
        $this->did = $did;
    }

    public function schema(): array
    {
        return [
            "did" => $this->did,
        ];
    }

    public function type(): string
    {
        return "mention";
    }
}
