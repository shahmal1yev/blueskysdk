<?php

namespace Atproto\API\Com\Atrproto\Repo;

use Atproto\Contracts\HTTP\RequestContract;
use Atproto\Contracts\RecordBuilderContract;
use Atproto\Exceptions\Http\Request\RequestBodyHasMissingRequiredFields;
use Atproto\Resources\Com\Atproto\Repo\CreateRecordResource;
use InvalidArgumentException;

/**
 * @deprecated This class deprecated and will be removed in a future version.
 */
class CreateRecord implements RequestContract
{
    /** @var object $body The request body */
    private $body;

    /** @var array The request headers */
    private $headers = [
        'Accept'       => 'application/json',
        'Content-Type' => 'application/json',
    ];

    public function __construct()
    {
        trigger_error(
            "This class deprecated and will be removed in a future version.",
            E_USER_DEPRECATED
        );

        $this->body = (object) [
            'repo' => '',
            'rkey' => '',
            'validate' => true,
            'record' => [],
            'collection' => 'app.bsky.feed.post',
            'swapCommit' => '',
        ];
    }

    /**
     * Set the repository for the record.
     *
     * @param string $repo The repository name
     * @return $this
     * @throws InvalidArgumentException If $repo is not a string
     */
    public function setRepo($repo)
    {
        if (!is_string($repo))
            throw new InvalidArgumentException("'repo' must be a string");

        $this->body->repo = $repo;

        return $this;
    }

    /**
     * Get the repository for the record.
     *
     * @return string The repository name
     */
    public function getRepo()
    {
        return $this->body->repo;
    }

    /**
     * Set the key for the record.
     *
     * @param string $rkey The record key
     * @return $this
     * @throws InvalidArgumentException If $rkey is not a string or its length is invalid
     */
    public function setRkey($rkey)
    {
        if (! is_string($rkey))
            throw new InvalidArgumentException("'key' must be a string");

        if (strlen($rkey) > 15 || 1 > strlen($rkey))
            throw new InvalidArgumentException("'key' length must be between 1 and 15 characters");

        $this->body->rkey = $rkey;

        return $this;
    }

    /**
     * Get the key for the record.
     *
     * @return string The record key
     */
    public function getRkey()
    {
        return $this->body->rkey;
    }

    /**
     * Set whether to validate the record.
     *
     * @param bool $validate Whether to validate the record
     * @return $this
     * @throws InvalidArgumentException If $validate is not a boolean
     */
    public function setValidate($validate)
    {
        if (! is_bool($validate))
            throw new InvalidArgumentException("'validate' must be a boolean");

        $this->body->validate = $validate;

        return $this;
    }

    /**
     * Get whether to validate the record.
     *
     * @return bool Whether to validate the record
     */
    public function getValidate()
    {
        return $this->body->validate;
    }

    /**
     * Set the record data.
     *
     * @param RecordBuilderContract $record The record data
     * @return $this
     * @throws InvalidArgumentException If the record data is invalid
     */
    public function setRecord(RecordBuilderContract $record)
    {
        $this->body->record = $record->buildRecord();

        return $this;
    }

    /**
     * Get the record data.
     *
     * @return array The record data
     */
    public function getRecord()
    {
        return $this->body->record;
    }

    /**
     * Set the collection to store the record in.
     *
     * @param string $collection The collection name
     * @return $this
     * @throws InvalidArgumentException If $collection is not a string or it is not one of the acceptable collections
     */
    public function setCollection($collection)
    {
        $acceptableCollections = [
            'app.bsky.feed.post',
            'app.bsky.feed.like',
            'app.bsky.actor.profile',
            'app.bsky.graph.follow'
        ];

        if (! is_string($collection))
            throw new InvalidArgumentException("'collection' must be a string");

        if (! in_array($collection, $acceptableCollections))
            throw new InvalidArgumentException("'collection' must be one of '" . implode("', '", $acceptableCollections) . "'");

        $this->body->collection = $collection;

        return $this;
    }

    /**
     * Get the collection to store the record in.
     *
     * @return string The collection name
     */
    public function getCollection()
    {
        return $this->body->collection;
    }

    /**
     * Set the swap commit.
     *
     * @param mixed $swapCommit The swap commit value
     * @return $this
     */
    public function swapCommit($swapCommit)
    {
        $this->body->swapCommit = $swapCommit;

        return $this;
    }

    /**
     * Get the swap commit.
     *
     * @return mixed The swap commit value
     */
    public function getSwapCommit()
    {
        return $this->body->swapCommit;
    }

    /**
     * Get the URI for the request.
     *
     * @return string The URI
     */
    public function getUri()
    {
        return '/com.atproto.repo.createRecord';
    }

    /**
     * Get the HTTP method for the request.
     *
     * @return string The HTTP method
     */
    public function getMethod()
    {
        return 'POST';
    }

    /**
     * Get the headers for the request.
     *
     * @return array The headers
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
     * Get the body for the request.
     *
     * @return string The body
     * @throws RequestBodyHasMissingRequiredFields If required fields are missing in the body
     */
    public function getBody()
    {
        $fields = array_filter([
            'repo' => $this->body->repo,
            'rkey' => $this->body->rkey,
            'validate' => $this->body->validate,
            'record' => $this->body->record,
            'collection' => $this->body->collection,
            'swapCommit' => $this->body->swapCommit,
        ]);

        $requiredFields = [
            'repo',
            'record',
            'collection'
        ];

        $missingFields = array_diff(
            $requiredFields,
            array_keys($fields)
        );

        if (! empty($missingFields))
            throw new RequestBodyHasMissingRequiredFields(implode(', ', $missingFields));

        return json_encode($fields);
    }

    /**
     * Check if authentication is required for the request.
     *
     * @return bool True if authentication is required, false otherwise
     */
    public function authRequired()
    {
        return true;
    }

    /**
     * Boot the request with authentication response.
     *
     * @param mixed $authResponse The authentication response
     */
    public function boot($authResponse)
    {
        $this->headers = array_merge($this->headers, [
            "Authorization" => "Bearer $authResponse->accessJwt"
        ]);

        $this->body->repo = $authResponse->did;
    }
}