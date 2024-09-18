<?php

namespace Atproto\Exceptions\Http\Response;

use Atproto\Exceptions\BlueskyException;

class AuthMissingException extends BlueskyException
{
    public function __construct($message = "Authentication Required", $code = 401, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}