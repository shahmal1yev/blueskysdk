<?php

namespace Atproto\HTTP\API;

use Atproto\Client;
use Atproto\Contracts\HTTP\APIRequestContract;
use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\HTTP\Request;
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

    protected function initialize(): void
    {
        $this->origin(self::API_BASE_URL)
            ->path($this->endpoint())
            ->headers(self::API_BASE_HEADERS);
    }

    private function endpoint(): string
    {
        $endpointParts = explode("\\", $this->client->path());

        $endpoint = array_reduce(
            $endpointParts,
            fn ($carry, $part) => $carry .= "." . lcfirst($part)
        );

        return sprintf("/xrpc/%s", trim($endpoint, "."));
    }

    abstract public function resource(array $data): ResourceContract;

    public function update(SplSubject $subject): void
    {
        /** @var Client $subject */
        $this->client = $subject;
    }
}
