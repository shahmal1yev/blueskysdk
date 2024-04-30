<?php

namespace Atproto\Auth\Strategies;

use Atproto\Contracts\AuthStrategyContract;
use Atproto\Exceptions\Auth\AuthFailed;
use \InvalidArgumentException;

/**
 * Class PasswordAuthentication
 *
 * This class implements the AuthStrategyContract interface for password authentication strategy.
 */
class PasswordAuthentication implements AuthStrategyContract
{
    /**
     * @var array Credentials for authentication
     */
    private $credentials;

    /**
     * Initialize the authentication strategy with provided credentials.
     *
     * @param array $credentials The credentials for authentication
     * @throws InvalidArgumentException If required credentials are missing
     */
    private function init(array $credentials)
    {
        $this->validateCredentials($credentials);
        $this->credentials = $this->filterCredentials($credentials);
    }

    /**
     * Validate the provided credentials.
     *
     * @param array $credentials The credentials to validate
     * @throws InvalidArgumentException If required credentials are missing
     */
    private function validateCredentials(array $credentials)
    {
        if (!isset($credentials['identifier']) || !isset($credentials['password'])) {
            throw new InvalidArgumentException("Both 'identifier' and 'password' must be provided in credentials");
        }
    }

    /**
     * Filter the provided credentials to contain only necessary keys.
     *
     * @param array $credentials The credentials to filter
     * @return array Filtered credentials containing only 'identifier' and 'password'
     */
    private function filterCredentials(array $credentials)
    {
        return array_intersect_key(
            $credentials,
            array_flip(['identifier', 'password'])
        );
    }

    /**
     * Authenticate using the provided credentials.
     *
     * @param array $credentials The credentials for authentication
     * @return mixed The authentication result
     * @throws AuthFailed If authentication fails
     */
    public function authenticate(array $credentials)
    {
        $this->init($credentials);

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => 'https://bsky.social/xrpc/com.atproto.server.createSession',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($this->credentials),
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Accept: application/json',
            ],
        ]);

        $response = curl_exec($ch);
        $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($statusCode !== 200)
            throw new AuthFailed("Authentication failed: " . ($response ? json_decode($response)->message : "Unknown error"));

        return json_decode($response);
    }
}
