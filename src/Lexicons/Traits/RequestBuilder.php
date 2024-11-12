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
            $uri = rtrim($this->getUri(), "/");
            $target = "/" . trim($this->getRequestTarget(), "/");

            if (false === $pos = strpos($uri, '?')) {
                $uri = $uri . $target;
            } else {
                $uri = substr_replace($uri, $target, $pos, 0);
            }

            return $uri;
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

    public function header(string $name, string $value = null)
    {
        if (is_null($value)) {
            return $this->getHeaderLine($name);
        }

        return $this->withAddedHeader($name, $value);
    }

    public function parameter(string $name, $value = null)
    {
        $content = json_decode($this->getBody()->getContents(), true);

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
            return $this->queryParameters[$name] ?? null;
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

        $_this->request = $this->withUri($_this->getUri()->withQuery($queryParameters));

        return $_this;
    }

    private function updateQuery(string $name, $value): string
    {
        parse_str($this->getUri()->getQuery(), $query);

        $query[$name] = $value;

        return http_build_query(
            $query,
            '',
            null,
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
            $_this->request = $this->withHeader($headerName, $headerValue);
        }

        return $_this;
    }

    private function emitHeaders(): array
    {
        $result = [];

        foreach($this->getHeaders() as $headerName => $headers) {
            foreach($headers as $header) {
                $headers[] = "$headerName: $header";
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
            return json_decode($this->getBody()->getContents(), true);
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
            null,
            PHP_QUERY_RFC3986
        )));

        return $_this;
    }
}
