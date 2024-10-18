<?php

namespace Atproto\Lexicons\Com\Atproto\Repo;

use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Contracts\LexiconContract;
use Atproto\Contracts\RequestContract;
use Atproto\Exceptions\Http\MissingFieldProvidedException;
use Atproto\Lexicons\APIRequest;
use Atproto\Resources\Com\Atproto\Repo\CreateRecordResource;

class CreateRecord extends APIRequest implements LexiconContract
{
    protected array $required = [
        'repo',
        'collection',
        'record'
    ];

    public function repo(string $repo = null)
    {
        if (is_null($repo)) {
            return $this->parameter('repo') ?? null;
        }

        $this->parameter('repo', $repo);

        return $this;
    }

    public function collection(string $collection = null)
    {
        if (is_null($collection)) {
            return $this->parameter('collection') ?? null;
        }

        $this->parameter('collection', $collection);

        return $this;
    }

    public function rkey(string $rkey = null)
    {
        if (is_null($rkey)) {
            return $this->parameter('rkey') ?? null;
        }

        $this->parameter('rkey', $rkey);

        return $this;
    }

    public function validate(bool $validate = null)
    {
        if (is_null($validate)) {
            return $this->parameter('validate') ?? null;
        }

        $this->parameter('validate', $validate);

        return $this;
    }

    public function record(object $record = null)
    {
        if (is_null($record)) {
            return $this->parameter('record') ?? null;
        }

        $this->parameter('record', $record);

        return $this;
    }

    public function swapCommit(string $swapCommit = null)
    {
        if (is_null($swapCommit)) {
            return $this->parameter('swapCommit') ?? null;
        }

        $this->parameter('swapCommit', $swapCommit);

        return $this;
    }

    /**
     * @throws MissingFieldProvidedException
     */
    public function build(): RequestContract
    {
        $parameters = array_keys($this->parameters(false));
        $missing = array_diff(
            $this->required,
            $parameters
        );

        if (count($missing)) {
            throw new MissingFieldProvidedException(implode(", ", $missing));
        }

        return $this;
    }

    public function resource(array $data): ResourceContract
    {
        return new CreateRecordResource($data);
    }

    public function __toString(): string
    {
        return json_encode($this);
    }

    public function jsonSerialize()
    {
        return array_filter([
            'repo' => $this->repo(),
            'collection' => $this->collection(),
            'record' => $this->record(),
            'swapCommit' => $this->swapCommit(),
            'validate' => $this->validate(),
        ]);
    }
}
