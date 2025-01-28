<?php

namespace Atproto\Lexicons\App\Bsky\Video;

use Atproto\Client;
use Atproto\Contracts\LexiconContract;
use Atproto\Contracts\Lexicons\RequestContract;
use Atproto\Contracts\Resources\ResponseContract;
use Atproto\Enums\SearchPost\SortEnum;
use Atproto\Exceptions\BlueskyException;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\APIRequest;
use Atproto\Lexicons\Traits\AuthenticatedEndpoint;
use Atproto\Responses\App\Bsky\Video\UploadVideoResponse;
use Atproto\Support\FileSupport;

/**
 * @method UploadVideoResponse send()
 */
class UploadVideo extends APIRequest implements LexiconContract
{
    use AuthenticatedEndpoint;
    private FileSupport $file;

    protected function initialize(): void
    {
        $this->origin('https://video.bsky.app/')
            ->headers(self::API_BASE_HEADERS)
            ->header('Content-Type', $this->file->getMimeType())
            ->path(sprintf("/xrpc/%s", $this->nsid()))
            ->method('POST');
    }

    public function __construct(Client $client, string $name, FileSupport $file, string $token = null)
    {
        $this->file = $file;

        parent::__construct($client);
        $this->update($client);

        $this->body($this->file)
            ->name($name)
            ->token($token);
    }

    public function body(FileSupport $file = null)
    {
        if (is_null($file)) {
            return $this->parameter('blob');
        }

        $blob = $this->file->getBlob();

        $this->parameter('blob', $blob);

        return $this;
    }

    public function name(string $name = null)
    {
        if (is_null($name)) {
            return $this->queryParameter('name');
        }

        $this->queryParameter('name', $name);

        return $this;
    }

    public function parameters($parameters = null)
    {
        return $this->body();
    }


    public function response(array $data): ResponseContract
    {
        return new UploadVideoResponse($data);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function build(): RequestContract
    {
        if (is_null($this->body())) {
            throw new InvalidArgumentException("Body is required");
        }

        return $this;
    }

    public function send(): ResponseContract
    {
        try {
            return parent::send();
        } catch (BlueskyException $ex) {
            if ($ex->getCode() === 409 && $ex->getMessage() === 'Video already processed') {
                return $this->response($this->content());
            } else {
                throw $ex;
            }
        }
    }
}
