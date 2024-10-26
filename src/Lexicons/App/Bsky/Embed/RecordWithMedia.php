<?php

namespace Atproto\Lexicons\App\Bsky\Embed;

use Atproto\Contracts\Lexicons\App\Bsky\Embed\MediaContract;
use Atproto\Lexicons\Com\Atproto\Repo\StrongRef;

class RecordWithMedia extends Record
{
    private MediaContract $media;

    public function __construct(StrongRef $ref, MediaContract $media)
    {
        parent::__construct($ref);
        $this->media($media);
    }

    public function media(MediaContract $media = null)
    {
        if (is_null($media)) {
            return $this->media;
        }

        $this->media = $media;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return array_merge(parent::jsonSerialize(), [
            'media' => $this->media,
        ]);
    }
}
