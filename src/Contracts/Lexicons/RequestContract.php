<?php

namespace Atproto\Contracts\Lexicons;

use Psr\Http\Message\RequestInterface;

/**
 * Interface RequestContract
 *
 * This interface defines the contract for an HTTP request.
 */
interface RequestContract extends RequestInterface
{
    /**
     * Get the URL of the request.
     *
     * @return RequestContract|string The URL of the request
     */
    public function url($url = null);

    /**
     * Get or set the path of the request.
     *
     * @param string|null $path The path to set, or null to get the current path.
     * @return mixed|string The request path or instance for chaining
     */
    public function path(string $path = null);

    /**
     * Get or set the HTTP method of the request.
     *
     * @param string|null $method The HTTP method to set, or null to get the current method.
     * @return mixed|string The request method or instance for chaining
     */
    public function method(string $method = null);

    /**
     * Get or set a header in the request.
     *
     * @param string $name The header name
     * @param string|null $value The header value to set, or null to get the current value.
     * @return mixed|string|null The header value or instance for chaining
     */
    public function header(string $name, string $value = null);

    /**
     * Get or set a parameter in the request.
     *
     * @param string $name The parameter name
     * @param null $value The parameter value to set, or null to get the current value.
     * @return mixed|string|null The parameter value or instance for chaining
     */
    public function parameter(string $name, $value = null);

    /**
     * Get or set a query parameter in the request.
     *
     * @param string $name The query parameter name
     * @param array|string|null $value The query parameter value to set, or null to get the current value.
     * @return mixed|string|null The query parameter value or instance for chaining
     */
    public function queryParameter(string $name, $value = null);

    /**
     * Get or set multiple headers at once.
     *
     * @param bool|array|null $headers The headers to set, or null to get the current headers.
     * @return array|mixed The headers or instance for chaining
     */
    public function headers($headers = null);

    /**
     * Get or set multiple parameters at once.
     *
     * @param bool|array|null $parameters The parameters to set, or null to get the current parameters.
     * @return array|mixed The parameters or instance for chaining
     */
    public function parameters($parameters = null);

    /**
     * Get or set multiple query parameters at once.
     *
     * @param bool|array|null $queryParameters The query parameters to set, or null to get the current query parameters.
     * @return array|mixed The query parameters or instance for chaining
     */
    public function queryParameters($queryParameters = null);

    public function send();
}
