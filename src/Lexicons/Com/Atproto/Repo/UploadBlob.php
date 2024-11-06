<?php

namespace Atproto\Lexicons\Com\Atproto\Repo;

use Atproto\Contracts\LexiconContract;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\DataModel\Blob\Blob;
use Atproto\Exceptions\Http\MissingFieldProvidedException;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\APIRequest;
use Atproto\Lexicons\Traits\AuthenticatedEndpoint;
use Atproto\Responses\Com\Atproto\Repo\UploadBlobResponse;
use Atproto\Support\FileSupport;

class UploadBlob extends APIRequest implements LexiconContract
{
    use AuthenticatedEndpoint;

    protected function initialize(): void
    {
        $this->origin(self::API_BASE_URL)
            ->headers(self::API_BASE_HEADERS)
            ->header('Content-Type', '*/*')
            ->path(sprintf("/xrpc/%s", $this->nsid()))
            ->method('POST');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function blob(string $blob = null)
    {
        if (is_null($blob)) {
            return $this->parameter('blob');
        }

        $blob = (! mb_check_encoding($blob, 'UTF-8'))
            ? $blob
            : (new FileSupport($blob))->getBlob();

        $this->parameter('blob', $blob);

        return $this;
    }

    public function parameters($parameters = null)
    {
        if (is_bool($parameters)) {
            return $this->parameters['blob'] ?: '';
        }

        parent::parameters($parameters);
    }

    public function token(string $token = null)
    {
        if (is_null($token)) {
            $token = $this->header('Authorization');
            return trim(substr($token, strrpos($token, ' '))) ?: null;
        }

        $this->header('Authorization', "Bearer $token");

        return $this;
    }

    /**
     * @throws MissingFieldProvidedException
     */
    public function build(): RequestContract
    {
        $missing = array_filter(
            ['token' => $this->header('Authorization'), 'blob' => $this->parameter('blob')],
            fn ($value) => is_null($value),
        );

        if (count($missing)) {
            throw new MissingFieldProvidedException(implode(", ", array_keys($missing)));
        }

        return $this;
    }

    public function response(array $data): ResponseContract
    {
        return new UploadBlobResponse([
            'blob' => Blob::viaBinary($this->parameter('blob'))
        ]);
    }
}
