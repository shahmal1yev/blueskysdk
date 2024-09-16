<?php

namespace Atproto\HTTP\Traits;

use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Exceptions\BlueskyException;
use Atproto\Exceptions\cURLException;

trait RequestHandler
{
    /** @var resource|ResourceContract $resource */
    private $resource;

    /** @var array $responseHeaders */
    private array $responseHeaders;

    /** @var string|array */
    private $content;


    /**
     * @throws BlueskyException
     */
    public function send(): ResourceContract
    {
        $this->request();
        $this->handle();

        return $this->resource;
    }

    /**
     * @throws BlueskyException
     */
    private function handle(): void
    {
        $this->handleError();
        $this->handleResponse();
    }

    /**
     * @throws BlueskyException
     */
    private function handleResponse(): void
    {
        $this->content = json_decode($this->content, true);
        $this->handleResponseError();
    }

    /**
     * @throws BlueskyException
     */
    private function handleResponseError(): void
    {
        $statusCode = curl_getinfo($this->resource, CURLINFO_HTTP_CODE);

        if ($statusCode < 200 || $statusCode > 299) {
            $exception = "\\Atproto\\Exceptions\\Http\\Response\\$this->content[error]";

            if (class_exists($exception)) {
                throw new $exception($this->content['message'], $statusCode);
            }

            throw new BlueskyException($this->content['message'], $statusCode);
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
            CURLOPT_HTTPHEADER     => $this->headers(true),
            CURLOPT_HEADERFUNCTION => [$this, 'handleResponseHeader'],
            CURLOPT_CUSTOMREQUEST  => $this->method(),
            CURLOPT_POSTFIELDS     => $this->parameters(true),
            CURLOPT_URL            => $this->url(),
        ]);

        $this->content = curl_exec($this->resource);
    }
}