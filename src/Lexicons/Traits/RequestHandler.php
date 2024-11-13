<?php

namespace Atproto\Lexicons\Traits;

use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Exceptions\BlueskyException;
use Atproto\Exceptions\cURLException;

trait RequestHandler
{
    /** @var resource|ResponseContract $resource */
    private $resource;

    private string $reason = '';

    /** @var array $responseHeaders */
    private array $responseHeaders;

    /** @var string|array */
    private $content;


    /**
     * @throws BlueskyException
     */
    public function send(): array
    {
        $this->request();
        $this->handle();

        $response = [
            'statusCode' => curl_getinfo($this->resource, CURLINFO_HTTP_CODE),
            'headers' => $this->responseHeaders,
            'content' => $this->content,
            'protocolVersion' => curl_getinfo($this->resource, CURLINFO_HTTP_VERSION),
            'reason' => $this->reason
        ];

        $this->resource = null;

        return $response;
    }

    /**
     * @throws BlueskyException
     */
    private function handle(): void
    {
        $this->handleError();
        $this->handleReason();
        $this->handleResponse();
        $this->handleResponseError();
    }

    private function handleReason(): void
    {
        preg_match(
            '#^HTTP/1.(?:0|1) [\d]{3} (.*)$#m',
            $this->content,
            $match
        );

        $this->reason = $match[1];
    }

    /**
     * @throws BlueskyException
     */
    private function handleResponse(): void
    {
        $content = substr(
            $this->content,
            curl_getinfo($this->resource, CURLINFO_HEADER_SIZE),
        );

        $this->content = json_decode(
            $content,
            true
        ) ?: [];
    }

    /**
     * @throws BlueskyException
     */
    private function handleResponseError(): void
    {
        $statusCode = curl_getinfo($this->resource, CURLINFO_HTTP_CODE);

        if ($statusCode < 200 || $statusCode > 299) {
            $exception = "\\Atproto\\Exceptions\\Http\\Response\\{$this->content['error']}Exception";

            if (class_exists($exception)) {
                throw new $exception($this->content['message'], $statusCode);
            }

            throw new BlueskyException($this->content['message'] ?? "Unknown error.", $statusCode);
        }
    }

    private function handleResponseHeader($ch, $header): int
    {
        $length = strlen($header);
        $header = explode(':', $header, 2);

        if (count($header) < 2) {
            return $length;
        }

        $name = trim(current($header));
        $value = trim(next($header));

        if (isset($this->responseHeaders[$name])) {
            $value = array_merge($this->responseHeaders[$name], [$value]);
        }

        $this->responseHeaders[$name] = $value;

        return $length;
    }

    /**
     * @throws cURLException
     */
    private function handleError(): void
    {
        if (curl_errno($this->resource)) {
            throw new cURLException(curl_error($this->resource));
        }
    }

    private function request(): void
    {
        $this->resource = curl_init();

        curl_setopt_array($this->resource, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => true,
            CURLOPT_HTTPHEADER     => $this->headers(true),
            CURLOPT_HEADERFUNCTION => [$this, 'handleResponseHeader'],
            CURLOPT_CUSTOMREQUEST  => $this->method(),
            CURLOPT_POSTFIELDS     => $this->parameters(true),
            CURLOPT_URL            => $this->url(),
            CURLOPT_HTTP_VERSION   => $this->protocol()
        ]);

        $this->content = curl_exec($this->resource);

        return;
    }
}
