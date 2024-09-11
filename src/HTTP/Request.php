<?php

namespace Atproto\HTTP;

class Request
{
    protected string $url = '';
    protected string $path = '';
    protected string $method = 'GET';
    protected array $headers = [];
    protected array $parameters = [];
    protected array $queryParameters = [];

    public function url(string $url = null)
    {
        if (is_null($url)) {
            return $this->url;
        }

        $this->url = $url;

        return $this;
    }

    public function path(string $path = null)
    {
        if (is_null($path)) {
            return $this->path;
        }

        $this->path = $path;

        return $this;
    }

    public function method(string $method = null)
    {
        if (is_null($method)) {
            return $this->method;
        }

        $this->method = $method;

        return $this;
    }

    public function header(string $name, string $value = null)
    {
        if (is_null($value)) {
            return $this->headers[$name] ?? null;
        }

        $this->headers[$name] = $value;

        return $this;
    }

    public function parameter(string $name, string $value = null)
    {
        if (is_null($value)) {
            return $this->parameters[$name] ?? null;
        }

        $this->parameters[$name] = $value;

        return $this;
    }

    public function queryParameter(string $name, string $value = null)
    {
        if (is_null($value)) {
            return $this->queryParameters[$name] ?? null;
        }

        $this->queryParameters[$name] = $value;

        return $this;
    }

    public function headers($headers = null)
    {
        if (is_bool($headers) && $headers) {
            return array_map(
                fn ($name, $value) => "$name: $value",
                array_keys($this->headers),
                array_values($this->headers)
            );
        }

        if (is_null($headers)) {
            return $this->headers;
        }

        $this->headers = $headers;

        return $this;
    }

    public function parameters($parameters = null)
    {
        if (is_bool($parameters) && $parameters) {
            return json_encode($this->parameters);
        }

        if (is_null($parameters)) {
            return $this->parameters;
        }

        $this->parameters = $parameters;

        return $this;
    }

    public function queryParameters($queryParameters = null)
    {
        if (is_bool($queryParameters) && $queryParameters) {
            return http_build_query($this->queryParameters);
        }

        if (is_null($queryParameters)) {
            return $this->queryParameters;
        }

        $this->queryParameters = $queryParameters;

        return $this;
    }
}