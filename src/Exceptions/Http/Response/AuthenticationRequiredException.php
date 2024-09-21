<?php

namespace Atproto\Exceptions\Http\Response;

use Atproto\Exceptions\BlueskyException;

class AuthenticationRequiredException extends BlueskyException
{
    public function __construct($message = "Invalid identifier or password", $code = 401, $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
