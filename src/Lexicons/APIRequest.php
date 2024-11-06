<?php

namespace Atproto\Lexicons;

use Atproto\Client;
use Atproto\Contracts\Lexicons\APIRequestContract;
use Atproto\Contracts\Resources\ResponseContract;
use SplSubject;

abstract class APIRequest extends Request implements APIRequestContract
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->initialize();
    }

    public function send(): ResponseContract
    {
        return $this->response(parent::send());
    }

    abstract protected function initialize(): void;

    abstract public function response(array $data): ResponseContract;

    public function update(SplSubject $subject): void
    {
        /** @var Client $subject */
        $this->client = $subject;
    }
}
