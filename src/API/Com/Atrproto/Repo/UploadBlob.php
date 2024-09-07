<?php

namespace Atproto\API\Com\Atrproto\Repo;

use Atproto\Contracts\HTTP\RequestContract;
use Atproto\Exceptions\Http\Request\RequestBodyHasMissingRequiredFields;
use Atproto\Helpers\File;
use Atproto\Resources\Com\Atproto\Repo\UploadBlobResource;
use InvalidArgumentException;

/**
 * Class UploadBlobRequest
 *
 * Represents a request to upload a blob.
 */
class UploadBlob implements RequestContract
{
    /** @var object $body The request body */
    private $body;

    /** @var File The blob content. */
    private $blob;

    /** @var array The headers for the request. */
    private $headers = [
        'Content-Type' => '*/*',
        'Accept' => 'application/json',
    ];

    /**
     * The class constructor.
     *
     * @return void
     */
    public function __construct()
    {
        $this->body = (object) [];
    }

    /**
     * Set the blob content.
     *
     * @param string $filePath The path to the blob file.
     * @return $this
     * @throws InvalidArgumentException If the blob path is invalid or blob size exceeds the maximum allowed.
     */
    public function setBlob($filePath)
    {
        $file = new File($filePath);

        if (! $file->exists())
            throw new InvalidArgumentException("File '$filePath' does not exist");

        if (! $file->isFile())
            throw new InvalidArgumentException("File '$filePath' is not a file");

        $maxSize = 1000000;
        if ($file->getFileSize() > $maxSize)
            throw new InvalidArgumentException("File '$filePath' is too big. Max file size is $maxSize bytes.");

        $this->body->blob = $file;

        return $this;
    }

    /**
     * Get the blob content.
     *
     * @return ?File The blob content.
     */
    public function getBlob()
    {
        return $this->body->blob;
    }

    /**
     * Get the body of the request.
     *
     * @return array The body of the request.
     * @throws RequestBodyHasMissingRequiredFields If the blob field is missing.
     */
    public function getBody()
    {
        if (! isset($this->body->blob))
            throw new RequestBodyHasMissingRequiredFields(implode(', ', ['blob']));

        return $this->body
            ->blob
            ->getBlob();
    }

    /**
     * Get the headers for the request.
     *
     * @return array The headers for the request.
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Set the headers for the request.
     *
     * @param array $headers The header/s for the request.
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
     * Get the HTTP method for the request.
     *
     * @return string The HTTP method for the request.
     */
    public function getMethod()
    {
        return 'POST';
    }

    /**
     * Get the URI for the request.
     *
     * @return string The URI for the request.
     */
    public function getUri()
    {
        return '/com.atproto.repo.uploadBlob';
    }

    /**
     * Check if authentication is required for the request.
     *
     * @return bool True if authentication is required, false otherwise.
     */
    public function authRequired()
    {
        return true;
    }

    /**
     * Boot the request with authentication response.
     *
     * @param object $authResponse The authentication response.
     */
    public function boot($authResponse)
    {
        $this->headers = array_merge($this->headers, [
            'Authorization' => "Bearer $authResponse->accessJwt"
        ]);
    }
}
