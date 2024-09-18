<?php

namespace Atproto\Exceptions\Http;

use Atproto\Exceptions\BlueskyException;

class MissingFieldProvidedException extends BlueskyException
{
    public function __construct($message = "", $code = 0, $previous = null)
    {
        parent::__construct("Missing provided fields: $message", $code, $previous);
    }
}