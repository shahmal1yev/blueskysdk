<?php

namespace Atproto\Lexicons\Traits;

use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Support\Arr;

trait RequestBuilder
{
    public function url($url = null, bool $preserveHost = false)
    {
        if (is_null($url)) {
            $uri = (string) $this->getUri();
            $target = $this->getRequestTarget();

            if ($target[0] !== '/') {
                $target = '/' . $target;
            }

            if (false !== $pos = strpos($uri, '?')) {
                $path = substr($uri, 0, $pos);
                $query = substr($uri, $pos);

                $path = rtrim($path, '/') . $target;

                $uri = $path . $query;
            } else {
                $uri = rtrim($uri, '/') . $target;
            }

            return $uri === '/' ? '' : $uri;
        }

        return $this->withUri($this->factory->createUri($url), $preserveHost);
    }

    public function path(string $path = null)
    {
        if (is_null($path)) {
            return $this->getRequestTarget();
        }

        return $this->withRequestTarget($path);
    }

    public function method(string $method = null)
    {
        if (is_null($method)) {
            return $this->getMethod();
        }

        return $this->withMethod($method);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function header(string $name, $value = null)
    {
        if (is_null($value)) {
            return $this->getHeaderLine($name);
        }

        try {
            return $this->withAddedHeader($name, $value);
        } catch (\InvalidArgumentException $e) {
            throw new InvalidArgumentException($e->getMessage(), $e->getCode(), $e);
        }
    }

    public function parameter(string $name, $value = null)
    {
        $content = json_decode($this->getBody()->getContents(), true) ?: [];

        if (is_null($value)) {
            return Arr::get($content, $name);
        }

        $content[$name] = $value;

        $_this = clone $this;

        $_this->request = $this->withBody($this->factory->createStream(json_encode($content)));

        return $_this;
    }

    /**
     * @throws InvalidArgumentException
     */
    public function queryParameter(string $name, $value = null)
    {
        if (is_null($value)) {
            parse_str($this->getUri()->getQuery(), $query);
            return Arr::get($query, $name);
        }

        if (! is_array($value) && ! is_string($value)) {
            throw new InvalidArgumentException('"$value" must be an array or string');
        }

        return $this->withAddedQueryParameter($name, $value);
    }

    private function withAddedQueryParameter(string $name, $value): RequestContract
    {
        $_this = clone $this;

        $queryParameters = $this->updateQuery($name, $value);

        $_this->request = $this->request->withUri($_this->getUri()->withQuery($queryParameters));

        return $_this;
    }

    private function updateQuery(string $name, $value): string
    {
        parse_str($this->getUri()->getQuery(), $query);

        $query[$name] = $value;

        return http_build_query(
            $query,
            '',
            '&',
            PHP_QUERY_RFC3986
        );
    }

    public function headers($headers = null)
    {
        if (true === $headers) {
            return $this->emitHeaders();
        }

        if (is_null($headers) || false === $headers) {
            return $this->getHeaders();
        }

        $_this = $this->headerlessRequest();

        foreach ($headers as $headerName => $headerValue) {
            $_this->request = $_this->request->withAddedHeader($headerName, $headerValue);
        }

        return $_this;
    }

    private function emitHeaders(): array
    {
        $result = [];

        foreach($this->getHeaders() as $headerName => $headers) {
            foreach($headers as $header) {
                $result[] = "$headerName: $header";
            }
        }

        return $result;
    }

    private function headerlessRequest(): RequestContract
    {
        $_this = clone $this;

        $headerNames = array_keys($_this->getHeaders());

        foreach($headerNames as $headerName) {
            $_this->request = $_this->withoutHeader($headerName);
        }

        return $_this;
    }

    public function parameters($parameters = null)
    {
        if (true === $parameters) {
            return $this->getBody()->getContents();
        }

        if (is_null($parameters) || false === $parameters) {
            return json_decode($this->getBody()->getContents(), true) ?: [];
        }

        $_this = clone $this;

        $_this->request = $this->withBody($this->factory->createStream(json_encode($parameters)));

        return $_this;
    }

    public function queryParameters($queryParameters = null)
    {
        if (true === $queryParameters) {
            return $this->getUri()->getQuery();
        }

        if (is_null($queryParameters) || false === $queryParameters) {
            parse_str($this->getUri()->getQuery(), $queryParameters);

            return $queryParameters;
        }

        $_this = clone $this;

        $_this->request = $_this->request->withUri($_this->getUri()->withQuery(http_build_query(
            $queryParameters,
            '',
            '&',
            PHP_QUERY_RFC3986
        )));

        return $_this;
    }

    public function protocol($protocol = null)
    {
        if (is_null($protocol)) {
            return $this->getProtocolVersion();
        }

        return $this->withProtocolVersion($protocol);
    }
}
