<?php

namespace Atproto\API\App\Bsky\Actor;

use Atproto\Contracts\HTTP\RequestContract;
use Atproto\Exceptions\Http\Request\RequestBodyHasMissingRequiredFields;

/**
 * The GetProfile class represents a request to retrieve a user's profile information from the Bluesky API.
 *
 * This class implements the RequestContract interface, providing methods to construct and handle the request.
 */
class GetProfile implements RequestContract
{
    /** @var object The request body */
    private $body;

    /** @var array The request headers */
    private $headers = [
        "Accept" => "application/json"
    ];

    /**
     * Constructs a new GetProfile instance.
     */
    public function __construct()
    {
        $this->body = (object) [
            'action' => '',
        ];
    }

    /**
     * Sets the action to be performed.
     *
     * @param string $action The action to be set
     *
     * @throws \InvalidArgumentException if $action is not a string
     */
    public function setAction($action)
    {
        if (! is_string($action))
            throw new \InvalidArgumentException("Action must be a string");

        $this->body->action = $action;
    }

    /**
     * Retrieves the action that has been set.
     *
     * @return string The action
     */
    public function getAction()
    {
        return $this->body->action;
    }

    /**
     * Sets the request headers.
     *
     * @param array $headers The headers to be set
     *
     * @return $this
     */
    public function setHeaders(array $headers)
    {
        $this->headers = array_diff_key(
            $this->headers,
            array_flip(array_keys($headers))
        );

        $this->headers = array_merge(
            $this->headers,
            $headers
        );

        return $this;
    }

    /**
     * Retrieves the request headers.
     *
     * @return array The request headers
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Retrieves the HTTP method for the request.
     *
     * @return string The HTTP method
     */
    public function getMethod()
    {
        return 'GET';
    }

    /**
     * Retrieves the URI for the request.
     *
     * @return string The URI
     */
    public function getUri()
    {
        return '/app.bsky.actor.getProfile';
    }

    /**
     * Indicates whether authentication is required for the request.
     *
     * @return bool true if authentication is required, false otherwise
     */
    public function authRequired()
    {
        return true;
    }

    /**
     * Boots the request with authentication response.
     *
     * @param mixed $authResponse The authentication response
     */
    public function boot($authResponse)
    {
        $this->headers = array_merge($this->headers, [
            'Authorization' => "Bearer $authResponse->accessJwt"
        ]);
    }

    /**
     * Retrieves the request body.
     *
     * @return mixed The request body
     *
     * @throws RequestBodyHasMissingRequiredFields if the request body is missing required fields
     */
    public function getBody()
    {
        if (! isset($this->body->blob))
            throw new RequestBodyHasMissingRequiredFields(implode(', ', ['blob']));

        return $this->body->action;
    }
}
