<?php

namespace Atproto\Lexicons\Com\Atproto\Repo;

use Atproto\Contracts\HTTP\AuthEndpointLexiconContract;
use Atproto\Contracts\LexiconContract;
use Atproto\Contracts\Lexicons\App\Bsky\Feed\PostBuilderContract;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Exceptions\Http\MissingFieldProvidedException;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\Traits\AuthenticatedEndpoint;
use Atproto\Responses\Com\Atproto\Repo\CreateRecordResponse;

class CreateRecord implements AuthEndpointLexiconContract
{
    use AuthenticatedEndpoint;

    private ?PostBuilderContract $record;
    public function repo(string $repo = null)
    {
        if (is_null($repo)) {
            return $this->parameter('repo') ?? null;
        }

        return $this->parameter('repo', $repo);
    }

    public function collection(string $collection = null)
    {
        if (is_null($collection)) {
            return $this->parameter('collection') ?? null;
        }

        return $this->parameter('collection', $collection);
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

        return $this->parameter('rkey', $rkey);

    }

    public function validate(bool $validate = null)
    {
        if (is_null($validate)) {
            return $this->parameter('validate') ?? null;
        }

        return $this->parameter('validate', $validate);
    }

    public function record(PostBuilderContract $record = null)
    {
        if (is_null($record)) {
            return $this->record ?? null;
        }

        $this->record = $record;

        return $this->parameter('record', $record);

    }

    public function swapCommit(string $swapCommit = null)
    {
        if (is_null($swapCommit)) {
            return $this->parameter('swapCommit') ?? null;
        }

        return $this->parameter('swapCommit', $swapCommit);
    }

    public function response(ResponseContract $data): ResponseContract
    {
        return new CreateRecordResponse($data);
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
