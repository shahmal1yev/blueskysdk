<?php

namespace Atproto\Exceptions;

use Exception;

/**
 * Class cURLException
 *
 * Exception thrown when a cURL request encounters an error.
 */
class cURLException extends BlueskyException
{
    /**
     * Construct the exception.
     *
     * @param string $message The error message
     * @param int $code The error code
     * @param Exception|null $previous The previous exception
     */
    public function __construct($message = "", $code = 0, $previous = null)
    {
        parent::__construct("cURL Exception. $message", $code, $previous);
    }
}
