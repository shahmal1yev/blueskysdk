<?php

namespace Atproto\API\Com\Atrproto\Repo;

use Atproto\Contracts\HTTP\RequestContract;
use InvalidArgumentException;

class CreateRecordRequest implements RequestContract
{
    /** @var string The repository to create the record in */
    private $repo = '';

    /** @var string The key of the record */
    private $rkey = '';

    /** @var bool Whether to validate the record */
    private $validate = true;

    /** @var array The record data */
    private $record = [];

    /** @var string The collection to store the record in */
    private $collection = 'app.bsky.feed.post';

    /** @var mixed The swap commit */
    private $swapCommit;

    /** @var array The request headers */
    private $headers = [
        'Accept: application/json',
        'Content-Type: application/json',
    ];

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

        $this->repo = $repo;

        return $this;
    }

    /**
     * Get the repository for the record.
     *
     * @return string The repository name
     */
    public function getRepo()
    {
        return $this->repo;
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

        $this->rkey = $rkey;

        return $this;
    }

    /**
     * Get the key for the record.
     *
     * @return string The record key
     */
    public function getRkey()
    {
        return $this->rkey;
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

        $this->validate = $validate;

        return $this;
    }

    /**
     * Get whether to validate the record.
     *
     * @return bool Whether to validate the record
     */
    public function getValidate()
    {
        return $this->validate;
    }

    /**
     * Set the record data.
     *
     * @param array $record The record data
     * @return $this
     * @throws InvalidArgumentException If the record data is invalid
     */
    public function setRecord(array $record)
    {
        if (! isset($record['text']))
            throw new InvalidArgumentException("'record' must contain 'text' key");

        if (! is_string($record['text']))
            throw new InvalidArgumentException("'text' must be a string");

        if (! isset($record['createdAt']))
            $record['createdAt'] = date('c');
        else if (date_create_from_format('c', $record['createdAt']) === false)
            throw new InvalidArgumentException("'createdAt' must be a valid date format. Use 'c' format instead.");

        $this->record = $record;

        return $this;
    }

    /**
     * Get the record data.
     *
     * @return array The record data
     */
    public function getRecord()
    {
        return $this->record;
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

        $this->collection = $collection;

        return $this;
    }

    /**
     * Get the collection to store the record in.
     *
     * @return string The collection name
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Set the swap commit.
     *
     * @param mixed $swapCommit The swap commit value
     * @return $this
     */
    public function swapCommit($swapCommit)
    {
        $this->swapCommit = $swapCommit;

        return $this;
    }

    /**
     * Get the swap commit.
     *
     * @return mixed The swap commit value
     */
    public function getSwapCommit()
    {
        return $this->swapCommit;
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
     * Get the body for the request.
     *
     * @return array The body
     * @throws InvalidArgumentException If required fields are missing in the body
     */
    public function getBody()
    {
        $fields = array_filter([
            'repo' => $this->repo,
            'rkey' => $this->rkey,
            'validate' => $this->validate,
            'record' => $this->record,
            'collection' => $this->collection,
            'swapCommit' => $this->swapCommit,
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
            throw new InvalidArgumentException("Request body has missing fields: " . implode(', ', $missingFields));

        return $fields;
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
        $this->headers = array_merge(
            $this->headers,
            ["Authorization: Bearer $authResponse->accessJwt"]
        );

        $this->repo = $authResponse->did;
    }
}