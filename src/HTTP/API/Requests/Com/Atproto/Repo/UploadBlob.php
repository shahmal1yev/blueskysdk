<?php

namespace Atproto\HTTP\API\Requests\Com\Atproto\Repo;

use Atproto\Contracts\RequestContract;
use Atproto\Exceptions\Http\MissingFieldProvidedException;
use Atproto\HTTP\API\APIRequest;

class UploadBlob extends APIRequest
{
    protected ?string $blob = null;
    protected ?string $token = null;

    public function blob(string $blob = null)
    {
        if (is_null($blob)) {
            return $this->blob;
        }

        $this->blob = $blob;

        return $this;
    }

    public function token(string $token = null)
    {
        if (is_null($token)) {
            return $this->token;
        }

        $this->token = $token;

        $this->header('Authorization', "Bearer $this->token");

        return $this;
    }

    /**
     * @throws MissingFieldProvidedException
     */
    public function build(): RequestContract
    {
        $missing = array_filter(
            [$this->token => 'token', $this->blob => 'blob'],
            fn ($key, $value) => ! $value,
            ARRAY_FILTER_USE_BOTH
        );

        if (count($missing)) {
            throw new MissingFieldProvidedException(implode(", ", $missing));
        }

        return $this;
    }
}