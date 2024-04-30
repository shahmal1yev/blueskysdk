<?php

namespace Atproto\Exceptions\Http\Request;

use Exception;

/**
 * Class RequestBodyHasMissingRequiredFields
 *
 * Exception thrown when required fields are missing in the request body.
 */
class RequestBodyHasMissingRequiredFields extends Exception
{
    /**
     * Constructor.
     *
     * @param string $message The error message.
     * @param int $code The error code.
     * @param Exception|null $previous The previous exception used for the exception chaining.
     */
    public function __construct($message, $code = 0, $previous = null)
    {
        parent::__construct("Request body has missing required fields: $message", $code, $previous);
    }
}
