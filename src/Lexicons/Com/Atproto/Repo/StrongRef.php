<?php

namespace Atproto\Lexicons\Com\Atproto\Repo;

use Atproto\Contracts\LexiconContract;
use Atproto\Lexicons\Traits\Serializable;

class StrongRef implements LexiconContract
{
    use Serializable;

    private string $uri;
    private string $cid;

    public function __construct(string $uri, string $cid)
    {
        $this->uri($uri)
            ->cid($cid);
    }

    public function uri(string $uri = null)
    {
        if (is_null($uri)) {
            return $this->uri;
        }

        $this->uri = $uri;

        return $this;
    }

    public function cid(string $cid = null)
    {
        if (is_null($cid)) {
            return $this->cid;
        }

        $this->cid = $cid;

        return $this;
    }

    public function jsonSerialize(): array
    {
        return [
            'uri' => $this->uri,
            'cid' => $this->cid,
        ];
    }
}
