<?php

namespace Atproto\Lexicons\Com\Atproto\Repo;

use Atproto\Contracts\HTTP\Resources\ResourceContract;
use Atproto\Contracts\RequestContract;
use Atproto\DataModel\Blob\Blob;
use Atproto\Exceptions\Http\MissingFieldProvidedException;
use Atproto\Exceptions\InvalidArgumentException;
use Atproto\Lexicons\APIRequest;
use Atproto\Resources\Com\Atproto\Repo\UploadBlobResource;
use Atproto\Support\FileSupport;

class UploadBlob extends APIRequest
{
    protected function initialize(): void
    {
        parent::initialize();
        $this->method('POST')
            ->header('Content-Type', '*/*');
    }

    /**
     * @throws InvalidArgumentException
     */
    public function blob(string $blob = null)
    {
        if (is_null($blob)) {
            return hex2bin($this->parameter('blob'));
        }

        $blob = (! mb_check_encoding($blob, 'UTF-8'))
            ? $blob
            : (new FileSupport($blob))->getBlob();

        $this->parameter('blob', bin2hex($blob));

        return $this;
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

    public function resource(array $data): ResourceContract
    {
        return new UploadBlobResource([
            'blob' => Blob::viaBinary(hex2bin($this->parameter('blob')))
        ]);
    }
}
