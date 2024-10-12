<?php

namespace Atproto\Lexicons\App\Bsky\Embed;

use Atproto\Exceptions\InvalidArgumentException;

class External
{
    private string $uri;
    private string $title;
    private string $description;
    private ?Blob $blob = null;

    public function __construct(string $uri, string $title, string $description)
    {
        $this->uri($uri)
            ->title($title)
            ->description($description);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function uri(string $uri = null)
    {
        if (is_null($uri)) {
            return $this->uri;
        }

        if (! filter_var($uri, FILTER_VALIDATE_URL)) {
            throw new InvalidArgumentException("'$uri' is not a valid URL");
        }

        $this->uri = $uri;

        return $this;
    }

    public function title(string $title = null)
    {
        if (is_null($title)) {
            return $this->title;
        }

        $this->title = $title;

        return $this;
    }

    public function description(string $description = null)
    {
        if (is_null($description)) {
            return $this->description;
        }

        $this->description = $description;

        return $this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function thumb(Blob $blob = null)
    {
        if (is_null($blob)) {
            return $this->blob;
        }

        if (! str_starts_with($blob->type(), 'image/*')) {
            throw new InvalidArgumentException(sprintf(
                "'%s' is not a valid image type: %s",
                $blob->path(),
                $blob->type()
            ));
        }

        if (1000000 < $blob->size()) {
            throw new InvalidArgumentException(sprintf(
                "'%s' size is too big than maximum allowed: %d",
                $blob->path(),
                $blob->size()
            ));
        }

        $this->blob = $blob;

        return $this;
    }
}
