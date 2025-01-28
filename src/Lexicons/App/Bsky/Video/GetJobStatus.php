<?php

namespace Atproto\Lexicons\App\Bsky\Video;

use Atproto\Client;
use Atproto\Contracts\LexiconContract;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Lexicons\APIRequest;
use Atproto\Lexicons\Traits\AuthenticatedEndpoint;
use Atproto\Responses\App\Bsky\Video\GetJobStatusResponse;

/** @method GetJobStatusResponse send() */
class GetJobStatus extends APIRequest implements LexiconContract
{
    use AuthenticatedEndpoint;

    public function __construct(Client $client, string $jobId)
    {
        parent::__construct($client);
        $this->update($client);

        $this->jobId($jobId);
    }

    protected function initialize(): void
    {
        $this->origin('https://video.bsky.app/')
            ->headers(self::API_BASE_HEADERS)
            ->path(sprintf("/xrpc/%s", $this->nsid()));
    }

    public function jobId(string $jobId = null)
    {
        if (is_null($jobId)) {
            return $this->queryParameter('jobId');
        }

        $this->queryParameter('jobId', $jobId);

        return $this;
    }

    public function response(array $data): ResponseContract
    {
        return new GetJobStatusResponse($data);
    }

    public function build(): RequestContract
    {
        return $this;
    }
}
