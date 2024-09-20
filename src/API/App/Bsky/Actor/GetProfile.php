<?php

namespace Atproto\API\App\Bsky\Actor;

use Atproto\API\Traits\ResourceSupport;
use Atproto\Contracts\HTTP\RequestContract;
use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Exceptions\Http\Request\RequestBodyHasMissingRequiredFields;
use Atproto\Resources\App\Bsky\Actor\GetProfileResource;
use InvalidArgumentException;

/**
 * The GetProfile class represents a request to retrieve a user's profile information from the Bluesky API.
 *
 * This class implements the RequestContract interface, providing methods to construct and handle the request.
 *
 * @deprecated This class deprecated and will be removed in a future version.
 */
class GetProfile implements RequestContract
{
    use ResourceSupport;

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
            'actor' => '',
        ];
    }

    /**
     * Sets the actor to be performed.
     *
     * @param string $actor The 'actor' to be set
     *
     * @throws InvalidArgumentException if $actor is not a string
     */
    public function setActor($actor)
    {
        if (! is_string($actor)) {
            throw new InvalidArgumentException("'actor' must be a string");
        }

        $this->body->actor = $actor;
    }

    /**
     * Retrieves the 'actor' that has been set.
     *
     * @return string The 'actor' field value
     */
    public function getActor()
    {
        return $this->body->actor;
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
        if (! isset($this->body->actor)) {
            throw new RequestBodyHasMissingRequiredFields('actor');
        }

        return ['actor' => $this->body->actor];
    }

    public function resource(array $response): ResourceContract
    {
        return new GetProfileResource($response);
    }
}
