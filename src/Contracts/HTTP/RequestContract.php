<?php

namespace Atproto\Contracts\HTTP;

use Atproto\Contracts\HTTP\Resources\ResourceContract;

/**
 * Interface RequestContract
 *
 * This interface defines the contract for an HTTP request.
 */
interface RequestContract
{
    /**
     * Get the body of the request.
     *
     * @return mixed The request body
     */
    public function getBody();

    /**
     * Get the headers of the request.
     *
     * @return array The request headers
     */
    public function getHeaders();

    /**
     * Get the HTTP method of the request.
     *
     * @return string The HTTP method (e.g., GET, POST)
     */
    public function getMethod();

    /**
     * Get the URI of the request.
     *
     * @return string The request URI
     */
    public function getUri();

    /**
     * Check if authentication is required for the request.
     *
     * @return bool True if authentication is required, false otherwise
     */
    public function authRequired();

    /**
     * Boot the request with authentication response.
     *
     * @param mixed $authResponse The authentication response
     */
    public function boot($authResponse);
}
