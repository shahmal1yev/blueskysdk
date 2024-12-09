<?php

namespace Atproto\Lexicons\App\Bsky\Embed;

use Atproto\Contracts\Lexicons\App\Bsky\Embed\EmbedInterface;
use Atproto\Contracts\Lexicons\App\Bsky\Embed\MediaContract;
use Atproto\Lexicons\Com\Atproto\Repo\StrongRef;
use Atproto\Lexicons\Traits\Lexicon;

class RecordWithMedia implements EmbedInterface
{
    use Lexicon;

    private Record $record;
    private MediaContract $media;

    public function __construct(Record $record, MediaContract $media)
    {
        $this->record($record);
        $this->media($media);
    }

    public function record(Record $record = null)
    {
        if (is_null($record)) {
            return $this->record;
        }

        $this->record = $record;

        return $this;
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
        return array_merge([
            '$type' => $this->nsid(),
            'record' => $this->record,
            'media' => $this->media,
        ]);
    }
}
