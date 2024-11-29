<?php

namespace Atproto\Lexicons\App\Bsky\Actor;

use Atproto\Contracts\HTTP\AuthEndpointLexiconContract;
use Atproto\Contracts\LexiconContract;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Exceptions\Auth\AuthRequired;
use Atproto\Exceptions\Http\MissingFieldProvidedException;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\Traits\AuthenticatedEndpoint;
use Atproto\Responses\App\Bsky\Actor\GetProfilesResponse;
use GenericCollection\Interfaces\GenericCollectionInterface;
use GenericCollection\Types\Primitive\StringType;

class GetProfiles implements AuthEndpointLexiconContract
{
    use AuthenticatedEndpoint;

    private ?GenericCollectionInterface $actors = null;

    public function response(ResponseContract $data): ResponseContract
    {
        return new GetProfilesResponse($data);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function actors(GenericCollectionInterface $actors = null)
    {
        if (is_null($actors)) {
            return $this->actors;
        }

        if ($actors->gettype() !== StringType::class) {
            throw new InvalidArgumentException(sprintf(
                "'\$actors' collection must be of type '%s' but is of type '%s'",
                StringType::class,
                $actors->gettype()
            ));
        }

        if (! ($actors->count() >= 1 && $actors->count() <= 25)) {
            throw new InvalidArgumentException("'\$actors' collection count must be between 1 and 25");
        }

        $this->actors = $actors;

        return $this->queryParameter('actors', array_values($this->actors->toArray()));
    }

    /**
     * @throws AuthRequired
     * @throws MissingFieldProvidedException
     */
    public function build(): RequestContract
    {
        if (is_null($this->header('Authorization'))) {
            throw new AuthRequired();
        }

        if (is_null($this->actors)) {
            throw new MissingFieldProvidedException("actors");
        }

        return $this;
    }
}
