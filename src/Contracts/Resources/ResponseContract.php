<?php

namespace Atproto\Contracts\Resources;

use Atproto\API\App\Bsky\Actor\GetProfile;
use Atproto\API\Com\Atrproto\Repo\CreateRecord;
use Atproto\API\Com\Atrproto\Repo\UploadBlob;
use Atproto\Exceptions\Resource\BadAssetCallException;
use Psr\Http\Message\ResponseInterface;

/**
 * @see GetProfile
 * @see CreateRecord
 * @see UploadBlob
 */
interface ResponseContract extends ResponseInterface
{
    public function get($name);

    /**
     * @param  string  $name
     * @return bool
     */
    public function exist(string $name): bool;
}
