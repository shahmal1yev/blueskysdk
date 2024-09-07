<?php

namespace Atproto\Clients;

use Atproto\API\Traits\ResourceSupport;
use Atproto\Auth\Strategies\PasswordAuthentication;
use Atproto\Contracts\AuthStrategyContract;
use Atproto\Contracts\ClientContract;
use Atproto\Contracts\HTTP\RequestContract;
use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Exceptions\Auth\AuthFailed;
use Atproto\Exceptions\Auth\AuthRequired;
use Atproto\Exceptions\cURLException;
use Atproto\Exceptions\Http\InvalidRequestException;
use Atproto\Exceptions\Http\Token\ExpiredTokenException;
use Atproto\Exceptions\Http\Token\InvalidTokenException;
use Atproto\Exceptions\Http\UnsupportedHTTPMethod;
use RuntimeException;

/**
 * Class BlueskyClient
 *
 * This class implements the ClientContract interface for making requests to the Bluesky API.
 */
class BlueskyClient implements ClientContract
{
    /**
     * @var string The base URL of the Bluesky API
     */
    private $url;

    /**
     * @var AuthStrategyContract The authentication strategy to be used
     */
    private $authStrategy;

    /**
     * @var mixed The authentication result
     */
    private $authenticated;

    /** @var RequestContract $request The request object */
    private $request;

    /**
     * BlueskyClient constructor.
     *
     * @param RequestContract $requestContract The request object
     * @param string $url The base URL of the Bluesky API
     */
    public function __construct(
        RequestContract $requestContract,
        $url = 'https://bsky.social/xrpc'
    )
    {
        $this->url = $url;
        $this->request = $requestContract;
        $this->authenticated = (object) [];
        $this->authStrategy = new PasswordAuthentication;
    }

    /**
     * Set the authentication strategy for the client.
     *
     * @param AuthStrategyContract $strategyContract The authentication strategy
     * @return $this
     *
     * @deprecated This method is deprecated and will be removed in a future version.
     * Authentication should be handled directly via `authenticate()` with credentials.
     */
    public function setStrategy(AuthStrategyContract $strategyContract)
    {
        $this->authStrategy = $strategyContract;
        return $this;
    }

    /**
     * Set the request object for the client.
     *
     * @param RequestContract $requestContract The request object
     * @return $this
     */
    public function setRequest(RequestContract $requestContract)
    {
        $this->request = $requestContract;
        return $this;
    }

    /**
     * Authenticate the client with provided credentials.
     *
     * @param array $credentials The authentication credentials
     * @return mixed The authentication result
     * @throws RuntimeException If $authStrategy is not set
     * @throws AuthFailed If authentication failed
     */
    public function authenticate($credentials)
    {
        if (! $this->authStrategy) {
            throw new RuntimeException("You must set an authentication strategy first");
        }

        $this->authenticated = $this->authStrategy
            ->authenticate($credentials);

        return $this->authenticated;
    }

    /**
     * Execute the request.
     *
     * @return object The response from the API
     * @throws cURLException If cURL request fails
     * @throws InvalidRequestException If the API request is invalid
     * @throws InvalidTokenException If the token used for authentication is invalid
     * @throws ExpiredTokenException If the token used for authentication has expired
     * @throws AuthRequired If authentication is required for the request but not provided
     * @throws UnsupportedHTTPMethod If the HTTP method specified in the request is not supported
     *
     * @deprecated This method will be renamed in the future for simplicity and to shorten method names. Use 'send()'
     * instead.
     */
    public function execute(): object
    {
        if ($this->request->authRequired() && empty($this->authenticated)) {
            throw new AuthRequired("You must be authenticated to use this method");
        }

        $this->request->boot($this->authenticated);

        return $this->sendRequest($this->request);
    }

    /**
     * @throws UnsupportedHTTPMethod
     * @throws cURLException
     * @throws AuthRequired
     * @throws InvalidRequestException
     * @throws InvalidTokenException
     * @throws ExpiredTokenException
     *
     * @return ResourceContract|object
     */
    public function send(): object
    {
        if (! in_array(ResourceSupport::class, class_uses($this->request))) {
            return $this->execute();
        }

        $response = json_decode(
            json_encode($this->execute()),
            true
        );

        return $this->request->resource($response);
    }

    /**
     * Get the request object associated with this client.
     *
     * @return RequestContract The request object
     *
     * @deprecated This method will be renamed in the future for simplicity and to shorten method names. Use 'request()' instead.
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Get the request object associated with this client.
     *
     * @return RequestContract $request
     */
    public function request()
    {
        return $this->request;
    }

    /**
     * Send the API request.
     *
     * @param RequestContract $request The request object
     * @return object The response from the API
     * @throws cURLException If cURL request fails
     * @throws InvalidTokenException If the token used for authentication is invalid
     * @throws InvalidRequestException If the API request is invalid
     * @throws ExpiredTokenException If the token used for authentication has expired
     * @throws UnsupportedHTTPMethod If the HTTP method specified in the request is not supported
     */
    private function sendRequest($request): object
    {
        $curl = curl_init();

        $headers = $request->getHeaders();
        array_walk($headers, function (&$value, $key) {
            $value = sprintf('%s: %s', $key, $value);
        });

        curl_setopt_array($curl, [
            CURLOPT_URL => $this->url . $request->getURI(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
        ]);

        $this->setRequestMethod($curl, $request);

        $response = curl_exec($curl);
        curl_close($curl);

        if (curl_errno($curl)) {
            throw new cURLException(curl_error($curl));
        }

        $response = json_decode($response);

        if (curl_getinfo($curl, CURLINFO_HTTP_CODE) != 200) {
            switch ($response->error) {
                case "InvalidToken":
                    throw new InvalidTokenException(
                        $response->message
                    );

                case "InvalidRequest":
                    throw new InvalidRequestException(
                        $response->message
                    );

                case "ExpiredToken":
                    throw new ExpiredTokenException(
                        $response->message
                    );
            }
        }

        return $response;
    }

    /**
     * Sets the request method for the cURL handle based on the HTTP method specified in the request object.
     *
     * @param resource $curl    The cURL handle
     * @param RequestContract $request The request object
     *
     * @throws UnsupportedHTTPMethod if the HTTP method specified in the request is not supported
     */
    private function setRequestMethod($curl, $request)
    {
        switch ($request->getMethod()) {
            case "POST":
                curl_setopt_array($curl, [
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $request->getBody(),
                ]);
                break;
            case "GET":
                curl_setopt(
                    $curl,
                    CURLOPT_URL,
                    sprintf(
                        '%s%s?%s',
                        $this->url,
                        $request->getUri(),
                        http_build_query($request->getBody())
                    )
                );
                break;
            default:
                throw new UnsupportedHTTPMethod(
                    "The package does not support this method: " . $request->getMethod()
                );
        }
    }
}
