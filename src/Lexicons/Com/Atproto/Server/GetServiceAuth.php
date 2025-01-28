<?php

namespace Atproto\Lexicons\Com\Atproto\Server;

use Atproto\Contracts\LexiconContract;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Lexicons\APIRequest;
use Atproto\Lexicons\Traits\AuthenticatedEndpoint;
use Atproto\Responses\Com\Atproto\Server\GetServiceAuthResponse;

/**
 * @method GetServiceAuthResponse send()
 */
class GetServiceAuth extends APIRequest implements LexiconContract
{
    use AuthenticatedEndpoint;

    public function aud(string $aud = null): GetServiceAuth
    {
        if (is_null($aud)) {
            return $this->queryParameter('aud');
        }

        $this->queryParameter('aud', $aud);

        return $this;
    }

    public function lxm(string $lxm = null): GetServiceAuth
    {
        if (is_null($lxm)) {
            return $this->queryParameter('lxm');
        }

        $this->queryParameter('lxm', $lxm);

        return $this;
    }

    public function exp(int $exp = null): GetServiceAuth
    {
        if (is_null($exp)) {
            return $this->queryParameter('exp');
        }

        $this->queryParameter('exp', $exp);

        return $this;
    }

    public function response(array $data): ResponseContract
    {
        return new GetServiceAuthResponse($data);
    }

    public function build(): RequestContract
    {
        return $this;
    }
}