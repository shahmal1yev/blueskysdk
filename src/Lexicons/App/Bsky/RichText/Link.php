<?php

namespace Atproto\Lexicons\App\Bsky\RichText;

use Atproto\Contracts\Lexicons\App\Bsky\RichText\FacetContract;
use Atproto\Exceptions\InvalidArgumentException;

class Link extends FeatureAbstract
{
    private string $url;

    /**
     * @throws InvalidArgumentException
     */
    public function __construct(string $url)
    {
        if (filter_var($url, FILTER_VALIDATE_URL) === false) {
            throw new InvalidArgumentException("Invalid URI: $url");
        }

        $this->url = $url;
    }

    public function schema(): array
    {
        return [
            "uri" => $this->url,
        ];
    }

    public function type(): string
    {
        return "link";
    }
}
