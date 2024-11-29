<?php

namespace Atproto\Lexicons\Com\Atproto\Repo;

use Atproto\Contracts\HTTP\AuthEndpointLexiconContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\Traits\AuthenticatedEndpoint;
use Atproto\Responses\Com\Atproto\Repo\UploadBlobResponse;
use Atproto\Support\FileSupport;

class UploadBlob implements AuthEndpointLexiconContract
{
    use AuthenticatedEndpoint;

    /**
     * @throws InvalidArgumentException
     */
    public function blob(string $blob = null)
    {
        if (is_null($blob)) {
            return hex2bin($this->parameter('blob')) ?? null;
        }

        $blob = (! mb_check_encoding($blob, 'UTF-8'))
            ? $blob
            : (new FileSupport($blob))->getBlob();

        return $this->parameter('blob', bin2hex($blob));
    }

    public function parameters($parameters = null)
    {
        if ($parameters === true) {
            return $this->blob();
        }

        return $this->parameters($parameters);
    }

    public function token(string $token = null)
    {
        if (is_null($token)) {
            $token = $this->header('Authorization');
            return trim(substr($token, strrpos($token, ' '))) ?: null;
        }

        return $this->header('Authorization', "Bearer $token");
    }

    public function response(ResponseContract $data): ResponseContract
    {
        return new UploadBlobResponse($data);
    }
}
