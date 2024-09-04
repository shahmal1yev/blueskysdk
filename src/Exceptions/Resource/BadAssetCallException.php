<?php

namespace Atproto\Exceptions\Resource;

use Atproto\Exceptions\BlueskyException;
use Throwable;

class BadAssetCallException extends BlueskyException
{
    protected $message = "asset does not exists on resource.";

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($this->message($message), $code, $previous);
    }

    private function message(string $asset) : string
    {
        if ($asset) {
            return "'$asset' $this->message";
        }

        return ucfirst($this->message);
    }
}