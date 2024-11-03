<?php

namespace Atproto\Lexicons;

use Atproto\Client;
use Atproto\Contracts\Lexicons\APIRequestContract;
use Atproto\Contracts\Resources\ResourceContract;
use SplSubject;

abstract class APIRequest extends Request implements APIRequestContract
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
        $this->initialize();
    }

    public function send(): ResourceContract
    {
        return $this->resource(parent::send());
    }

    abstract protected function initialize(): void;

    abstract public function resource(array $data): ResourceContract;

    public function update(SplSubject $subject): void
    {
        /** @var Client $subject */
        $this->client = $subject;
    }
}
