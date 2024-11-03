<?php

namespace Atproto\Lexicons\Com\Atproto\Repo;

use Atproto\Contracts\LexiconContract;
use Atproto\Contracts\Lexicons\App\Bsky\Feed\PostBuilderContract;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Contracts\Resources\ResourceContract;
use Atproto\Exceptions\Http\MissingFieldProvidedException;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\APIRequest;
use Atproto\Lexicons\Traits\AuthenticatedEndpoint;
use Atproto\Resources\Com\Atproto\Repo\CreateRecordResource;

class CreateRecord extends APIRequest implements LexiconContract
{
    use AuthenticatedEndpoint;

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

    /**
     * @throws InvalidArgumentException
     */
    public function rkey(string $rkey = null)
    {
        if (is_null($rkey)) {
            return $this->parameter('rkey') ?? null;
        }

        if (strlen($rkey) > 15) {
            throw new InvalidArgumentException("The 'rkey' must be a maximum of 15 characters.");
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

    public function record(PostBuilderContract $record = null)
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

    public function jsonSerialize(): array
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
